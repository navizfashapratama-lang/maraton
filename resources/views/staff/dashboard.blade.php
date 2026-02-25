@extends('layouts.staff')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="relative overflow-hidden bg-white border border-slate-200 rounded-[2rem] p-6 lg:p-10 shadow-sm">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-blue-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center space-x-6">
                <div class="hidden sm:block">
                    @if(Auth::user()->foto_profil ?? '' )
                        <div class="relative group">
                            <img src="{{ Storage::url(Auth::user()->foto_profil) }}" 
                                 class="h-24 w-24 rounded-3xl object-cover shadow-2xl shadow-blue-100 border-4 border-white transform transition-transform group-hover:scale-105 duration-300">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full shadow-sm"></div>
                        </div>
                    @else
                        <div class="h-24 w-24 flex items-center justify-center rounded-3xl bg-gradient-to-br from-blue-600 to-blue-800 shadow-xl shadow-blue-200 text-white border-4 border-white transform transition-transform hover:rotate-3 duration-300">
                            <span class="text-3xl font-black">{{ strtoupper(substr(session('user_nama'), 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    @php $userName = session('user_nama') ?? 'Staff'; @endphp
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Selamat Datang, {{ $userName }}!</h2>
                    <div class="flex items-center mt-1 text-slate-500 font-bold text-sm uppercase tracking-wider">
                        <i class="far fa-calendar-check mr-2 text-blue-500 text-base"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                    
                    <div class="mt-5 flex items-center p-2 px-4 bg-orange-50 text-orange-700 rounded-2xl text-xs inline-flex border border-orange-100 font-black tracking-tight uppercase">
                        <span class="relative flex h-2 w-2 mr-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                        </span>
                        Ada <span class="mx-1 text-orange-900 underline">{{ $pending_registrations }}</span> pendaftaran menunggu verifikasi
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 lg:w-1/3">
                <div class="bg-slate-50 border border-slate-100 p-5 rounded-[1.5rem] text-center transition-all hover:bg-white hover:shadow-md group">
                    <p class="text-2xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $today_registrations ?? 0 }}</p>
                    <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 mt-1">Hari Ini</p>
                </div>
                <div class="bg-slate-50 border border-slate-100 p-5 rounded-[1.5rem] text-center transition-all hover:bg-white hover:shadow-md group">
                    <p class="text-2xl font-black text-slate-800 group-hover:text-blue-600 transition-colors">{{ $active_events ?? 0 }}</p>
                    <p class="text-[10px] uppercase tracking-[0.2em] font-black text-slate-400 mt-1">Event Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="group bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:border-blue-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Event</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $total_events }}</h3>
                </div>
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full transition-all duration-1000" style="width: {{ $total_events > 0 ? ($active_events/$total_events)*100 : 0 }}%"></div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:border-emerald-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Peserta</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $total_registrations }}</h3>
                </div>
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full" style="width: 70%"></div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:border-orange-500 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menunggu</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $pending_registrations }}</h3>
                </div>
                <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-orange-500 h-full" style="width: 45%"></div>
            </div>
        </div>

        <div class="group bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm hover:border-indigo-600 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendapatan</p>
                    <h3 class="text-lg font-black text-slate-800 mt-1 tracking-tight">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                </div>
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-indigo-600 h-full" style="width: 90%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight">Event Mendatang</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Jadwal pelaksanaan marathon terbaru</p>
                </div>
                <a href="{{ route('staff.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black py-2.5 px-6 rounded-xl transition-all shadow-lg shadow-blue-100 uppercase tracking-widest">
                    <i class="fas fa-plus mr-1"></i> Baru
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Event & Kategori</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Waktu</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($upcoming_events as $event)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mr-4 group-hover:rotate-6 transition-transform">
                                        <i class="fas fa-running text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 leading-none">{{ $event->nama }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1.5 tracking-tighter">{{ $event->kategori }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-xs font-black text-slate-700 leading-none">{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d M Y') }}</p>
                                <p class="text-[9px] text-blue-500 font-bold uppercase mt-1 tracking-tighter">{{ \Carbon\Carbon::parse($event->tanggal)->diffForHumans() }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                    {{ $event->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-1">
                                    <a href="{{ route('staff.events.edit', $event->id) }}" class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <a href="{{ route('staff.events.show', $event->id) }}" class="p-2 text-slate-300 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all" title="Lihat">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden flex flex-col h-full">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pendaftaran Terbaru</h4>
                    <span class="text-[9px] font-black px-2 py-1 bg-blue-600 text-white rounded-lg">{{ $recent_registrations->count() }} DATA</span>
                </div>
                <div class="flex-1 p-3 space-y-2 max-h-[400px] overflow-y-auto custom-scrollbar">
                    @forelse($recent_registrations as $reg)
                    <div class="p-4 hover:bg-slate-50 rounded-[1.5rem] transition-all border border-transparent hover:border-slate-100 group">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-tr from-slate-100 to-slate-200 text-slate-600 rounded-xl flex items-center justify-center font-black text-xs group-hover:scale-95 transition-transform uppercase">
                                {{ substr($reg->nama_lengkap, 0, 1) }}
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between items-start">
                                    <p class="text-xs font-black text-slate-800 tracking-tight leading-none truncate w-24">{{ $reg->nama_lengkap }}</p>
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">{{ \Carbon\Carbon::parse($reg->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-[9px] text-blue-500 font-bold mt-1.5 uppercase tracking-tighter">{{ Str::limit($reg->event_nama, 20) }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <div class="h-12 w-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-slate-200">
                            <i class="fas fa-user-clock text-xl"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada data</p>
                    </div>
                    @endforelse
                </div>
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    <a href="{{ route('staff.registrations.index') }}" class="block text-center text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-[0.2em] transition-all">
                        LIHAT SEMUA DATA <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
                               
<style>
    /* Animasi Entry */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Scrollbar Styling kustom agar lebih tipis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
@endsection