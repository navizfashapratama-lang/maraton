@extends('layouts.staff')

@section('title', 'Profil Staff')
@section('page-title', 'Profil Staff')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden sticky top-24">
                <div class="h-32 bg-gradient-to-br from-blue-600 to-blue-800 relative">
                    <div class="absolute top-4 right-4">
                        <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full border border-white/20">
                            {{ $user->peran }}
                        </span>
                    </div>
                </div>
                
                <div class="px-6 pb-8 text-center">
                    <div class="relative inline-block -mt-16 mb-4">
                        @if($user->foto_profil)
                            <img src="{{ Storage::url($user->foto_profil) }}" 
                                 class="w-32 h-32 rounded-3xl object-cover border-4 border-white shadow-xl shadow-blue-100 mx-auto transform hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-32 h-32 rounded-3xl bg-gradient-to-tr from-blue-100 to-blue-50 border-4 border-white shadow-xl flex items-center justify-center mx-auto transform hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-user text-blue-400 text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute bottom-1 right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full shadow-sm"></div>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $user->nama }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $user->email }}</p>
                    
                    <div class="mt-8">
                        <a href="{{ route('staff.profile.edit') }}" class="inline-flex items-center justify-center w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-2xl shadow-lg shadow-blue-200 transition-all group">
                            <i class="fas fa-edit mr-2 group-hover:rotate-12 transition-transform"></i> EDIT PROFIL SAYA
                        </a>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest">
                        <span class="text-slate-400">Status Akun</span>
                        @if($user->is_active ?? true)
                            <span class="text-emerald-500 flex items-center"><i class="fas fa-check-circle mr-1.5"></i> AKTIF</span>
                        @else
                            <span class="text-rose-500 flex items-center"><i class="fas fa-times-circle mr-1.5"></i> NONAKTIF</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm group hover:border-blue-500 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-check-double text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['total_verifications'] ?? 0 }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Transaksi Diverifikasi</p>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: 75%"></div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm group hover:border-orange-500 transition-all">
                    <div class="flex items-center space-x-4">
                        <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-slate-800 tracking-tighter">{{ $stats['pending_tasks'] ?? 0 }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tugas Tertunda</p>
                        </div>
                    </div>
                    <div class="mt-4 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="bg-orange-500 h-full rounded-full transition-all duration-1000 shadow-sm" style="width: 40%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <h5 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                        <i class="fas fa-info-circle mr-3 text-blue-500"></i> Informasi Pribadi & Akses
                    </h5>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-start group">
                        <div class="p-3 bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 rounded-2xl transition-colors shrink-0 mr-4">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Telepon</p>
                            <p class="text-sm font-black text-slate-700">{{ $user->telepon ?? 'Belum Diatur' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="p-3 bg-slate-50 text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 rounded-2xl transition-colors shrink-0 mr-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Terdaftar Sejak</p>
                            <p class="text-sm font-black text-slate-700">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="p-3 bg-slate-50 text-slate-400 group-hover:bg-sky-50 group-hover:text-sky-600 rounded-2xl transition-colors shrink-0 mr-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Login Terakhir</p>
                            <p class="text-sm font-black text-slate-700">
                                {{ $user->terakhir_login ? \Carbon\Carbon::parse($user->terakhir_login)->diffForHumans() : 'Sesi Pertama' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="p-3 bg-slate-50 text-slate-400 group-hover:bg-rose-50 group-hover:text-rose-600 rounded-2xl transition-colors shrink-0 mr-4">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lokasi/Alamat</p>
                            <p class="text-sm font-black text-slate-700 leading-tight">{{ $user->alamat ?? 'Alamat Belum Diisi' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mx-8 mb-8 p-6 bg-blue-600 rounded-[1.5rem] shadow-xl shadow-blue-100 relative overflow-hidden group">
                    <i class="fas fa-quote-right absolute -right-4 -bottom-4 text-8xl text-white/10 group-hover:scale-110 transition-transform"></i>
                    <div class="relative z-10 flex items-center">
                        <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center mr-4 text-white">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <p class="text-white/70 text-[10px] font-bold uppercase tracking-widest">Aktivitas Staff</p>
                            <p class="text-white text-xs font-medium leading-relaxed mt-0.5 italic">"Kualitas pendaftaran marathon ada pada ketelitian verifikasi Anda hari ini."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    // Simple logic to animate bars on scroll
    document.addEventListener('DOMContentLoaded', () => {
        const bars = document.querySelectorAll('.h-1\\.5 div');
        bars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => { bar.style.width = width; }, 500);
        });
    });
</script>
@endsection