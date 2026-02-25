@extends('layouts.staff')

@section('title', 'Kelola Paket per Event')
@section('page-title', 'Daftar Event & Kelola Paket')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Pilih Event untuk Kelola Paket</h2>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mt-1">Staff dapat menambah atau mengubah paket di dalam event yang telah dibuat Admin</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('staff.packages.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-black rounded-2xl shadow-lg shadow-blue-100 transition-all uppercase tracking-widest">
                <i class="fas fa-plus-circle mr-2 text-sm"></i> Tambah Paket Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:border-blue-500 transition-all duration-300">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Event</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $events->total() }}</h3>
                </div>
                <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm group hover:border-emerald-500 transition-all duration-300">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Mendatang</p>
                    <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ DB::table('lomba')->where('status', 'mendatang')->count() }}</h3>
                </div>
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl shadow-slate-200 relative overflow-hidden group col-md-6 lg:col-span-2 text-white">
            <i class="fas fa-box absolute -right-4 -bottom-4 text-8xl text-white/5 group-hover:scale-110 transition-transform"></i>
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em]">Total Paket Terdaftar</p>
                    <h3 class="text-3xl font-black mt-1">{{ DB::table('paket_lomba')->count() }} <span class="text-sm font-bold text-blue-400 ml-2">Paket Siap Jual</span></h3>
                </div>
                <div class="hidden sm:block text-right">
                    <p class="text-[10px] font-bold text-white/40 uppercase tracking-tighter italic">"Update paket secara berkala demi kenyamanan peserta"</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('staff.events.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1 group">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full bg-white border-slate-200 rounded-2xl py-3.5 pl-12 pr-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                           placeholder="Cari event untuk dikelola paketnya...">
                </div>
                <div class="md:w-48">
                    <select name="status" class="w-full bg-white border-slate-200 rounded-2xl py-3.5 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">Semua Status</option>
                        <option value="mendatang" {{ request('status') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <button type="submit" class="px-8 bg-slate-800 hover:bg-black text-white text-[10px] font-black rounded-2xl transition-all uppercase tracking-widest shadow-lg shadow-slate-200">
                    Filter Data
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">#</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Event</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jml Paket</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu & Lokasi</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Kelola</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($events as $event)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-6 text-center">
                            <span class="text-xs font-black text-slate-300 group-hover:text-blue-500 transition-colors">
                                {{ $loop->iteration + (($events->currentPage() - 1) * $events->perPage()) }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center">
                                <div class="h-12 w-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-blue-100 mr-4 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-running"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 leading-none">{{ $event->nama }}</p>
                                    <p class="text-[10px] text-blue-500 font-bold uppercase mt-2 tracking-tighter">{{ $event->kategori }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $packageCount = DB::table('paket_lomba')->where('lomba_id', $event->id)->count();
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 uppercase tracking-tighter">
                                {{ $packageCount }} Paket
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center space-x-2">
                                <i class="far fa-calendar-alt text-slate-300 text-xs"></i>
                                <span class="text-xs font-bold text-slate-700">{{ date('d M Y', strtotime($event->tanggal)) }}</span>
                            </div>
                            <div class="flex items-center space-x-2 mt-1.5">
                                <i class="fas fa-map-marker-alt text-rose-400 text-[10px]"></i>
                                <span class="text-[10px] font-bold text-slate-400 uppercase truncate w-32">{{ $event->lokasi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $statusStyle = [
                                    'mendatang' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'selesai' => 'bg-slate-100 text-slate-500 border-slate-200',
                                    'dibatalkan' => 'bg-rose-50 text-rose-600 border-rose-100',
                                ][$event->status] ?? 'bg-slate-50';
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.1em] border {{ $statusStyle }}">
                                {{ $event->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('staff.packages.index', ['event_id' => $event->id]) }}" 
                                   class="px-4 py-2 bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 text-[9px] font-black rounded-xl transition-all uppercase tracking-widest flex items-center">
                                    <i class="fas fa-boxes mr-2"></i> Paket
                                </a>
                                <a href="{{ route('staff.packages.create', ['event_id' => $event->id]) }}" 
                                   class="p-2.5 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all" title="Tambah Paket Baru">
                                    <i class="fas fa-plus-square text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-24 text-center">
                            <div class="h-20 w-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-times text-3xl"></i>
                            </div>
                            <h5 class="text-slate-700 font-black tracking-tight uppercase text-sm">Event Belum Tersedia</h5>
                            <p class="text-[10px] text-slate-400 mt-1 font-bold tracking-widest">Tunggu Admin membuat event sebelum Anda mengelola paket</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($events->hasPages())
        <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-center">
            <div class="tailwind-pagination">
                {{ $events->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    /* Styling Pagination Laravel agar menyatu dengan Tailwind */
    .tailwind-pagination nav div:first-child { display: none; }
    .tailwind-pagination nav span, .tailwind-pagination nav a { 
        padding: 10px 18px; border-radius: 14px; margin: 0 3px; font-size: 11px; font-weight: 900; transition: all 0.3s;
    }
    .tailwind-pagination nav a { background: white; border: 1px solid #e2e8f0; color: #64748b; }
    .tailwind-pagination nav a:hover { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .tailwind-pagination nav span[aria-current="page"] span { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
</style>
@endsection