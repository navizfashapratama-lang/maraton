<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Menampilkan halaman sukses pendaftaran
     */
    public function success($kode)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login');
        }

        $pendaftaran = DB::table('pendaftaran')
            ->where('kode_pendaftaran', $kode)
            ->where('id_pengguna', session('user_id'))
            ->first();
            
        if (!$pendaftaran) {
            return redirect()->route('home')
                ->with('error', 'Pendaftaran tidak ditemukan.');
        }

        $event = DB::table('lomba')->find($pendaftaran->id_lomba);
        $paket = DB::table('paket')->find($pendaftaran->id_paket);

        return view('event.success', compact('pendaftaran', 'event', 'paket'));
    }

    /**
     * Menampilkan halaman sukses berdasarkan ID
     */
    public function successById($id)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $registration = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id', $id)
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->select('pendaftaran.*', 
                    'lomba.nama as event_nama', 
                    'lomba.tanggal', 
                    'lomba.lokasi',
                    'paket.nama as package_name', 
                    'paket.harga as package_price')
            ->first();
            
        if (!$registration) {
            return redirect()->route('events.index')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        return view('registration-success', compact('registration'));
    }

    /**
     * Menampilkan halaman pembayaran
     */
    public function payment($id)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $registration = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id', $id)
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->select('pendaftaran.*', 
                    'lomba.nama as event_nama', 
                    'lomba.tanggal',
                    'paket.nama as package_name', 
                    'paket.harga as package_price')
            ->first();
            
        if (!$registration) {
            return redirect()->route('profile')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        // Cek apakah sudah ada pembayaran
        $existing_payment = DB::table('pembayaran')
            ->where('id_pendaftaran', $id)
            ->first();
        
        $bank_accounts = [
            ['bank' => 'BCA', 'number' => '1234567890', 'name' => 'Marathon Events'],
            ['bank' => 'Mandiri', 'number' => '0987654321', 'name' => 'Marathon Events'],
            ['bank' => 'BRI', 'number' => '1122334455', 'name' => 'Marathon Events'],
        ];
        
        return view('payment-page', compact('registration', 'existing_payment', 'bank_accounts'));
    }

    /**
     * Menampilkan instruksi pembayaran
     */
    public function paymentInstructions($registration_id)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $registration = DB::table('pendaftaran')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
            ->where('pendaftaran.id', $registration_id)
            ->where('pendaftaran.id_pengguna', session('user_id'))
            ->select('pendaftaran.*', 
                    'lomba.nama as event_nama', 
                    'paket.nama as package_name',
                    'paket.harga as package_price',
                    'lomba.harga_reguler',
                    'lomba.harga_premium')
            ->first();
            
        if (!$registration) {
            return redirect()->route('profile')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        // Cek apakah sudah ada pembayaran
        $existing_payment = DB::table('pembayaran')
            ->where('id_pendaftaran', $registration_id)
            ->first();
        
        // Data bank untuk ditampilkan
        $bank_accounts = [
            ['bank' => 'BCA', 'number' => '1234567890', 'name' => 'Marathon Events'],
            ['bank' => 'Mandiri', 'number' => '0987654321', 'name' => 'Marathon Events'],
            ['bank' => 'BRI', 'number' => '1122334455', 'name' => 'Marathon Events'],
            ['bank' => 'BNI', 'number' => '6677889900', 'name' => 'Marathon Events'],
        ];
        
        return view('payment-instructions', compact('registration', 'existing_payment', 'bank_accounts'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadProof(Request $request, $registration_id)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $registration = DB::table('pendaftaran')
            ->where('id', $registration_id)
            ->where('id_pengguna', session('user_id'))
            ->first();
            
        if (!$registration) {
            return redirect()->route('profile')->with('error', 'Pendaftaran tidak ditemukan');
        }
        
        $package = DB::table('paket')->where('id', $registration->id_paket)->first();
        
        // Cek apakah sudah ada pembayaran
        $existing_payment = DB::table('pembayaran')
            ->where('id_pendaftaran', $registration_id)
            ->first();
        
        if ($existing_payment && $existing_payment->status != 'ditolak') {
            return redirect()->route('profile')->with('info', 'Anda sudah mengupload bukti pembayaran untuk pendaftaran ini.');
        }
        
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'metode_pembayaran' => 'required|in:transfer,bca,mandiri,bri,dana,ovo,gopay,shopeepay,linkaja,qris,lainnya',
            'jumlah' => 'required|numeric|min:' . ($package->harga ?? 0),
            'nama_pembayar' => 'required|string|max:255',
            'email_pembayar' => 'required|email',
            'bank_tujuan' => 'nullable|string|max:100',
            'nama_rekening' => 'nullable|string|max:255',
        ]);
        
        try {
            // Upload file
            $file = $request->file('bukti_pembayaran');
            $filename = 'payment_' . time() . '_' . $registration_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payments', $filename, 'public');
            
            // Generate kode pembayaran
            $kode_pembayaran = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(time() . $registration_id), 0, 6));
            
            if ($existing_payment && $existing_payment->status == 'ditolak') {
                // Update existing payment
                DB::table('pembayaran')->where('id', $existing_payment->id)->update([
                    'bukti_pembayaran' => $path,
                    'jumlah' => $request->jumlah,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'nama_pembayar' => $request->nama_pembayar,
                    'email_pembayar' => $request->email_pembayar,
                    'bank_tujuan' => $request->bank_tujuan,
                    'nama_rekening' => $request->nama_rekening,
                    'status' => 'menunggu',
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new payment
                DB::table('pembayaran')->insert([
                    'id_pendaftaran' => $registration_id,
                    'kode_pembayaran' => $kode_pembayaran,
                    'nama_pembayar' => $request->nama_pembayar,
                    'email_pembayar' => $request->email_pembayar,
                    'jumlah' => $request->jumlah,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bank_tujuan' => $request->bank_tujuan,
                    'nama_rekening' => $request->nama_rekening,
                    'bukti_pembayaran' => $path,
                    'status' => 'menunggu',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Update registration status
            DB::table('pendaftaran')->where('id', $registration_id)->update([
                'status_pembayaran' => 'menunggu',
                'updated_at' => now(),
            ]);
            
            // Log activity
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'upload_bukti_pembayaran',
                'deskripsi' => 'Mengupload bukti pembayaran untuk pendaftaran ID: ' . $registration_id,
                'tabel_terkait' => 'pembayaran',
                'id_terkait' => $registration_id,
                'created_at' => now()
            ]);
            
            // Send notification to admin
            DB::table('notifikasi')->insert([
                'user_id' => session('user_id'),
                'pendaftaran_id' => $registration_id,
                'jenis' => 'pembayaran_baru',
                'judul' => 'Bukti Pembayaran Baru',
                'pesan' => 'Terdapat bukti pembayaran baru untuk pendaftaran: ' . $registration->kode_pendaftaran,
                'tautan' => '/staff/payments',
                'dibaca' => 0,
                'created_at' => now()
            ]);
            
            return redirect()->route('profile')->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi dari admin.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pendaftaran
     */
    public function cancel(Request $request, $id)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('login');
        }

        $pendaftaran = DB::table('pendaftaran')
            ->where('id', $id)
            ->where('id_pengguna', session('user_id'))
            ->first();
            
        if (!$pendaftaran) {
            return redirect()->back()
                ->with('error', 'Pendaftaran tidak ditemukan.');
        }

        // Check if can be cancelled
        $event = DB::table('lomba')->find($pendaftaran->id_lomba);
        $today = now();
        $eventDate = Carbon::parse($event->tanggal);
        
        if ($eventDate->diffInDays($today) < 7) {
            return redirect()->back()
                ->with('error', 'Pendaftaran hanya bisa dibatalkan minimal 7 hari sebelum event.');
        }

        if ($pendaftaran->status_pendaftaran == 'disetujui' && $pendaftaran->status_pembayaran == 'lunas') {
            return redirect()->back()
                ->with('error', 'Pendaftaran yang sudah dibayar dan disetujui tidak bisa dibatalkan.');
        }

        DB::beginTransaction();

        try {
            DB::table('pendaftaran')
                ->where('id', $id)
                ->update([
                    'status_pendaftaran' => 'dibatalkan',
                    'dibatalkan_pada' => now(),
                    'alasan_pembatalan' => 'Dibatalkan oleh peserta',
                    'updated_at' => now(),
                ]);

            // Log activity
            DB::table('log_aktivitas')->insert([
                'user_id' => session('user_id'),
                'aksi' => 'batal_pendaftaran',
                'deskripsi' => "Membatalkan pendaftaran untuk event: {$event->nama}",
                'tabel_terkait' => 'pendaftaran',
                'id_terkait' => $id,
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('my.registrations')
                ->with('success', 'Pendaftaran berhasil dibatalkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Gagal membatalkan pendaftaran: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}