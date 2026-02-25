@extends('layouts.staff')

@section('title', 'Kelola Pendaftaran')
@section('page-title', 'Kelola Pendaftaran')

@section('content')
<div class="space-y-6 animate-fade-in">

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-blue-500 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pendaftaran</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h3>
                    <div class="flex items-center mt-2 text-emerald-500 text-xs font-bold">
                        <i class="fas fa-arrow-up mr-1"></i> {{ ceil($stats['total']/max($stats['total'], 1)*100) }}%
                    </div>
                </div>
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-orange-500 transition-all relative overflow-hidden">
            @if($stats['pending'] > 0)
            <div class="absolute top-0 right-0 h-1 w-full bg-orange-500 animate-pulse"></div>
            @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menunggu Verif</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['pending'] }}</h3>
                    <div class="flex items-center mt-2 text-orange-500 text-xs font-bold">
                        <i class="fas fa-clock mr-1 animate-spin-slow"></i> Butuh Perhatian
                    </div>
                </div>
                <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-emerald-500 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Disetujui</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['approved'] }}</h3>
                    <div class="flex items-center mt-2 text-emerald-500 text-xs font-bold">
                        <i class="fas fa-check-circle mr-1"></i> {{ ceil($stats['approved']/max($stats['total'], 1)*100) }}%
                    </div>
                </div>
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-rose-500 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ditolak</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['rejected'] }}</h3>
                    <div class="flex items-center mt-2 text-rose-500 text-xs font-bold">
                        <i class="fas fa-times-circle mr-1"></i> {{ ceil($stats['rejected']/max($stats['total'], 1)*100) }}%
                    </div>
                </div>
                <div class="p-4 bg-rose-50 text-rose-600 rounded-2xl group-hover:bg-rose-600 group-hover:text-white transition-colors">
                    <i class="fas fa-user-times text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden transition-all duration-500">
        <button onclick="toggleFilter()" class="w-full flex items-center justify-between p-6 hover:bg-slate-50 transition-colors border-b border-slate-100">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg mr-3">
                    <i class="fas fa-filter"></i>
                </div>
                <div class="text-left">
                    <h4 class="text-sm font-bold text-slate-800 tracking-tight">Filter Pendaftaran</h4>
                    <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Sesuaikan tampilan data yang kamu butuhkan</p>
                </div>
            </div>
            <i id="filterChevron" class="fas fa-chevron-up text-slate-300 transition-transform duration-300"></i>
        </button>

        <div id="filterContent" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4 space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Berdasarkan Event</label>
                    <select id="filterEvent" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3 space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Status Verif</label>
                    <select id="filterStatus" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="md:col-span-3 space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Metode Bayar</label>
                    <select id="filterPayment" class="w-full bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">Semua Metode</option>
                        <option value="transfer">Transfer Bank (Auto)</option>
                        <option value="onsite">On Site (Manual)</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button id="filterBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-blue-200 transition-all flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h4 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Pendaftaran</h4>
                <p class="text-xs text-slate-400 font-medium">Verifikasi peserta marathon dengan sistem pemantauan bukti transfer</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($registrations->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">#</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Peserta</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nomor Start</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detail Event</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Status / Metode</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($registrations as $registration)
                    <tr class="hover:bg-slate-50/50 transition-all group">
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs font-black text-slate-300">
                                {{ $loop->iteration + ($registrations->currentPage() - 1) * $registrations->perPage() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-tr from-blue-600 to-blue-400 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-blue-100">
                                    {{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $registration->nama_lengkap }}</p>
                                    <p class="text-[10px] text-blue-500 font-bold tracking-widest">{{ $registration->kode_pendaftaran }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($registration->nomor_start)
                                <span class="px-3 py-1 bg-slate-800 text-white text-[10px] font-black rounded-lg tracking-widest">
                                    {{ $registration->nomor_start }}
                                </span>
                            @else
                                <span class="text-[10px] text-slate-400 italic">Belum Terbit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[11px] font-bold text-slate-700 leading-tight truncate w-40">{{ $registration->event_nama }}</p>
                            <span class="text-[9px] font-bold py-0.5 px-2 bg-slate-100 text-slate-500 rounded uppercase">{{ $registration->package_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($registration->metode_pembayaran == 'transfer')
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <i class="fas fa-robot mr-1"></i> System Approved
                                    </span>
                                    <a href="{{ asset('storage/' . $registration->bukti_pembayaran) }}" target="_blank" class="text-[8px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100 hover:bg-orange-100">
                                        <i class="fas fa-search mr-1"></i> CEK FOTO BUKTI
                                    </a>
                                </div>
                            @else
                                @if($registration->status_pendaftaran == 'menunggu')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-orange-50 text-orange-600 border border-orange-100 animate-pulse">
                                        <i class="fas fa-hand-holding-usd mr-1"></i> Bayar Onsite
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                        <i class="fas fa-check-circle mr-1"></i> {{ $registration->status_pendaftaran }}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center space-x-1">
                                <a href="{{ route('staff.registrations.view', $registration->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                @if($registration->status_pendaftaran !== 'ditolak')
                                    @if($registration->status_pendaftaran == 'menunggu')
                                        <form action="{{ route('staff.registrations.approve', $registration->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Setujui pendaftaran onsite ini?')" class="p-2 text-emerald-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                                                <i class="fas fa-check-circle text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <button onclick="openRejectModal('{{ $registration->id }}', '{{ $registration->nama_lengkap }}')" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Batalkan / Reject">
                                        <i class="fas fa-times-circle text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="py-20 text-center">
                <div class="h-24 w-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-slash text-4xl"></i>
                </div>
                <h5 class="text-slate-600 font-bold">Belum Ada Data Pendaftaran</h5>
            </div>
            @endif
        </div>

        @if($registrations->count() > 0)
        <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter">
                Halaman {{ $registrations->currentPage() }} dari {{ $registrations->lastPage() }}
            </p>
            <div class="tailwind-pagination">
                {{ $registrations->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<div id="tailwindRejectModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl transform transition-all animate-fade-in-up">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100">
                <h5 class="text-lg font-extrabold text-slate-800">Tolak / Batalkan</h5>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center p-4 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100">
                    <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                    <p class="text-xs font-bold tracking-tight">Membatalkan pendaftaran <span id="rejectTargetName" class="underline"></span> akan mencabut akses nomor start mereka.</p>
                </div>
                <div class="space-y-2 text-left">
                    <label class="text-[11px] font-bold text-slate-500 uppercase ml-1">Alasan Penolakan (User akan melihat ini)</label>
                    <textarea name="alasan" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-2xl text-sm p-4" placeholder="Contoh: Bukti transfer setelah dicek ternyata palsu..." required></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 text-slate-500 text-xs font-bold py-3 rounded-2xl border border-slate-200">BATAL</button>
                <button type="submit" class="flex-1 bg-rose-500 text-white text-xs font-bold py-3 rounded-2xl shadow-lg shadow-rose-200">KONFIRMASI REJECT</button>
            </div>
        </form>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    .animate-spin-slow { animation: spin 3s linear infinite; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .tailwind-pagination nav div:first-child { display: none; }
</style>

<script>
    function toggleFilter() {
        const content = document.getElementById('filterContent');
        const chevron = document.getElementById('filterChevron');
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    function openRejectModal(id, name) {
        const modal = document.getElementById('tailwindRejectModal');
        const targetName = document.getElementById('rejectTargetName');
        const form = document.getElementById('rejectForm');
        targetName.innerText = name;
        form.action = `/staff/registrations/reject/${id}`;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('tailwindRejectModal').classList.add('hidden');
    }

    document.getElementById('filterBtn').addEventListener('click', function() {
        const eventId = document.getElementById('filterEvent').value;
        const status = document.getElementById('filterStatus').value;
        const payment = document.getElementById('filterPayment').value;
        
        let url = new URL(window.location.href);
        if (eventId) url.searchParams.set('event_id', eventId); else url.searchParams.delete('event_id');
        if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
        if (payment) url.searchParams.set('payment', payment); else url.searchParams.delete('payment');
        
        window.location.href = url.toString();
    });
</script>
@endsection