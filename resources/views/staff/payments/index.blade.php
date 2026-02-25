@extends('layouts.staff')

@section('title', 'Kelola Pembayaran')
@section('page-title', 'Kelola Pembayaran')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Kelola Pembayaran</h2>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-tighter">Verifikasi arus kas dan transaksi peserta</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative group">
                <button class="bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold py-2.5 px-5 rounded-2xl border border-slate-200 shadow-sm transition-all flex items-center">
                    <i class="fas fa-download mr-2 text-blue-500"></i> Export Data <i class="fas fa-chevron-down ml-2 opacity-30"></i>
                </button>
                <div class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-2xl shadow-xl border border-slate-100 hidden group-hover:block z-30 animate-fade-in-down">
                    <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-file-excel mr-2 text-emerald-500"></i> Simpan ke Excel</a>
                    <a href="#" class="block px-4 py-2 text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600"><i class="fas fa-file-pdf mr-2 text-rose-500"></i> Simpan ke PDF</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h3>
            </div>
            <i class="fas fa-wallet absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-blue-50 transition-colors"></i>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menunggu Verif</p>
                <h3 class="text-3xl font-black text-orange-500 mt-1">{{ $stats['pending'] }}</h3>
            </div>
            <i class="fas fa-clock absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-orange-50 transition-colors"></i>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terverifikasi</p>
                <h3 class="text-3xl font-black text-emerald-500 mt-1">{{ $stats['verified'] }}</h3>
            </div>
            <i class="fas fa-check-circle absolute -right-4 -bottom-4 text-7xl text-slate-50 group-hover:text-emerald-50 transition-colors"></i>
        </div>

        <div class="bg-blue-600 p-6 rounded-3xl shadow-xl shadow-blue-100 relative overflow-hidden group">
            <div class="relative z-10 text-white">
                <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest">Total Pendapatan</p>
                <h3 class="text-xl font-black mt-1">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-hand-holding-usd absolute -right-4 -bottom-4 text-7xl text-white/10"></i>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <form action="{{ route('staff.payments.index') }}" method="GET" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm focus:ring-blue-500 focus:border-blue-500 transition-all"
                           placeholder="Cari kode, nama peserta, atau event...">
                </div>
                <div class="md:col-span-3">
                    <select name="status" class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 text-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-2xl shadow-lg shadow-blue-200 transition-all uppercase text-xs tracking-widest">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kode & Peserta</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Event</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Metode</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Jumlah</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center font-bold text-xs mr-3">
                                    {{ $loop->iteration + (($payments->currentPage() - 1) * $payments->perPage()) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-blue-600 leading-none">{{ $payment->kode_pembayaran }}</p>
                                    <p class="text-xs font-bold text-slate-700 mt-1">{{ $payment->peserta_nama }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $payment->user_nama }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-700">{{ $payment->event_nama }}</p>
                            <p class="text-[10px] text-slate-400">{{ date('d M Y', strtotime($payment->event_date)) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 bg-slate-100 text-slate-500 text-[10px] font-bold rounded-lg uppercase tracking-widest border border-slate-200">
                                {{ $payment->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-black text-emerald-600">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-tighter">{{ date('d/m/y H:i', strtotime($payment->created_at)) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusStyles = [
                                    'menunggu' => 'bg-orange-50 text-orange-600 border-orange-100 animate-pulse',
                                    'terverifikasi' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'kadaluarsa' => 'bg-slate-100 text-slate-400 border-slate-200'
                                ];
                                $currentStyle = $statusStyles[$payment->status] ?? 'bg-slate-100';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $currentStyle }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="{{ route('staff.payments.view', $payment->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($payment->status == 'menunggu')
                                    <button onclick="openVerifyModal('{{ $payment->id }}', '{{ $payment->kode_pembayaran }}', '{{ $payment->jumlah }}')" 
                                            class="p-2 text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all" title="Verifikasi">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <button onclick="openRejectModal('{{ $payment->id }}', '{{ $payment->kode_pembayaran }}')" 
                                            class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Tolak">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-20 text-center">
                            <i class="fas fa-credit-card text-slate-100 text-6xl mb-4"></i>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Tidak ada data transaksi ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">
                Halaman {{ $payments->currentPage() }} dari {{ $payments->lastPage() }} (Total {{ $payments->total() }} Data)
            </p>
            <div class="tailwind-pagination">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>

<div id="verifyModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl animate-fade-in-up">
        <form id="verifyForm" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h5 class="text-lg font-black text-slate-800 tracking-tight">Verifikasi Pembayaran</h5>
                <button type="button" onclick="closeModal('verifyModal')" class="text-slate-300 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest">Kode Pembayaran</p>
                    <p id="v-kode" class="text-sm font-black text-blue-700"></p>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mt-3">Jumlah Transfer</p>
                    <p id="v-jumlah" class="text-sm font-black text-blue-700"></p>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Catatan Verifikasi</label>
                    <textarea name="catatan" rows="2" class="w-full bg-slate-50 border-slate-200 rounded-2xl text-sm p-4 focus:ring-blue-500" placeholder="Opsional..."></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeModal('verifyModal')" class="flex-1 py-3 text-xs font-bold text-slate-400 hover:text-slate-600 transition-all uppercase tracking-widest">Batal</button>
                <button type="submit" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-black py-3 rounded-2xl shadow-lg shadow-emerald-200 transition-all uppercase text-[10px] tracking-widest">Verifikasi Sekarang</button>
            </div>
        </form>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl animate-fade-in-up">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h5 class="text-lg font-black text-slate-800 tracking-tight text-rose-600">Tolak Pembayaran</h5>
                <button type="button" onclick="closeModal('rejectModal')" class="text-slate-300 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-xs text-slate-500 leading-relaxed font-medium">Anda akan menolak pembayaran <span id="r-kode" class="font-bold text-slate-800"></span>. Pastikan alasan penolakan jelas agar peserta dapat memperbaiki.</p>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Alasan Penolakan <span class="text-rose-500">*</span></label>
                    <textarea name="catatan" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-2xl text-sm p-4 focus:ring-rose-500 border-2" placeholder="Contoh: Bukti transfer tidak jelas atau nominal tidak sesuai..." required></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeModal('rejectModal')" class="flex-1 py-3 text-xs font-bold text-slate-400 transition-all uppercase tracking-widest">Kembali</button>
                <button type="submit" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-black py-3 rounded-2xl shadow-lg shadow-rose-200 transition-all uppercase text-[10px] tracking-widest">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyModal(id, kode, jumlah) {
        document.getElementById('v-kode').innerText = kode;
        document.getElementById('v-jumlah').innerText = 'Rp ' + parseInt(jumlah).toLocaleString('id-ID');
        document.getElementById('verifyForm').action = `/staff/payments/verify/${id}`;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function openRejectModal(id, kode) {
        document.getElementById('r-kode').innerText = kode;
        document.getElementById('rejectForm').action = `/staff/payments/reject/${id}`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    // Auto-refresh logic (opsional)
    @if($stats['pending'] > 0)
    setTimeout(() => { window.location.reload(); }, 60000);
    @endif
</script>

<style>
    .tailwind-pagination nav div:first-child { display: none; }
    .tailwind-pagination nav span, .tailwind-pagination nav a { 
        padding: 8px 16px; border-radius: 12px; margin: 0 2px; font-size: 11px; font-weight: 800; transition: all 0.3s;
    }
    .tailwind-pagination nav a { background: white; border: 1px solid #e2e8f0; color: #64748b; }
    .tailwind-pagination nav span[aria-current="page"] span { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
</style>
@endsection