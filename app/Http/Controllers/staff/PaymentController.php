<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pembayaran')
            ->join('pendaftaran', 'pembayaran.id_pendaftaran', '=', 'pendaftaran.id')
            ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
            ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
            ->select(
                'pembayaran.*',
                'pendaftaran.nama_lengkap as peserta_nama',
                'pengguna.nama as user_nama',
                'lomba.nama as event_nama',
                'lomba.tanggal as event_date'
            );

        // Pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('pembayaran.kode_pembayaran', 'like', '%' . $request->search . '%')
                  ->orWhere('pendaftaran.nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('lomba.nama', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Status
        if ($request->status) {
            $query->where('pembayaran.status', $request->status);
        }

        $payments = $query->orderBy('pembayaran.created_at', 'desc')->paginate(10);

        // Statistik untuk Box di atas
        $stats = [
            'total' => DB::table('pembayaran')->count(),
            'pending' => DB::table('pembayaran')->where('status', 'menunggu')->count(),
            'verified' => DB::table('pembayaran')->where('status', 'terverifikasi')->count(),
            'rejected' => DB::table('pembayaran')->where('status', 'ditolak')->count(),
            'total_amount' => DB::table('pembayaran')->where('status', 'terverifikasi')->sum('jumlah'),
        ];

        return view('staff.payments.index', compact('payments', 'stats'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil data pembayaran
            $payment = DB::table('pembayaran')->where('id', $id)->first();

            if (!$payment) {
                return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
            }

            // 2. Ambil data pendaftaran
            $pendaftaran = DB::table('pendaftaran')
                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                ->where('pendaftaran.id', $payment->id_pendaftaran)
                ->select('pendaftaran.*', 'lomba.nama as event_nama')
                ->first();

            if (!$pendaftaran) {
                return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
            }

            // 3. Update status pembayaran
            DB::table('pembayaran')->where('id', $id)->update([
                'status' => 'terverifikasi',
                'catatan' => $request->catatan,
                'updated_at' => now()
            ]);

            // 4. Update status pendaftaran menjadi 'disetujui'
            DB::table('pendaftaran')
                ->where('id', $payment->id_pendaftaran)
                ->update([
                    'status_pendaftaran' => 'disetujui',
                    'updated_at' => now()
                ]);

            // 5. CEK METODE PEMBAYARAN
            $metode = strtolower($payment->metode_pembayaran ?? '');
            $isCash = in_array($metode, ['cash', 'onsite', 'cash_onsite']);

            // 6. BUAT NOTIFIKASI
            if ($isCash) {
                // UNTUK CASH ONSITE: Notifikasi ke ADMIN
                // Ambil semua admin & superadmin
                $admins = DB::table('pengguna')
                    ->whereIn('peran', ['admin', 'superadmin'])
                    ->where('is_active', 1)
                    ->get();

                foreach ($admins as $admin) {
                    DB::table('notifikasi')->insert([
                        'user_id' => $admin->id,
                        'pendaftaran_id' => $payment->id_pendaftaran,
                        'jenis' => 'pembayaran_terverifikasi',
                        'judul' => '💰 Pembayaran Cash Diverifikasi',
                        'pesan' => 'Pembayaran cash untuk event "' . $pendaftaran->event_nama . '" telah diverifikasi oleh staff.',
                        'tautan' => '/admin/payments/' . $id,
                        'dibaca' => 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                $logMessage = 'Pembayaran cash #' . $payment->kode_pembayaran . ' diverifikasi (notifikasi ke admin)';
            } else {
                // UNTUK TRANSFER ONLINE: Notifikasi ke PESERTA
                DB::table('notifikasi')->insert([
                    'user_id' => $pendaftaran->id_pengguna,
                    'pendaftaran_id' => $payment->id_pendaftaran,
                    'jenis' => 'pembayaran_terverifikasi',
                    'judul' => '✅ Pembayaran Terverifikasi',
                    'pesan' => 'Pembayaran Anda untuk event "' . $pendaftaran->event_nama . '" telah diverifikasi. Selamat berlari! 🏃‍♂️',
                    'tautan' => '/profile',
                    'dibaca' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $logMessage = 'Pembayaran transfer #' . $payment->kode_pembayaran . ' diverifikasi (notifikasi ke peserta)';
            }

            DB::commit();
            
            return redirect()->back()->with('success', '✅ Pembayaran #' . $payment->kode_pembayaran . ' berhasil diverifikasi! ' . $logMessage);
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil data pembayaran
            $payment = DB::table('pembayaran')->where('id', $id)->first();

            if (!$payment) {
                return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
            }

            // 2. Ambil data pendaftaran
            $pendaftaran = DB::table('pendaftaran')
                ->join('lomba', 'pendaftaran.id_lomba', '=', 'lomba.id')
                ->where('pendaftaran.id', $payment->id_pendaftaran)
                ->select('pendaftaran.*', 'lomba.nama as event_nama')
                ->first();

            // 3. Update status pembayaran
            DB::table('pembayaran')->where('id', $id)->update([
                'status' => 'ditolak',
                'catatan' => $request->catatan,
                'updated_at' => now()
            ]);

            // 4. Notifikasi ke PESERTA (untuk penolakan, selalu ke peserta)
            DB::table('notifikasi')->insert([
                'user_id' => $pendaftaran->id_pengguna,
                'pendaftaran_id' => $payment->id_pendaftaran,
                'jenis' => 'pembayaran_baru',
                'judul' => '❌ Pembayaran Ditolak',
                'pesan' => 'Pembayaran Anda untuk event "' . $pendaftaran->event_nama . '" ditolak. Alasan: ' . $request->catatan . '. Silakan upload ulang bukti pembayaran.',
                'tautan' => '/profile',
                'dibaca' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->back()->with('info', 'Pembayaran #' . $payment->kode_pembayaran . ' telah ditolak. Notifikasi telah dikirim ke peserta.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}