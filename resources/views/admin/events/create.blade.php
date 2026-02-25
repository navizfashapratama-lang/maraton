<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event Baru - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .required::after {
            content: " *";
            color: #ef4444;
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* File Upload Styling */
        .file-upload-container {
            transition: all 0.3s ease;
        }
        
        .file-upload-container:hover {
            border-color: #3b82f6;
            background-color: #f8fafc;
        }
        
        .file-preview {
            max-height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-gray-100">
    <!-- Loading Screen -->
    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
        <div class="relative">
            <div class="w-20 h-20 rounded-full border-4 border-gray-200 border-t-blue-600 animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-running text-2xl text-blue-600"></i>
            </div>
        </div>
        <p class="mt-6 text-gray-600">Menyiapkan formulir...</p>
    </div>

    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-lg sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <a href="{{ route('admin.events.index') }}" 
                           class="mr-4 p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition duration-200">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Tambah Event Baru</h1>
                            <p class="text-gray-600 text-sm">Buat event marathon untuk pendaftaran peserta</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-medium text-gray-800">{{ session('user_nama') ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white shadow">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-700 hover:text-blue-600">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <a href="{{ route('admin.events.index') }}" class="ml-1 text-sm text-gray-700 hover:text-blue-600 md:ml-2">
                                Event Lomba
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <span class="ml-1 text-sm font-medium text-blue-600 md:ml-2">Tambah Event</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Form Container -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form Section -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in">
                        <!-- Form Header -->
                        <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-blue-600">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                    <i class="fas fa-calendar-plus text-white text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-white">Formulir Event Baru</h2>
                                    <p class="text-blue-100 text-sm">Isi semua informasi yang diperlukan</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Success/Error Messages -->
                        @if(session('success'))
                        <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($errors->any())
                        <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terjadi kesalahan</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Event Form -->
                        <form method="POST" action="{{ route('admin.events.store') }}" id="eventForm" class="p-6 space-y-8 custom-scrollbar" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Section 1: Basic Information -->
                            <div class="border-b border-gray-200 pb-8">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Informasi Dasar Event</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama Event -->
                                    <div class="md:col-span-2">
                                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2 required">Nama Event</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-running text-gray-400"></i>
                                            </div>
                                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                   placeholder="Contoh: Marathon Jakarta 2024" required>
                                        </div>
                                        @error('nama')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Kategori Lomba -->
                                    <div>
                                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2 required">Kategori Lomba</label>
                                        <div class="relative">
                                            <select id="kategori" name="kategori" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none" required>
                                                <option value="">Pilih Kategori Lomba</option>
                                                <option value="5K" {{ old('kategori') == '5K' ? 'selected' : '' }}>5K Run (5 Kilometer)</option>
                                                <option value="10K" {{ old('kategori') == '10K' ? 'selected' : '' }}>10K Run (10 Kilometer)</option>
                                                <option value="21K" {{ old('kategori') == '21K' ? 'selected' : '' }}>Half Marathon (21 Kilometer)</option>
                                                <option value="42K" {{ old('kategori') == '42K' ? 'selected' : '' }}>Full Marathon (42 Kilometer)</option>
                                                <option value="Fun Run" {{ old('kategori') == 'Fun Run' ? 'selected' : '' }}>Fun Run</option>
                                                <option value="Trail Run" {{ old('kategori') == 'Trail Run' ? 'selected' : '' }}>Trail Run</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('kategori')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Kategori Event -->
                                    <div>
                                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori Event</label>
                                        <div class="relative">
                                            <select id="kategori_id" name="kategori_id" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none">
                                                <option value="">Pilih Kategori Event</option>
                                                @if(isset($categories) && $categories->count() > 0)
                                                    @foreach($categories as $kategori)
                                                        <option value="{{ $kategori->id }}" 
                                                            {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                            {{ $kategori->nama_kategori }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled>Belum ada kategori event</option>
                                                @endif
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-tags text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('kategori_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Tanggal Event -->
                                    <div>
                                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2 required">Tanggal Event</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" required>
                                        </div>
                                        @error('tanggal')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Tanggal Tutup Pendaftaran -->
                                    <div>
                                        <label for="pendaftaran_ditutup" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Tutup Pendaftaran</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-calendar-times text-gray-400"></i>
                                            </div>
                                            <input type="date" id="pendaftaran_ditutup" name="pendaftaran_ditutup" value="{{ old('pendaftaran_ditutup') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika sama dengan tanggal event</p>
                                        @error('pendaftaran_ditutup')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Kuota Peserta -->
                                    <div>
                                        <label for="kuota_peserta" class="block text-sm font-medium text-gray-700 mb-2">Kuota Peserta</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-users text-gray-400"></i>
                                            </div>
                                            <input type="number" id="kuota_peserta" name="kuota_peserta" value="{{ old('kuota_peserta', 100) }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                   min="1" placeholder="100">
                                        </div>
                                        @error('kuota_peserta')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Location Information -->
                            <div class="border-b border-gray-200 pb-8">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Lokasi & Tempat</h3>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Lokasi -->
                                    <div>
                                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2 required">Lokasi Event</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            </div>
                                            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                   placeholder="Contoh: Gelora Bung Karno, Senayan, Jakarta" required>
                                        </div>
                                        @error('lokasi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Harga Paket -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Harga Reguler -->
                                        <div>
                                            <label for="harga_reguler" class="block text-sm font-medium text-gray-700 mb-2 required">Harga Reguler</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-money-bill text-gray-400"></i>
                                                </div>
                                                <input type="number" id="harga_reguler" name="harga_reguler" value="{{ old('harga_reguler') }}" 
                                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                       placeholder="250000" min="0" required>
                                            </div>
                                            @error('harga_reguler')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <!-- Harga Premium -->
                                        <div>
                                            <label for="harga_premium" class="block text-sm font-medium text-gray-700 mb-2">Harga Premium (Opsional)</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-crown text-gray-400"></i>
                                                </div>
                                                <input type="number" id="harga_premium" name="harga_premium" value="{{ old('harga_premium') }}" 
                                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                       placeholder="500000" min="0">
                                            </div>
                                            @error('harga_premium')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Event Details -->
                            <div class="border-b border-gray-200 pb-8">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800">Detail & Status</h3>
                                </div>
                                
                                <div class="space-y-6">
                                    <!-- Deskripsi Event -->
                                    <div>
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2 required">Deskripsi Event</label>
                                        <textarea id="deskripsi" name="deskripsi" rows="4" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none custom-scrollbar"
                                                  placeholder="Jelaskan detail event, fasilitas, hadiah, dll." required>{{ old('deskripsi') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-gray-500">Maksimal 2000 karakter</div>
                                            <div id="charCountDeskripsi" class="text-xs text-gray-500">0/2000</div>
                                        </div>
                                        @error('deskripsi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Rute Lomba -->
                                    <div>
                                        <label for="rute_lomba" class="block text-sm font-medium text-gray-700 mb-2">Rute Lomba (Opsional)</label>
                                        <textarea id="rute_lomba" name="rute_lomba" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none custom-scrollbar"
                                                  placeholder="Jelaskan rute lomba, checkpoint, elevasi, dll.">{{ old('rute_lomba') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-gray-500">Maksimal 1000 karakter</div>
                                            <div id="charCountRute" class="text-xs text-gray-500">0/1000</div>
                                        </div>
                                        @error('rute_lomba')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Syarat dan Ketentuan -->
                                    <div>
                                        <label for="syarat_ketentuan" class="block text-sm font-medium text-gray-700 mb-2">Syarat & Ketentuan (Opsional)</label>
                                        <textarea id="syarat_ketentuan" name="syarat_ketentuan" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none custom-scrollbar"
                                                  placeholder="Syarat keikutsertaan, ketentuan umum, dll.">{{ old('syarat_ketentuan') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-gray-500">Maksimal 1500 karakter</div>
                                            <div id="charCountSyarat" class="text-xs text-gray-500">0/1500</div>
                                        </div>
                                        @error('syarat_ketentuan')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Fasilitas -->
                                    <div>
                                        <label for="fasilitas" class="block text-sm font-medium text-gray-700 mb-2">Fasilitas (Opsional)</label>
                                        <textarea id="fasilitas" name="fasilitas" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none custom-scrollbar"
                                                  placeholder="Fasilitas yang disediakan untuk peserta">{{ old('fasilitas') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-gray-500">Maksimal 1000 karakter</div>
                                            <div id="charCountFasilitas" class="text-xs text-gray-500">0/1000</div>
                                        </div>
                                        @error('fasilitas')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Status Event -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Event</label>
                                        <div class="relative">
                                            <select id="status" name="status" 
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 appearance-none">
                                                <option value="mendatang" {{ old('status', 'mendatang') == 'mendatang' ? 'selected' : '' }}>Mendatang</option>
                                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="dibatalkan" {{ old('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-chevron-down text-gray-400"></i>
                                            </div>
                                        </div>
                                        @error('status')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Upload Gambar -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Poster Event (Opsional)</label>
                                        <div class="space-y-4">
                                            <div id="fileUploadContainer" class="file-upload-container border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center hover:border-blue-400 transition duration-200 cursor-pointer">
                                                <div class="space-y-3">
                                                    <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-cloud-upload-alt text-blue-500 text-2xl"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-700">Klik untuk upload poster</p>
                                                        <p class="text-sm text-gray-500 mt-1">PNG, JPG, JPEG, GIF (maks. 5MB)</p>
                                                    </div>
                                                    <input type="file" id="poster_url" name="poster_url" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif">
                                                    <button type="button" onclick="document.getElementById('poster_url').click()" 
                                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                        <i class="fas fa-upload mr-2"></i> Pilih File
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div id="filePreview" class="hidden">
                                                <div class="p-4 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center justify-between mb-3">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center">
                                                                <img id="previewImage" src="" alt="Preview" class="file-preview w-full h-full object-cover hidden">
                                                                <i class="fas fa-image text-gray-400 text-xl"></i>
                                                            </div>
                                                            <div>
                                                                <p id="previewFileName" class="font-medium text-gray-800"></p>
                                                                <p id="previewFileSize" class="text-sm text-gray-600"></p>
                                                                <p id="previewFileType" class="text-xs text-gray-500"></p>
                                                            </div>
                                                        </div>
                                                        <button type="button" onclick="removeFile()" 
                                                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition duration-200">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="flex items-center text-sm text-blue-600">
                                                        <i class="fas fa-check-circle mr-2"></i>
                                                        <span>File siap diupload</span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div id="fileError" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                                    <p id="errorMessage" class="text-sm text-red-700"></p>
                                                </div>
                                            </div>
                                        </div>
                                        @error('poster_url')
                                            <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-sm text-red-700">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="pt-8 border-t border-gray-200">
                                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                                    <div class="text-sm text-gray-500">
                                        <p class="flex items-center">
                                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                            Field dengan tanda <span class="text-red-500 font-medium">*</span> wajib diisi
                                        </p>
                                    </div>
                                    
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.events.index') }}" 
                                           class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition duration-200 flex items-center focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                            <i class="fas fa-times mr-2"></i> Batal
                                        </a>
                                        <button type="submit" id="submitBtn"
                                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <i class="fas fa-save mr-2"></i> Simpan Event
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar - Help & Preview -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Help Card -->
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-lg p-6 animate-fade-in">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-question-circle text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Tips & Panduan</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Kategori Event</p>
                                    <p class="text-sm text-gray-600 mt-1">Pilih kategori yang sesuai untuk mengelompokkan event</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Nama Event</p>
                                    <p class="text-sm text-gray-600 mt-1">Gunakan nama yang jelas dan menarik perhatian</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Harga</p>
                                    <p class="text-sm text-gray-600 mt-1">Harga wajib diisi untuk paket reguler</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Tanggal Event</p>
                                    <p class="text-sm text-gray-600 mt-1">Pastikan tanggal event benar</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Paket Otomatis</p>
                                    <p class="text-sm text-gray-600 mt-1">Paket reguler dan premium akan dibuat otomatis</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Preview Event</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-700">Nama Event:</div>
                                    <div id="previewNama" class="text-sm text-gray-800 font-semibold text-right">-</div>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-700">Kategori Lomba:</div>
                                    <div id="previewKategori" class="text-sm text-gray-800 text-right">-</div>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-700">Kategori Event:</div>
                                    <div id="previewKategoriEvent" class="text-sm text-gray-800 text-right">-</div>
                                </div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-sm font-medium text-gray-700">Tanggal:</div>
                                    <div id="previewTanggal" class="text-sm text-gray-800 text-right">-</div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-gray-700">Lokasi:</div>
                                    <div id="previewLokasi" class="text-sm text-gray-800 text-right">-</div>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <div class="text-sm font-medium text-gray-700 mb-2">Harga:</div>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Reguler:</span>
                                        <span id="previewHargaReguler" class="font-semibold text-gray-800 text-sm">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Premium:</span>
                                        <span id="previewHargaPremium" class="font-semibold text-yellow-600 text-sm">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-green-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Status:</span>
                                    <span id="previewStatus" class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        Mendatang
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 animate-fade-in">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.events.index') }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200 group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition duration-200">
                                        <i class="fas fa-list text-white"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Daftar Event</span>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition duration-200"></i>
                            </a>
                            
                            <a href="{{ route('admin.registrations.index') }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200 group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-700 transition duration-200">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Pendaftaran</span>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition duration-200"></i>
                            </a>
                            
                            <a href="{{ route('admin.dashboard') }}" 
                               class="flex items-center justify-between p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200 group">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-700 transition duration-200">
                                        <i class="fas fa-tachometer-alt text-white"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">Dashboard</span>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition duration-200"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Hide loading screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loadingScreen').style.opacity = '0';
                document.getElementById('loadingScreen').style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    document.getElementById('loadingScreen').style.display = 'none';
                }, 500);
            }, 500);
        });

        // File upload handling
        const fileInput = document.getElementById('poster_url');
        const fileUploadContainer = document.getElementById('fileUploadContainer');
        const filePreview = document.getElementById('filePreview');
        const previewImage = document.getElementById('previewImage');
        const previewFileName = document.getElementById('previewFileName');
        const previewFileSize = document.getElementById('previewFileSize');
        const previewFileType = document.getElementById('previewFileType');
        const fileError = document.getElementById('fileError');
        const errorMessage = document.getElementById('errorMessage');

        fileUploadContainer.addEventListener('click', () => fileInput.click());
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Reset error
            fileError.classList.add('hidden');
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                showFileError('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                fileInput.value = '';
                return;
            }
            
            // Validate file size (5MB max)
            const maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if (file.size > maxSize) {
                showFileError('Ukuran file terlalu besar. Maksimal 5MB.');
                fileInput.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                
                previewFileName.textContent = file.name;
                previewFileSize.textContent = formatFileSize(file.size);
                previewFileType.textContent = file.type.split('/')[1].toUpperCase();
                
                fileUploadContainer.classList.add('hidden');
                filePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        function removeFile() {
            fileInput.value = '';
            previewImage.src = '';
            previewImage.classList.add('hidden');
            filePreview.classList.add('hidden');
            fileUploadContainer.classList.remove('hidden');
        }

        function showFileError(message) {
            errorMessage.textContent = message;
            fileError.classList.remove('hidden');
            setTimeout(() => {
                fileError.classList.add('hidden');
            }, 5000);
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Character counters
        const deskripsiInput = document.getElementById('deskripsi');
        const ruteLombaInput = document.getElementById('rute_lomba');
        const syaratKetentuanInput = document.getElementById('syarat_ketentuan');
        const fasilitasInput = document.getElementById('fasilitas');
        
        const charCountDeskripsi = document.getElementById('charCountDeskripsi');
        const charCountRute = document.getElementById('charCountRute');
        const charCountSyarat = document.getElementById('charCountSyarat');
        const charCountFasilitas = document.getElementById('charCountFasilitas');

        function updateCharCount(textarea, counter, maxLength) {
            const currentLength = textarea.value.length;
            counter.textContent = `${currentLength}/${maxLength}`;
            
            if (currentLength > maxLength) {
                counter.classList.remove('text-gray-500');
                counter.classList.add('text-red-600');
                textarea.classList.add('border-red-300');
            } else {
                counter.classList.remove('text-red-600');
                counter.classList.add('text-gray-500');
                textarea.classList.remove('border-red-300');
            }
        }

        // Initialize character counters
        const textareas = [
            { element: deskripsiInput, counter: charCountDeskripsi, maxLength: 2000 },
            { element: ruteLombaInput, counter: charCountRute, maxLength: 1000 },
            { element: syaratKetentuanInput, counter: charCountSyarat, maxLength: 1500 },
            { element: fasilitasInput, counter: charCountFasilitas, maxLength: 1000 }
        ];

        textareas.forEach(({ element, counter, maxLength }) => {
            if (element && counter) {
                element.addEventListener('input', function() {
                    updateCharCount(this, counter, maxLength);
                });
                // Initial count
                updateCharCount(element, counter, maxLength);
            }
        });

        // Real-time preview
        const previewElements = {
            nama: document.getElementById('previewNama'),
            kategori: document.getElementById('previewKategori'),
            kategoriEvent: document.getElementById('previewKategoriEvent'),
            tanggal: document.getElementById('previewTanggal'),
            lokasi: document.getElementById('previewLokasi'),
            hargaReguler: document.getElementById('previewHargaReguler'),
            hargaPremium: document.getElementById('previewHargaPremium'),
            status: document.getElementById('previewStatus')
        };

        const formInputs = {
            nama: document.getElementById('nama'),
            kategori: document.getElementById('kategori'),
            kategori_id: document.getElementById('kategori_id'),
            tanggal: document.getElementById('tanggal'),
            lokasi: document.getElementById('lokasi'),
            harga_reguler: document.getElementById('harga_reguler'),
            harga_premium: document.getElementById('harga_premium'),
            status: document.getElementById('status')
        };

        // Update preview on input change
        Object.keys(formInputs).forEach(key => {
            const input = formInputs[key];
            if (input) {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            }
        });

        function updatePreview() {
            // Nama
            previewElements.nama.textContent = formInputs.nama.value || '-';
            
            // Kategori Lomba
            if (formInputs.kategori.value) {
                const kategoriText = formInputs.kategori.options[formInputs.kategori.selectedIndex]?.text || '-';
                previewElements.kategori.textContent = kategoriText.split(' (')[0] || '-';
            } else {
                previewElements.kategori.textContent = '-';
            }
            
            // Kategori Event
            if (formInputs.kategori_id && formInputs.kategori_id.value) {
                const kategoriEventText = formInputs.kategori_id.options[formInputs.kategori_id.selectedIndex]?.text || '-';
                previewElements.kategoriEvent.textContent = kategoriEventText;
            } else {
                previewElements.kategoriEvent.textContent = '-';
            }
            
            // Tanggal
            if (formInputs.tanggal.value) {
                const date = new Date(formInputs.tanggal.value);
                previewElements.tanggal.textContent = date.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } else {
                previewElements.tanggal.textContent = '-';
            }
            
            // Lokasi
            previewElements.lokasi.textContent = formInputs.lokasi.value || '-';
            
            // Harga
            if (formInputs.harga_reguler.value) {
                const hargaReguler = parseInt(formInputs.harga_reguler.value) || 0;
                previewElements.hargaReguler.textContent = 'Rp ' + formatNumber(hargaReguler);
            } else {
                previewElements.hargaReguler.textContent = 'Rp 0';
            }
            
            if (formInputs.harga_premium.value) {
                const hargaPremium = parseInt(formInputs.harga_premium.value) || 0;
                previewElements.hargaPremium.textContent = 'Rp ' + formatNumber(hargaPremium);
            } else {
                previewElements.hargaPremium.textContent = 'Rp 0';
            }
            
            // Status
            if (formInputs.status.value) {
                const statusText = formInputs.status.options[formInputs.status.selectedIndex]?.text || '-';
                previewElements.status.textContent = statusText;
                
                // Update color based on status
                const status = formInputs.status.value;
                previewElements.status.className = 'px-3 py-1 text-xs font-medium rounded-full ';
                
                if (status === 'mendatang') {
                    previewElements.status.className += 'bg-blue-100 text-blue-800';
                } else if (status === 'selesai') {
                    previewElements.status.className += 'bg-green-100 text-green-800';
                } else if (status === 'dibatalkan') {
                    previewElements.status.className += 'bg-red-100 text-red-800';
                } else {
                    previewElements.status.className += 'bg-gray-100 text-gray-800';
                }
            }
        }

        function formatNumber(num) {
            return parseInt(num || 0).toLocaleString('id-ID');
        }

        // Form validation and submission
        const eventForm = document.getElementById('eventForm');
        const submitBtn = document.getElementById('submitBtn');

        if (eventForm && submitBtn) {
            eventForm.addEventListener('submit', function(e) {
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                submitBtn.disabled = true;
                
                // Validate required fields
                const requiredFields = ['nama', 'kategori', 'tanggal', 'lokasi', 'deskripsi', 'harga_reguler'];
                const emptyFields = [];
                
                requiredFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field && !field.value.trim()) {
                        emptyFields.push(field);
                    }
                });
                
                if (emptyFields.length > 0) {
                    e.preventDefault();
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                    submitBtn.disabled = false;
                    
                    showToast('error', 'Mohon lengkapi semua field yang wajib diisi!');
                    emptyFields[0].focus();
                    return false;
                }
                
                // Validate date not in the past
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const eventDate = new Date(formInputs.tanggal.value);
                
                if (eventDate < today) {
                    const confirmPastDate = confirm('Tanggal event sudah lewat. Apakah Anda yakin ingin melanjutkan?');
                    if (!confirmPastDate) {
                        e.preventDefault();
                        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                        submitBtn.disabled = false;
                        formInputs.tanggal.focus();
                        return false;
                    }
                }
                
                // Validate harga premium > reguler if both exist
                const hargaReguler = parseInt(formInputs.harga_reguler.value) || 0;
                const hargaPremium = parseInt(formInputs.harga_premium.value) || 0;
                
                if (hargaPremium > 0 && hargaPremium <= hargaReguler) {
                    e.preventDefault();
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                    submitBtn.disabled = false;
                    showToast('error', 'Harga premium harus lebih tinggi dari harga reguler!');
                    formInputs.harga_premium.focus();
                    return false;
                }
                
                // Submit form
                return true;
            });
        }

        // Toast notification function
        function showToast(type, message) {
            // Remove existing toast
            const existingToast = document.querySelector('.custom-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `custom-toast fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            if (type === 'error') {
                toast.classList.add('bg-red-500', 'text-white');
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                        <div>
                            <div class="font-semibold">Error</div>
                            <div>${message}</div>
                        </div>
                    </div>
                `;
            } else if (type === 'success') {
                toast.classList.add('bg-green-500', 'text-white');
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-xl"></i>
                        <div>
                            <div class="font-semibold">Berhasil</div>
                            <div>${message}</div>
                        </div>
                    </div>
                `;
            }
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set default date to tomorrow
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const tanggalInput = document.getElementById('tanggal');
            if (tanggalInput && !tanggalInput.value) {
                tanggalInput.value = tomorrow.toISOString().split('T')[0];
            }
            
            // Set default close registration date to tomorrow
            const tutupInput = document.getElementById('pendaftaran_ditutup');
            if (tutupInput && !tutupInput.value) {
                tutupInput.value = tomorrow.toISOString().split('T')[0];
            }
            
            // Initialize preview
            updatePreview();
        });
    </script>
</body>
</html>