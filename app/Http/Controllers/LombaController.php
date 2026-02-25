<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LombaController extends Controller
{
    /**
     * Menampilkan form pendaftaran event
     */
    public function showRegisterForm($id)
    {
        // Get event data
        $event = DB::table('lomba')->where('id', $id)->first();
        
        if (!$event) {
            return redirect()->route('home')
                ->with('error', 'Event tidak ditemukan.');
        }
        
        // Check if event is still available for registration
        if ($event->status !== 'mendatang') {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Event ini sudah tidak menerima pendaftaran.');
        }
        
        // Check quota
        $totalPendaftar = DB::table('pendaftaran')
            ->where('id_lomba', $id)
            ->whereIn('status_pendaftaran', ['menunggu', 'disetujui'])
            ->count();
        
        $kuotaTersedia = $event->kuota_peserta - $totalPendaftar;
        
        if ($kuotaTersedia <= 0) {
            return redirect()->route('event.detail', $id)
                ->with('error', 'Kuota event ini sudah penuh.');
        }
        
        // If user is logged in, show registration form
        if (session('is_logged_in')) {
            // Check if user already registered
            $alreadyRegistered = DB::table('pendaftaran')
                ->where('id_lomba', $id)
                ->where('id_pengguna', session('user_id'))
                ->whereIn('status_pendaftaran', ['menunggu', 'disetujui'])
                ->exists();
                
            if ($alreadyRegistered) {
                return redirect()->route('event.detail', $id)
                    ->with('error', 'Anda sudah terdaftar di event ini.');
            }
            
            // Get user data for auto-fill
            $user = DB::table('pengguna')->where('id', session('user_id'))->first();
            
            // Get packages
            $packages = DB::table('paket')->where('lomba_id', $id)->get();
            
            if ($packages->isEmpty()) {
                return redirect()->route('event.detail', $id)
                    ->with('error', 'Belum ada paket tersedia untuk event ini.');
            }
            
            return view('event.register', compact('event', 'user', 'packages', 'kuotaTersedia'));
        } 
        // If user is NOT logged in, show login/register options
        else {
            // Save event ID to session for redirection after login/register
            session(['pending_event_id' => $id]);
            session(['pending_event_name' => $event->nama]);
            
            return view('event.auth-options', compact('event'));
        }
    }

    /**
     * Menyimpan pendaftaran event
     */
    public function storeRegistration(Request $request, $id)
    {
        // 1. Validasi Input sesuai kolom tabel pendaftaran
        $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'telepon'           => 'required|string|max:20',
            'alamat'            => 'required|string',
            'tanggal_lahir'     => 'required|date',
            'jenis_kelamin'     => 'required|in:L,P',
            'ukuran_jersey'     => 'nullable|in:XS,S,M,L,XL,XXL',
            'id_paket'          => 'required|exists:paket,id',
            'metode_pembayaran' => 'required|in:transfer,onsite',
            'bukti_pembayaran'  => 'required_if:metode_pembayaran,transfer|image|max:2048', 
        ]);

        DB::beginTransaction();
        try {
            $metode = $request->metode_pembayaran;
            $pathBukti = null;

            // Ambil data paket untuk harga
            $paket = DB::table('paket')->where('id', $request->id_paket)->first();

            // 2. Handle Upload Bukti Transfer
            if ($metode == 'transfer' && $request->hasFile('bukti_pembayaran')) {
                $file = $request->file('bukti_pembayaran');
                $filename = 'pay_' . time() . '_' . session('user_id') . '.' . $file->getClientOriginalExtension();
                $pathBukti = $file->storeAs('payments', $filename, 'public');
            }

            // 3. Status Logic (Auto-Approved via Transfer)
            $statusPendaftaran = ($metode == 'transfer') ? 'disetujui' : 'menunggu';
            $statusPembayaran  = ($metode == 'transfer') ? 'lunas' : 'menunggu';

            // 4. Generate Nomor Start Otomatis jika Transfer
            $nomorStart = null;
            if ($metode == 'transfer') {
                $event = DB::table('lomba')->where('id', $id)->first();
                $prefix = strtoupper(substr($event->nama ?? 'RUN', 0, 3));
                $count = DB::table('pendaftaran')->where('id_lomba', $id)->count() + 1;
                $nomorStart = $prefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            // 5. Simpan ke Tabel Pendaftaran
            $registrationId = DB::table('pendaftaran')->insertGetId([
                'id_pengguna'        => session('user_id'),
                'id_lomba'           => $id,
                'id_paket'           => $request->id_paket,
                'nama_lengkap'       => $request->nama_lengkap,
                'email'              => $request->email,
                'telepon'            => $request->telepon,
                'alamat'             => $request->alamat,
                'tanggal_lahir'      => $request->tanggal_lahir,
                'jenis_kelamin'      => $request->jenis_kelamin,
                'ukuran_jersey'      => $request->ukuran_jersey,
                'catatan_khusus'     => $request->catatan_khusus,
                'status'             => $statusPendaftaran,
                'status_pendaftaran' => $statusPendaftaran,
                'status_pembayaran'  => $statusPembayaran,
                'nomor_start'        => $nomorStart,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // 6. Simpan ke Tabel Pembayaran
            DB::table('pembayaran')->insert([
                'id_pendaftaran'    => $registrationId,
                'nama_pembayar'     => $request->nama_lengkap,
                'email_pembayar'    => $request->email,
                'jumlah'            => $paket->harga,
                'metode_pembayaran' => ($metode == 'transfer') ? 'transfer' : 'cash',
                'bukti_pembayaran'  => $pathBukti,
                'status'            => ($metode == 'transfer') ? 'terverifikasi' : 'menunggu',
                'tanggal_bayar'     => ($metode == 'transfer') ? now() : null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // 7. Notifikasi Staff
            if ($metode == 'transfer') {
                DB::table('notifikasi')->insert([
                    'user_id'    => 1, 
                    'judul'      => '🔔 Pendaftaran Baru (Auto)',
                    'pesan'      => "Peserta {$request->nama_lengkap} sudah lunas via TF. Cek bukti segera!",
                    'tautan'     => "/staff/registrations/view/{$registrationId}",
                    'created_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('registration.success', $registrationId)->with('success', 'Pendaftaran Berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail event (sudah ada di PublicEventController, tapi untuk backward compatibility)
     */
    public function show($id)
    {
        return app(PublicEventController::class)->show($id);
    }

    /**
     * Menampilkan daftar pendaftaran user
     */
    public function myRegistrations()
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $registrations = DB::table('pendaftaran')
            ->select('pendaftaran.*', 'lomba.nama as event_nama', 'lomba.tanggal', 
                     'lomba.lokasi', 'lomba.status as event_status', 'paket.nama as paket_nama',
                     'paket.harga as paket_harga')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->leftJoin('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->orderBy('pendaftaran.created_at', 'desc')
            ->paginate(10);

        return view('event.my-registrations', compact('registrations'));
    }

    /**
     * Continue registration setelah login
     */
    public function continueRegistration(Request $request)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $paket_id = $request->paket;
        
        if (!$paket_id) {
            return redirect()->route('home')->with('error', 'Tidak ada paket yang dipilih.');
        }
        
        // Ambil data paket dari database
        $paket = DB::table('paket')
            ->join('lomba', 'paket.lomba_id', '=', 'lomba.id')
            ->select('paket.*', 'lomba.*', 'lomba.id as event_id')
            ->where('paket.id', $paket_id)
            ->first();
        
        if (!$paket) {
            return redirect()->route('home')->with('error', 'Paket tidak ditemukan.');
        }
        
        // Check jika sudah terdaftar
        $existing_registration = DB::table('pendaftaran')
            ->where('id_pengguna', session('user_id'))
            ->where('id_lomba', $paket->event_id)
            ->first();
        
        if ($existing_registration) {
            return redirect()->route('event.detail', $paket->event_id)->with('info', 'Anda sudah terdaftar untuk event ini.');
        }
        
        return view('event-register', [
            'event' => (object)[
                'id' => $paket->event_id,
                'nama' => $paket->nama,
                'tanggal' => $paket->tanggal,
                'lokasi' => $paket->lokasi,
                'kategori' => $paket->kategori,
                'deskripsi' => $paket->deskripsi
            ],
            'packages' => [$paket]
        ]);
    }

    /**
     * Test route for registration (debug)
     */
    public function testRegister(Request $request, $id)
    {
        \Log::info('TEST ROUTE DIPANGGIL', $request->all());
        
        try {
            DB::table('pendaftaran')->insert([
                'id_pengguna' => session('user_id'),
                'id_lomba' => $id,
                'id_paket' => $request->paket_id,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'status' => 'menunggu',
                'status_pendaftaran' => 'menunggu',
                'status_pembayaran' => 'menunggu',
                'kode_pendaftaran' => 'TEST-' . time(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $regId = DB::getPdo()->lastInsertId();
            
            return redirect('/test-success/' . $regId);
            
        } catch (\Exception $e) {
            \Log::error('TEST ERROR: ' . $e->getMessage());
            return "ERROR: " . $e->getMessage();
        }
    }

    /**
     * Test success page
     */
    public function testSuccess($id)
    {
        return "<h1>BERHASIL! ID: {$id}</h1><a href='".url('/payment/instructions/' . $id)."'>Ke Pembayaran</a>";
    }
}