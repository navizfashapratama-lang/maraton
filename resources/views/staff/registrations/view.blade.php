@extends('layouts.staff')

@section('title', 'Detail Pendaftaran')
@section('page-title', 'Detail Pendaftaran')

@section('content')
<div class="space-y-6 animate-fade-in pb-10">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                <i class="fas fa-id-card"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $registration->kode_pendaftaran }}</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Daftar pada: {{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y H:i') }}</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('staff.registrations.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-100 transition-all flex items-center">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
            @if($registration->status_pendaftaran == 'menunggu')
                <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="px-4 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:bg-emerald-600 shadow-lg shadow-emerald-100 transition-all flex items-center">
                    <i class="fas fa-check mr-2"></i> Setujui
                </button>
                <button onclick="openRejectModal('{{ $registration->id }}', '{{ $registration->nama_lengkap }}')" class="px-4 py-2 bg-rose-500 text-white rounded-xl text-xs font-bold hover:bg-rose-600 shadow-lg shadow-rose-100 transition-all flex items-center">
                    <i class="fas fa-times mr-2"></i> Tolak
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $statusColor = [
                        'menunggu' => 'orange',
                        'disetujui' => 'emerald',
                        'ditolak' => 'rose',
                        'dibatalkan' => 'slate'
                    ];
                    $color = $statusColor[$registration->status_pendaftaran] ?? 'indigo';
                @endphp
                <div class="bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 p-4 rounded-2xl">
                    <div class="flex items-center">
                        <div class="p-2 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-lg mr-3">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-{{ $color }}-400 uppercase tracking-widest leading-none">Status Pendaftaran</p>
                            <p class="text-sm font-bold text-{{ $color }}-700 uppercase">{{ $registration->status_pendaftaran }}</p>
                        </div>
                    </div>
                </div>

                @if($payment)
                @php $payColor = $payment->status == 'terverifikasi' ? 'emerald' : ($payment->status == 'menunggu' ? 'orange' : 'rose'); @endphp
                <div class="bg-{{ $payColor }}-50 border-l-4 border-{{ $payColor }}-500 p-4 rounded-2xl">
                    <div class="flex items-center">
                        <div class="p-2 bg-{{ $payColor }}-100 text-{{ $payColor }}-600 rounded-lg mr-3">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-{{ $payColor }}-400 uppercase tracking-widest leading-none">Status Pembayaran</p>
                            <p class="text-sm font-bold text-{{ $payColor }}-700 uppercase">{{ $payment->status == 'terverifikasi' ? 'Lunas' : $payment->status }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h6 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                        <i class="fas fa-user text-indigo-500 mr-2"></i> Informasi Personal
                    </h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                                <p class="text-sm font-bold text-slate-700">{{ $registration->nama_lengkap }}</p>
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Peserta</label>
                                <p class="text-sm font-bold text-slate-700">{{ $registration->email }}</p>
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon / WA</label>
                                <p class="text-sm font-bold text-slate-700">{{ $registration->telepon }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</label>
                                <p class="text-sm font-bold text-slate-700">{{ $registration->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Lahir</label>
                                <p class="text-sm font-bold text-slate-700">{{ \Carbon\Carbon::parse($registration->tanggal_lahir)->format('d M Y') }}</p>
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Domisili</label>
                                <p class="text-sm font-bold text-slate-700">{{ $registration->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h6 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                        <i class="fas fa-running text-indigo-500 mr-2"></i> Detail Lomba & Paket
                    </h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                            <label class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest block mb-1">Event Dipilih</label>
                            <p class="text-base font-black text-indigo-900 leading-tight">{{ $registration->event_nama }}</p>
                            <div class="flex items-center text-xs text-indigo-600 font-bold mt-2">
                                <i class="fas fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::parse($registration->event_date)->format('d F Y') }}
                            </div>
                            <div class="flex items-center text-xs text-indigo-600 font-bold mt-1">
                                <i class="fas fa-map-marker-alt mr-2"></i> {{ $registration->event_lokasi }}
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-end border-b border-slate-100 pb-2">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Paket Layanan</label>
                                    <p class="text-sm font-black text-slate-800">{{ $registration->package_name }}</p>
                                </div>
                                <p class="text-lg font-black text-indigo-600">Rp {{ number_format($registration->package_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                @php $facilities = ['Race Kit' => 'termasuk_race_kit', 'Medali' => 'termasuk_medali', 'Kaos' => 'termasuk_kaos', 'Sertifikat' => 'termasuk_sertifikat', 'Snack' => 'termasuk_snack']; @endphp
                                @foreach($facilities as $label => $key)
                                <div class="flex items-center text-[10px] font-bold {{ $registration->$key ? 'text-emerald-500' : 'text-slate-300 line-through' }}">
                                    <i class="fas fa-{{ $registration->$key ? 'check-circle' : 'times-circle' }} mr-2"></i> {{ $label }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-slate-800 rounded-3xl p-6 text-center shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 text-6xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-bolt"></i>
                </div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Nomor Start (BIB)</label>
                @if($registration->nomor_start)
                    <h2 class="text-5xl font-black text-white tracking-tighter">{{ $registration->nomor_start }}</h2>
                @else
                    <div class="py-4 px-2 border-2 border-dashed border-slate-600 rounded-2xl">
                        <p class="text-xs text-slate-500 font-bold italic">Belum Diterbitkan</p>
                    </div>
                @endif
            </div>

            @if($payment)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h6 class="text-xs font-black text-slate-800 uppercase tracking-widest">Bukti Pembayaran</h6>
                    <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-600 rounded-lg">{{ $payment->kode_pembayaran }}</span>
                </div>
                <div class="p-6">
                    @if($payment->bukti_pembayaran)
                        <div class="relative group cursor-pointer" onclick="window.open('{{ asset('storage/' . $payment->bukti_pembayaran) }}', '_blank')">
                            <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" alt="Bukti" class="w-full rounded-2xl shadow-sm border border-slate-100 group-hover:opacity-90 transition-opacity">
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="bg-white/90 px-4 py-2 rounded-xl text-xs font-bold text-slate-800 shadow-xl border border-slate-100">Klik untuk perbesar</span>
                            </div>
                        </div>
                    @else
                        <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <i class="fas fa-file-invoice-dollar text-3xl text-slate-200 mb-2"></i>
                            <p class="text-xs text-slate-400 font-bold uppercase">Bukti Belum Diunggah</p>
                        </div>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t border-slate-50 space-y-2">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-400">Metode:</span>
                            <span class="text-slate-700">{{ ucfirst($payment->metode_pembayaran) }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-400">Jumlah:</span>
                            <span class="text-indigo-600">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-indigo-600 rounded-3xl p-6 shadow-lg shadow-indigo-100">
                <h6 class="text-white text-sm font-black uppercase tracking-widest mb-4">Hubungi Peserta</h6>
                <div class="grid grid-cols-2 gap-3">
                    <a href="https://wa.me/{{ $registration->telepon }}" target="_blank" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all border border-white/10 group">
                        <i class="fab fa-whatsapp text-xl mb-2"></i>
                        <span class="text-[10px] font-bold uppercase">WhatsApp</span>
                    </a>
                    <a href="mailto:{{ $registration->email }}" class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 text-white rounded-2xl transition-all border border-white/10">
                        <i class="fas fa-envelope text-xl mb-2"></i>
                        <span class="text-[10px] font-bold uppercase">Email</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="approveModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl animate-fade-in-up">
        <form action="{{ route('staff.registrations.approve', $registration->id) }}" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h5 class="text-lg font-black text-slate-800 tracking-tight">Setujui Pendaftaran</h5>
                <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="text-slate-300 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-4 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-sm font-bold">
                    Konfirmasi pendaftaran untuk <span class="underline">{{ $registration->nama_lengkap }}</span>.
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Start (BIB) Custom</label>
                    <input type="text" name="nomor_start" value="{{ $registration->nomor_start }}" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Kosongkan untuk otomatis">
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-b-3xl flex gap-3">
                <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="flex-1 px-4 py-3 bg-white text-slate-500 rounded-2xl font-bold border border-slate-200 text-xs">BATAL</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-emerald-500 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 text-xs uppercase tracking-widest">SETUJUI SEKARANG</button>
            </div>
        </form>
    </div>
</div>

<div id="tailwindRejectModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl animate-fade-in-up">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h5 class="text-lg font-black text-slate-800 tracking-tight">Tolak Pendaftaran</h5>
                <button type="button" onclick="closeRejectModal()" class="text-slate-300 hover:text-slate-600"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-4 bg-rose-50 text-rose-700 rounded-2xl border border-rose-100 text-sm font-bold">
                    Anda akan menolak pendaftaran <span id="rejectTargetName" class="underline"></span>.
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1 text-left block">Alasan Penolakan</label>
                    <textarea name="alasan" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-rose-500 focus:border-rose-500 transition-all" placeholder="Berikan alasan yang jelas kepada peserta..." required></textarea>
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-b-3xl flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-3 bg-white text-slate-500 rounded-2xl font-bold border border-slate-200 text-xs">BATAL</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-100 text-xs uppercase tracking-widest">KONFIRMASI TOLAK</button>
            </div>
        </form>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    @media print {
        .btn, .modal, nav, aside { display: none !important; }
        .bg-white { border: none !important; box-shadow: none !important; }
        .xl\:grid-cols-3 { display: block !important; }
        .card { break-inside: avoid; margin-bottom: 20px; }
    }
</style>

<script>
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
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Teks disalin: ' + text);
        });
    }
</script>
@endsection