@extends('layouts.staff')

@section('title', 'Edit Profil Staff')
@section('page-title', 'Edit Profil Staff')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in">
    
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('staff.profile.index') }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Edit Profil</h2>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Perbarui informasi identitas dan keamanan</p>
            </div>
        </div>
    </div>

    <form action="{{ route('staff.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] border border-slate-200 p-8 text-center shadow-sm sticky top-24">
                <div class="relative inline-block group">
                    <div id="imageWrapper" class="w-40 h-40 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-2xl shadow-blue-100 ring-1 ring-slate-100 transition-transform duration-500 group-hover:scale-[1.02]">
                        @if($user->foto_profil)
                            <img src="{{ Storage::url($user->foto_profil) }}" id="profilePreview" class="w-full h-full object-cover">
                        @else
                            <div id="profilePreviewPlaceholder" class="w-full h-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                <i class="fas fa-user text-white text-5xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <label for="photo" class="absolute -bottom-2 -right-2 h-12 w-12 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl flex items-center justify-center cursor-pointer shadow-xl shadow-blue-200 transition-all hover:rotate-12">
                        <i class="fas fa-camera text-lg"></i>
                        <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                    </label>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Foto Profil</h4>
                    <p class="text-[10px] text-slate-400 mt-2 font-medium leading-relaxed uppercase">Rekomendasi ukuran 1:1 (Persegi) maks. 5MB</p>
                </div>

                @if($user->foto_profil)
                <div class="mt-6 pt-6 border-t border-slate-50">
                    <label class="flex items-center justify-center cursor-pointer group">
                        <input type="checkbox" name="remove_photo" value="1" class="hidden peer">
                        <div class="w-5 h-5 border-2 border-slate-200 rounded-lg peer-checked:bg-rose-500 peer-checked:border-rose-500 transition-all flex items-center justify-center mr-2">
                            <i class="fas fa-check text-[10px] text-white"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-400 group-hover:text-rose-500 transition-colors uppercase">Hapus Foto Sekarang</span>
                    </label>
                </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center space-x-3">
                    <div class="h-8 w-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-id-card text-sm"></i>
                    </div>
                    <h5 class="text-sm font-black text-slate-800 uppercase tracking-widest text-blue-600">Informasi Dasar</h5>
                </div>
                
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                                       class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                       placeholder="Nama Lengkap">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Email (ID Staff)</label>
                            <div class="relative opacity-60">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="email" value="{{ $user->email }}" disabled
                                       class="w-full bg-slate-100 border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm cursor-not-allowed font-semibold">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Nomor Telepon</label>
                        <div class="relative group">
                            <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                            <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $user->telepon) }}"
                                   class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                   placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Alamat Lengkap</label>
                        <div class="relative group">
                            <i class="fas fa-map-marker-alt absolute left-4 top-4 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                            <textarea name="alamat" rows="3" 
                                      class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 pl-11 pr-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                      placeholder="Masukkan alamat domisili...">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-alt text-sm"></i>
                        </div>
                        <h5 class="text-sm font-black text-slate-800 uppercase tracking-widest text-orange-600">Keamanan Password</h5>
                    </div>
                    <span class="text-[9px] font-black bg-slate-100 text-slate-400 px-3 py-1 rounded-full uppercase tracking-widest">Opsional</span>
                </div>

                <div class="p-8 space-y-6">
                    <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100 flex items-start space-x-3">
                        <i class="fas fa-info-circle text-orange-500 mt-0.5"></i>
                        <p class="text-[11px] text-orange-700 font-bold leading-relaxed tracking-tight uppercase">Biarkan kosong jika tidak ingin mengubah password.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                   placeholder="••••••••">
                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-blue-500" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Password Baru</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password"
                                       class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                       placeholder="Min. 8 Karakter">
                                <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-blue-500" data-target="new_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-3 px-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span id="passwordHint" class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kekuatan</span>
                                    <span id="strengthText" class="text-[9px] font-black text-slate-400 uppercase tracking-widest">-</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden flex">
                                    <div id="passwordStrength" class="h-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase ml-1 tracking-widest">Ulangi Password Baru</label>
                            <div class="relative">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                       class="w-full bg-slate-50 border-slate-200 rounded-2xl py-3 px-4 text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold"
                                       placeholder="Konfirmasi Password">
                                <div id="matchIcon" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                                    <i class="fas fa-check-circle text-emerald-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-[2rem] p-4 shadow-xl shadow-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center px-4">
                    <div class="h-10 w-10 bg-white/10 rounded-xl flex items-center justify-center text-white mr-3">
                        <i class="fas fa-save"></i>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-[10px] font-black text-white/50 uppercase tracking-widest">Siap disimpan?</p>
                        <p class="text-xs font-bold text-white leading-none mt-1">Periksa kembali data Anda</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3 w-full sm:w-auto">
                    <a href="{{ route('staff.profile.index') }}" class="flex-1 sm:flex-none px-8 py-3 text-xs font-black text-white/60 hover:text-white uppercase tracking-widest transition-colors">Batal</a>
                    <button type="submit" class="flex-1 sm:flex-none px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-2xl shadow-lg shadow-blue-900/20 transition-all active:scale-95 uppercase tracking-widest">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo Preview Logic
    const photoInput = document.getElementById('photo');
    const profilePreview = document.getElementById('profilePreview');
    const wrapper = document.getElementById('imageWrapper');

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (profilePreview) {
                    profilePreview.src = e.target.result;
                } else {
                    wrapper.innerHTML = `<img src="${e.target.result}" id="profilePreview" class="w-full h-full object-cover">`;
                }
            }
            reader.readAsDataURL(file);
        }
    });

    // Password Visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // Password Strength Meter
    const newPass = document.getElementById('new_password');
    const strengthBar = document.getElementById('passwordStrength');
    const strengthText = document.getElementById('strengthText');

    newPass.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length > 0) score = 20;
        if (val.length >= 8) score = 40;
        if (/[A-Z]/.test(val)) score += 20;
        if (/[0-9]/.test(val)) score += 20;
        if (/[^A-Za-z0-9]/.test(val)) score += 20;

        strengthBar.style.width = score + '%';
        
        if (score <= 40) {
            strengthBar.className = 'h-full bg-rose-500 transition-all duration-500';
            strengthText.innerText = 'Lemah';
            strengthText.className = 'text-[9px] font-black text-rose-500 uppercase tracking-widest';
        } else if (score <= 80) {
            strengthBar.className = 'h-full bg-orange-500 transition-all duration-500';
            strengthText.innerText = 'Sedang';
            strengthText.className = 'text-[9px] font-black text-orange-500 uppercase tracking-widest';
        } else {
            strengthBar.className = 'h-full bg-emerald-500 transition-all duration-500';
            strengthText.innerText = 'Sangat Kuat';
            strengthText.className = 'text-[9px] font-black text-emerald-500 uppercase tracking-widest';
        }
    });

    // Confirmation Match
    const confirmPass = document.getElementById('new_password_confirmation');
    const matchIcon = document.getElementById('matchIcon');

    confirmPass.addEventListener('input', function() {
        if (this.value.length > 0 && this.value === newPass.value) {
            matchIcon.classList.remove('hidden');
        } else {
            matchIcon.classList.add('hidden');
        }
    });

    // Phone Formatter
    const phoneInput = document.getElementById('telepon');
    phoneInput.addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,4})(\d{0,4})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
    });
});
</script>
@endsection