@extends('layouts.staff')

@section('title', 'Kelola Paket - Staff Area')
@section('page-title', 'Kelola Paket Event')

@section('content')
<div class="space-y-6 animate-fade-in">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Paket Lomba</h2>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-tighter">Kelola kategori harga dan fasilitas peserta</p>
        </div>
        <a href="{{ route('staff.packages.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all transform hover:-translate-y-1">
            <i class="fas fa-plus mr-2"></i> TAMBAH PAKET BARU
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-blue-500 transition-all">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Paket</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</h3>
            </div>
            <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fas fa-box text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-emerald-500 transition-all">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Race Kit</p>
                <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ $stats['with_race_kit'] }}</h3>
            </div>
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i class="fas fa-gift text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-500 transition-all">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Medali</p>
                <h3 class="text-3xl font-black text-orange-500 mt-1">{{ $stats['with_medal'] }}</h3>
            </div>
            <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <i class="fas fa-medal text-xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-sky-500 transition-all">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kaos/Jersey</p>
                <h3 class="text-3xl font-black text-sky-600 mt-1">{{ $stats['with_shirt'] }}</h3>
            </div>
            <div class="p-4 bg-sky-50 text-sky-600 rounded-2xl group-hover:bg-sky-600 group-hover:text-white transition-colors">
                <i class="fas fa-tshirt text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-2">
        <form action="{{ route('staff.packages.index') }}" method="GET" class="flex items-center">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full bg-transparent border-none py-4 pl-12 pr-4 text-sm focus:ring-0 text-slate-600 placeholder:text-slate-300 font-medium"
                       placeholder="Cari nama paket atau nama event marathon...">
            </div>
            <button type="submit" class="px-8 py-3 bg-slate-800 hover:bg-black text-white text-xs font-bold rounded-2xl transition-all mr-2">
                CARI DATA
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($packages->count() > 0)
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Paket & Event</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fasilitas</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Harga Satuan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach($packages as $package)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-6 py-5">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-xs mr-4 shadow-lg shadow-blue-100">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 leading-tight">{{ $package->nama }}</p>
                                    <p class="text-[10px] text-blue-500 font-bold uppercase mt-1 tracking-tighter">
                                        <i class="fas fa-calendar-alt mr-1"></i> {{ $package->event_nama }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                {!! $package->termasuk_race_kit ? '<span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[9px] font-bold rounded-lg border border-emerald-100 tracking-tighter">RACE KIT</span>' : '' !!}
                                {!! $package->termasuk_medali ? '<span class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[9px] font-bold rounded-lg border border-orange-100 tracking-tighter">MEDALI</span>' : '' !!}
                                {!! $package->termasuk_kaos ? '<span class="px-2 py-0.5 bg-sky-50 text-sky-600 text-[9px] font-bold rounded-lg border border-sky-100 tracking-tighter">KAOS</span>' : '' !!}
                                {!! $package->termasuk_snack ? '<span class="px-2 py-0.5 bg-slate-50 text-slate-500 text-[9px] font-bold rounded-lg border border-slate-100 tracking-tighter">SNACK</span>' : '' !!}
                            </div>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <span class="text-sm font-black text-emerald-600 leading-none">Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{ route('staff.packages.edit', $package->id) }}" class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <button class="p-2.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="py-24 text-center">
                <div class="h-20 w-20 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-3xl"></i>
                </div>
                <h5 class="text-slate-700 font-black tracking-tight">Tidak Ada Paket Ditemukan</h5>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Silakan tambahkan paket untuk event mendatang</p>
            </div>
            @endif
        </div>

        @if($packages->hasPages())
        <div class="p-6 bg-slate-50 border-t border-slate-100">
            <div class="tailwind-pagination">
                {{ $packages->links() }}
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-8">
            <h5 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-blue-500"></i> Distribusi Fasilitas
            </h5>
            <div class="space-y-6">
                @php
                    $features = [
                        ['label' => 'Race Kit', 'count' => $stats['with_race_kit'], 'color' => 'bg-emerald-500'],
                        ['label' => 'Medali Juara', 'count' => $stats['with_medal'], 'color' => 'bg-orange-500'],
                        ['label' => 'Kaos Event', 'count' => $stats['with_shirt'], 'color' => 'bg-sky-500'],
                    ];
                @endphp

                @foreach($features as $f)
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-[11px] font-black uppercase tracking-wider">
                        <span class="text-slate-500">{{ $f['label'] }}</span>
                        <span class="text-slate-800">{{ $f['count'] }} Paket</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="{{ $f['color'] }} h-full transition-all duration-1000 shadow-sm" 
                             style="width: {{ $stats['total'] > 0 ? ($f['count'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-slate-800 rounded-3xl p-8 shadow-xl text-white relative overflow-hidden group">
            <i class="fas fa-info-circle absolute -top-4 -right-4 text-9xl text-white/5 group-hover:rotate-12 transition-transform"></i>
            <h5 class="text-sm font-black uppercase tracking-widest mb-6 flex items-center">
                <i class="fas fa-lightbulb mr-3 text-yellow-400"></i> Informasi Strategis
            </h5>
            <ul class="space-y-4 relative z-10">
                <li class="flex items-start bg-white/5 p-4 rounded-2xl border border-white/5">
                    <div class="h-8 w-8 bg-blue-500 text-white rounded-lg flex items-center justify-center shrink-0 mr-4 font-bold text-xs">1</div>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">Pastikan harga paket sudah termasuk margin biaya operasional dan vendor.</p>
                </li>
                <li class="flex items-start bg-white/5 p-4 rounded-2xl border border-white/5">
                    <div class="h-8 w-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center shrink-0 mr-4 font-bold text-xs">2</div>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">Paket dengan <span class="text-emerald-400 font-bold uppercase tracking-tighter">Medali</span> biasanya memiliki minat pendaftaran 40% lebih tinggi.</p>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .tailwind-pagination nav div:first-child { display: none; }
    .tailwind-pagination nav span, .tailwind-pagination nav a { 
        padding: 8px 16px; border-radius: 12px; margin: 0 2px; font-size: 10px; font-weight: 900; transition: all 0.3s;
    }
    .tailwind-pagination nav a { background: white; border: 1px solid #e2e8f0; color: #64748b; }
    .tailwind-pagination nav a:hover { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .tailwind-pagination nav span[aria-current="page"] span { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
</style>
@endsection