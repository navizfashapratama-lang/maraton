<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        
        .border-gradient {
            border-image: linear-gradient(to right, #8b5cf6, #ec4899);
            border-image-slice: 1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-white bg-opacity-90 z-50 flex flex-col items-center justify-center">
        <div class="w-16 h-16 border-4 border-gray-300 border-t-blue-600 rounded-full animate-spin"></div>
        <div class="mt-4 text-gray-600 font-medium">Menyimpan perubahan...</div>
    </div>

    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.events.index') }}" 
                           class="text-gray-600 hover:text-gray-800 transition duration-200">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Edit Event: {{ $event->nama }}</h1>
                            <p class="text-gray-600 text-sm">Perbarui detail event marathon</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ date('d F Y') }}
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Messages -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg shadow animate-fade-up">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-green-800">{{ session('success') }}</p>
                        <p class="text-green-600 text-sm mt-1">Event berhasil diperbarui</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow animate-fade-up">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-red-800">{{ session('error') }}</p>
                        <p class="text-red-600 text-sm mt-1">Silakan periksa kembali data yang dimasukkan</p>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow animate-fade-up">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-medium text-yellow-800">Ada {{ $errors->count() }} kesalahan yang perlu diperbaiki</p>
                        <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Event Info Summary -->
            <div class="mb-8 bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">Informasi Event</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Dibuat</p>
                                <p class="font-medium">{{ $event->created_at ? \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Diupdate</p>
                                <p class="font-medium">{{ $event->updated_at ? \Carbon\Carbon::parse($event->updated_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status Saat Ini</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $event->status == 'mendatang' ? 'bg-green-100 text-green-800' : 
                                       ($event->status == 'selesai' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    <i class="fas fa-circle text-xs mr-2 
                                        {{ $event->status == 'mendatang' ? 'text-green-500' : 
                                           ($event->status == 'selesai' ? 'text-gray-500' : 'text-red-500') }}"></i>
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">ID Event</p>
                                <p class="font-medium font-mono">#{{ $event->id }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('admin.events.show', $event->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition duration-200">
                            <i class="fas fa-eye mr-2"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-up">
                <div class="h-2 bg-gradient-to-r from-purple-600 to-pink-600"></div>
                <div class="p-8">
                    <!-- Form Header -->
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white">
                                <i class="fas fa-edit text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Form Edit Event Marathon</h2>
                                <p class="text-gray-600 text-sm">Perbarui detail event dengan lengkap</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 bg-gray-50 p-3 rounded-lg">
                            <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                            Semua field bertanda <span class="text-red-500 font-medium">*</span> wajib diisi
                        </div>
                    </div>

                    <!-- Form -->
                    <!-- PERBAIKAN DI SINI: TIDAK PAKAI @method('POST') -->
                    <form method="POST" action="{{ route('admin.events.update', $event->id) }}" id="eventForm" novalidate>
                        @csrf
                        
                        <!-- Row 1: Nama & Kategori -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nama Event -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Event <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $event->nama) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                       placeholder="Contoh: Marathon Jakarta 2024"
                                       required>
                                @error('nama')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                            
                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="kategori" 
                                        name="kategori" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 bg-white"
                                        required>
                                    <option value="" disabled class="text-gray-400">Pilih Kategori</option>
                                    <option value="3K" {{ old('kategori', $event->kategori) == '3K' ? 'selected' : '' }}>Fun Run 3K</option>
                                    <option value="5K" {{ old('kategori', $event->kategori) == '5K' ? 'selected' : '' }}>5K Run</option>
                                    <option value="10K" {{ old('kategori', $event->kategori) == '10K' ? 'selected' : '' }}>10K Run</option>
                                    <option value="21K" {{ old('kategori', $event->kategori) == '21K' ? 'selected' : '' }}>Half Marathon 21K</option>
                                    <option value="42K" {{ old('kategori', $event->kategori) == '42K' ? 'selected' : '' }}>Full Marathon 42K</option>
                                    <option value="Trail" {{ old('kategori', $event->kategori) == 'Trail' ? 'selected' : '' }}>Trail Run</option>
                                    <option value="Fun Run" {{ old('kategori', $event->kategori) == 'Fun Run' ? 'selected' : '' }}>Fun Run</option>
                                </select>
                                @error('kategori')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Row 2: Tanggal & Lokasi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tanggal Event -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Event <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ old('tanggal', $event->tanggal) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                       required>
                                <div class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    Pilih tanggal pelaksanaan event
                                </div>
                                @error('tanggal')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                            
                            <!-- Lokasi -->
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="lokasi" 
                                       name="lokasi" 
                                       value="{{ old('lokasi', $event->lokasi) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                       placeholder="Contoh: Stadion Utama Gelora Bung Karno"
                                       required>
                                @error('lokasi')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Row 3: Harga Reguler & Premium -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Harga Reguler -->
                            <div>
                                <label for="harga_reguler" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Reguler <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" 
                                           id="harga_reguler" 
                                           name="harga_reguler" 
                                           value="{{ old('harga_reguler', $event->harga_reguler ?? 0) }}"
                                           min="10000"
                                           step="1000"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                           placeholder="10000"
                                           required>
                                </div>
                                <div class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    Harga minimal Rp 10.000
                                </div>
                                @error('harga_reguler')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                            
                            <!-- Harga Premium -->
                            <div>
                                <label for="harga_premium" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Premium
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" 
                                           id="harga_premium" 
                                           name="harga_premium" 
                                           value="{{ old('harga_premium', $event->harga_premium ?? 0) }}"
                                           min="0"
                                           step="1000"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                           placeholder="0 (opsional)">
                                </div>
                                <div class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-crown mr-2 text-yellow-500"></i>
                                    Harga paket premium (opsional)
                                </div>
                                @error('harga_premium')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Deskripsi Event -->
                        <div class="mb-6">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Event <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200 resize-none"
                                      placeholder="Deskripsi lengkap tentang event, rute, fasilitas, dll."
                                      required>{{ old('deskripsi', $event->deskripsi ?? '') }}</textarea>
                            <div class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-align-left mr-2"></i>
                                Jelaskan event secara detail untuk menarik peserta
                            </div>
                            @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Row 4: Kuota Peserta -->
                        <div class="mb-6">
                            <label for="kuota_peserta" class="block text-sm font-medium text-gray-700 mb-2">
                                Kuota Peserta
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                                <input type="number" 
                                       id="kuota_peserta" 
                                       name="kuota_peserta" 
                                       value="{{ old('kuota_peserta', $event->kuota_peserta ?? 0) }}"
                                       min="0"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                                       placeholder="0 untuk tidak terbatas">
                            </div>
                            <div class="mt-2 text-xs text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Kosongkan atau isi 0 untuk kuota tidak terbatas
                            </div>
                            @error('kuota_peserta')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Row 5: Status -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status Event <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50 transition duration-200 group">
                                    <input type="radio" 
                                           name="status" 
                                           value="mendatang" 
                                           {{ old('status', $event->status) == 'mendatang' ? 'checked' : '' }}
                                           class="text-blue-600 focus:ring-blue-500 mr-3 group-hover:scale-110 transition duration-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Mendatang</div>
                                        <div class="text-sm text-gray-600">Event akan datang</div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition duration-200">
                                        <i class="fas fa-clock text-blue-600 text-sm"></i>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50 transition duration-200 group">
                                    <input type="radio" 
                                           name="status" 
                                           value="selesai" 
                                           {{ old('status', $event->status) == 'selesai' ? 'checked' : '' }}
                                           class="text-green-600 focus:ring-green-500 mr-3 group-hover:scale-110 transition duration-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Selesai</div>
                                        <div class="text-sm text-gray-600">Event telah berakhir</div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition duration-200">
                                        <i class="fas fa-check-circle text-green-600 text-sm"></i>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-red-50 transition duration-200 group">
                                    <input type="radio" 
                                           name="status" 
                                           value="dibatalkan" 
                                           {{ old('status', $event->status) == 'dibatalkan' ? 'checked' : '' }}
                                           class="text-red-600 focus:ring-red-500 mr-3 group-hover:scale-110 transition duration-200">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-800">Dibatalkan</div>
                                        <div class="text-sm text-gray-600">Event dibatalkan</div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition duration-200">
                                        <i class="fas fa-times-circle text-red-600 text-sm"></i>
                                    </div>
                                </label>
                            </div>
                            @error('status')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 pt-8 border-t border-gray-200">
                            <a href="{{ route('admin.events.index') }}" 
                               class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200 flex items-center justify-center group">
                                <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition duration-200"></i>Kembali ke Daftar
                            </a>
                            
                            <div class="flex space-x-3">
                                <button type="button" 
                                        onclick="resetToOriginal()"
                                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200 flex items-center group">
                                    <i class="fas fa-undo mr-2 group-hover:rotate-180 transition duration-500"></i>Reset ke Asli
                                </button>
                                
                                <button type="button" 
                                        onclick="showDeleteModal()"
                                        class="px-6 py-3 border border-red-300 text-red-700 font-medium rounded-lg hover:bg-red-50 transition duration-200 flex items-center group">
                                    <i class="fas fa-trash mr-2 group-hover:scale-110 transition duration-200"></i>Hapus Event
                                </button>
                                
                                <button type="submit" 
                                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center group">
                                    <i class="fas fa-save mr-2 group-hover:scale-110 transition duration-200"></i>Update Event
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Warning Box -->
            <div class="mt-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-yellow-500 to-orange-500 flex items-center justify-center text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">Perhatian!</h3>
                        <p class="text-gray-600 text-sm">Perubahan yang dilakukan akan mempengaruhi data pendaftaran dan pembayaran yang terkait dengan event ini.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95">
            <div class="p-6">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Hapus Event</h3>
                <p class="text-gray-600 text-center mb-6">
                    Anda yakin ingin menghapus event <span class="font-bold">"{{ $event->nama }}"</span>? 
                    Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus.
                </p>
                <div class="flex space-x-3">
                    <button onclick="hideDeleteModal()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 rounded-xl transition duration-200">
                        Batal
                    </button>
                    <a href="{{ route('admin.events.destroy', $event->id) }}" 
                       onclick="return confirmDelete()"
                       class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-3 rounded-xl transition duration-200 flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show loading overlay on form submit
        document.getElementById('eventForm').addEventListener('submit', function(e) {
            // Client-side validation
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500', 'animate-pulse');
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                    
                    // Remove animation after 1 second
                    setTimeout(() => {
                        field.classList.remove('animate-pulse');
                    }, 1000);
                }
            });
            
            // Validate harga premium > harga reguler
            const hargaReguler = parseFloat(document.getElementById('harga_reguler').value) || 0;
            const hargaPremium = parseFloat(document.getElementById('harga_premium').value) || 0;
            
            if (hargaPremium > 0 && hargaPremium <= hargaReguler) {
                isValid = false;
                showToast('Harga premium harus lebih tinggi dari harga reguler!', 'error');
                document.getElementById('harga_premium').classList.add('border-red-500', 'animate-pulse');
                
                if (!firstErrorField) {
                    firstErrorField = document.getElementById('harga_premium');
                }
            }
            
            // Validate date for upcoming events
            const selectedStatus = document.querySelector('input[name="status"]:checked');
            if (selectedStatus && selectedStatus.value === 'mendatang') {
                const eventDate = new Date(document.getElementById('tanggal').value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (eventDate < today) {
                    isValid = false;
                    showToast('Tanggal event mendatang tidak boleh di masa lalu!', 'error');
                    document.getElementById('tanggal').classList.add('border-red-500', 'animate-pulse');
                    
                    if (!firstErrorField) {
                        firstErrorField = document.getElementById('tanggal');
                    }
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error field
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                    firstErrorField.focus();
                }
                
                // Show error message
                showToast('Harap periksa kembali data yang dimasukkan!', 'error');
                return;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.remove('hidden');
        });
        
        // Remove error styling when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            });
        });
        
        // Reset form to original values
        function resetToOriginal() {
            if (confirm('Reset form ke nilai asli? Semua perubahan akan hilang.')) {
                // Reload the page to get original values
                window.location.reload();
            }
        }
        
        // Delete confirmation
        function confirmDelete() {
            return confirm('Yakin ingin menghapus event ini? Tindakan ini tidak dapat dibatalkan!');
        }
        
        // Delete modal functions
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            setTimeout(() => {
                document.querySelector('#deleteModal > div').classList.remove('scale-95');
            }, 10);
        }
        
        function hideDeleteModal() {
            document.querySelector('#deleteModal > div').classList.add('scale-95');
            setTimeout(() => {
                document.getElementById('deleteModal').classList.add('hidden');
            }, 300);
        }
        
        // Auto-calculate premium price based on regular price
        const regularPrice = document.getElementById('harga_reguler');
        const premiumPrice = document.getElementById('harga_premium');
        
        regularPrice.addEventListener('change', function() {
            if (this.value && !premiumPrice.value) {
                const premiumValue = Math.round(this.value * 1.3); // Premium 30% lebih mahal
                premiumPrice.value = premiumValue;
                
                showToast('Harga premium telah dihitung otomatis (30% lebih tinggi dari reguler)', 'info');
            }
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            const toastId = 'toast-' + Date.now();
            
            toast.id = toastId;
            toast.className = `fixed top-4 right-4 z-50 bg-white rounded-xl shadow-2xl p-4 min-w-[300px] transform transition-all duration-500 translate-x-full`;
            toast.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full ${type === 'success' ? 'bg-green-100' : type === 'info' ? 'bg-blue-100' : 'bg-red-100'} flex items-center justify-center">
                        <i class="fas ${type === 'success' ? 'fa-check text-green-600' : type === 'info' ? 'fa-info text-blue-600' : 'fa-exclamation text-red-600'}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800">${type === 'success' ? 'Berhasil!' : type === 'info' ? 'Informasi!' : 'Perhatian!'}</p>
                        <p class="text-sm text-gray-600">${message}</p>
                    </div>
                    <button onclick="removeToast('${toastId}')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toastId);
            }, 5000);
        }
        
        // Remove toast function
        function removeToast(id) {
            const toast = document.getElementById(id);
            if (toast) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }
        }
        
        // Check for old input values on page load
        document.addEventListener('DOMContentLoaded', function() {
            // If there's an error, scroll to top
            @if($errors->any())
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Show error summary
                const errorCount = {{ $errors->count() }};
                if (errorCount > 0) {
                    showToast(`Ada ${errorCount} kesalahan yang perlu diperbaiki`, 'error');
                }
            @endif
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').min = today;
        });
    </script>
</body>
</html>