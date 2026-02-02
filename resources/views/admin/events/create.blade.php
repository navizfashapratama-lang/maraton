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
        
        /* Smooth transitions */
        .smooth-transition {
            transition: all 0.3s ease;
        }
        
        /* Category Card */
        .category-card {
            transition: all 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .category-card.selected {
            border-width: 3px;
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        /* Tag Styling */
        .category-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .category-tag:hover {
            transform: scale(1.05);
        }
        
        .category-tag.selected {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            
            @php
                // Pastikan $kategoris ada dan bisa di-loop
                $kategorisAvailable = false;
                if (isset($kategoris) && (is_array($kategoris) || ($kategoris instanceof \Illuminate\Support\Collection))) {
                    $kategorisAvailable = true;
                }
            @endphp
            
            @if($kategorisAvailable && $kategoris->count() > 0)
                @foreach($kategoris as $kategori)
                    @php
                        // Handle object atau array
                        $id = is_object($kategori) ? $kategori->id : ($kategori['id'] ?? null);
                        $nama = is_object($kategori) ? $kategori->nama_kategori : ($kategori['nama_kategori'] ?? '');
                        $warna = is_object($kategori) ? ($kategori->warna ?? '#4ECDC4') : ($kategori['warna'] ?? '#4ECDC4');
                        $ikon = is_object($kategori) ? ($kategori->ikon ?? 'fa-running') : ($kategori['ikon'] ?? 'fa-running');
                    @endphp
                    
                    @if($id && $nama)
                        <option value="{{ $id }}" 
                            {{ old('kategori_id') == $id ? 'selected' : '' }}
                            data-color="{{ $warna }}"
                            data-icon="{{ $ikon }}">
                            {{ $nama }}
                        </option>
                    @endif
                @endforeach
            @else
                <option value="">Belum ada kategori event</option>
            @endif
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-tags text-gray-400"></i>
        </div>
    </div>
    
    <!-- Debug info (opsional) -->
    @if(config('app.debug') && $kategorisAvailable)
        <div class="mt-2 text-xs text-gray-500">
            Ditemukan {{ $kategoris->count() }} kategori
        </div>
    @endif
    
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
                                    
                                    <!-- Waktu Mulai -->
                                    <div>
                                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">Waktu Mulai</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-clock text-gray-400"></i>
                                            </div>
                                            <input type="time" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai', '06:00') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                        </div>
                                        @error('waktu_mulai')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Durasi -->
                                    <div>
                                        <label for="durasi" class="block text-sm font-medium text-gray-700 mb-2">Durasi Event (Jam)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-hourglass-half text-gray-400"></i>
                                            </div>
                                            <input type="number" id="durasi" name="durasi" value="{{ old('durasi', '3') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                   min="1" max="24" placeholder="3">
                                        </div>
                                        @error('durasi')
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
                                    
                                    <!-- Map Link -->
                                    <div>
                                        <label for="map_link" class="block text-sm font-medium text-gray-700 mb-2">Link Google Maps (Opsional)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-link text-gray-400"></i>
                                            </div>
                                            <input type="url" id="map_link" name="map_link" value="{{ old('map_link') }}" 
                                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                                   placeholder="https://maps.google.com/...">
                                        </div>
                                        @error('map_link')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <!-- Deskripsi Lokasi -->
                                    <div>
                                        <label for="deskripsi_lokasi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Lokasi (Opsional)</label>
                                        <textarea id="deskripsi_lokasi" name="deskripsi_lokasi" rows="3" 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-none custom-scrollbar"
                                                  placeholder="Detail titik kumpul, akses transportasi, parkir, dll.">{{ old('deskripsi_lokasi') }}</textarea>
                                        <div class="flex justify-between items-center mt-1">
                                            <div class="text-xs text-gray-500">Maksimal 500 karakter</div>
                                            <div id="charCountLokasi" class="text-xs text-gray-500">0/500</div>
                                        </div>
                                        @error('deskripsi_lokasi')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section 3: Pricing Information -->
                            <div class="border-b border-gray-200 pb-8">
                                <div class="flex items-center mb-8">
                                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mr-3 shadow-md">
                                        <i class="fas fa-tag text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-800">Paket & Harga Event</h3>
                                        <p class="text-sm text-gray-600 mt-1">Pilih jenis event dan tentukan paket yang tersedia</p>
                                    </div>
                                </div>
                                
                                <!-- Jenis Event -->
                                <div class="mb-10">
                                    <label class="block text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide required">
                                        <i class="fas fa-calendar-alt mr-2"></i>Jenis Event
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Event Berbayar -->
                                        <label class="relative">
                                            <input type="radio" name="event_type" value="paid" 
                                                   class="hidden peer"
                                                   id="event_paid"
                                                   @if(old('event_type', 'paid') == 'paid') checked @endif
                                                   onchange="handleEventTypeChange()">
                                            <div class="cursor-pointer border-2 border-gray-300 rounded-xl p-5 text-center peer-checked:border-blue-500 peer-checked:bg-gradient-to-r peer-checked:from-blue-50 peer-checked:to-white transition-all duration-300 hover:border-blue-400 hover:shadow-md">
                                                <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                                    <i class="fas fa-money-bill-wave text-white text-xl"></i>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-lg mb-2">Event Berbayar</h4>
                                                <p class="text-sm text-gray-600 mb-3">Peserta membayar untuk mengikuti event dengan fasilitas lengkap</p>
                                                <div class="inline-flex items-center text-sm text-blue-600 font-medium">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    <span>Paket Reguler & Premium tersedia</span>
                                                </div>
                                            </div>
                                            <div class="absolute top-3 right-3">
                                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all">
                                                    <i class="fas fa-check text-white text-xs transform scale-0 peer-checked:scale-100 transition-transform duration-300"></i>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <!-- Event Gratis -->
                                        <label class="relative">
                                            <input type="radio" name="event_type" value="free" 
                                                   class="hidden peer"
                                                   id="event_free"
                                                   @if(old('event_type') == 'free') checked @endif
                                                   onchange="handleEventTypeChange()">
                                            <div class="cursor-pointer border-2 border-gray-300 rounded-xl p-5 text-center peer-checked:border-green-500 peer-checked:bg-gradient-to-r peer-checked:from-green-50 peer-checked:to-white transition-all duration-300 hover:border-green-400 hover:shadow-md">
                                                <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-lg">
                                                    <i class="fas fa-gift text-white text-xl"></i>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-lg mb-2">Event Gratis</h4>
                                                <p class="text-sm text-gray-600 mb-3">Tidak ada biaya pendaftaran, terbuka untuk umum</p>
                                                <div class="inline-flex items-center text-sm text-green-600 font-medium">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    <span>Tanpa biaya, fasilitas dasar</span>
                                                </div>
                                            </div>
                                            <div class="absolute top-3 right-3">
                                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center transition-all">
                                                    <i class="fas fa-check text-white text-xs transform scale-0 peer-checked:scale-100 transition-transform duration-300"></i>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Container untuk Paket Berbayar -->
                                <div id="paid-event-container" class="smooth-transition">
                                    <div class="mb-8">
                                        <div class="flex items-center justify-between mb-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-1 uppercase tracking-wide required">
                                                    <i class="fas fa-boxes mr-2"></i>Pilih Jenis Paket
                                                </label>
                                                <p class="text-sm text-gray-500">Pilih paket yang sesuai untuk event berbayar</p>
                                            </div>
                                            <div class="text-xs font-medium px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                                Hanya untuk Event Berbayar
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Paket Reguler -->
                                            <label class="relative">
                                                <input type="radio" name="package_type" value="regular" 
                                                       class="hidden peer" 
                                                       id="package_regular"
                                                       @if(old('package_type', 'regular') == 'regular') checked @endif
                                                       onchange="updateFacilities()">
                                                <div class="cursor-pointer border-2 border-gray-300 rounded-xl p-6 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-100 peer-checked:bg-gradient-to-br peer-checked:from-blue-50 peer-checked:to-white transition-all duration-300 hover:border-blue-400 hover:shadow-lg">
                                                    <div class="absolute -top-2 left-4">
                                                        <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full shadow">
                                                            POPULER
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-4">
                                                                <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3 shadow">
                                                                    <i class="fas fa-walking text-white"></i>
                                                                </div>
                                                                <div>
                                                                    <h4 class="font-bold text-gray-800 text-lg">Paket Reguler</h4>
                                                                    <p class="text-xs text-gray-500 font-medium">Standar • Untuk Peserta Umum</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <p class="text-sm text-gray-600 mb-5">Paket dasar dengan fasilitas esensial untuk pengalaman lomba yang baik</p>
                                                            
                                                            <div class="space-y-3 mb-5">
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Race Kit Lengkap</span>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Sertifikat Digital</span>
                                                                </div>
                                                                <div class="flex items-center opacity-60">
                                                                    <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-times text-gray-400 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm text-gray-500">Kaos Event</span>
                                                                </div>
                                                                <div class="flex items-center opacity-60">
                                                                    <div class="w-5 h-5 bg-gray-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-times text-gray-400 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm text-gray-500">Medali Finisher</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="text-right ml-6 border-l border-gray-200 pl-6">
                                                            <div class="mb-4">
                                                                <label class="block text-sm font-medium text-gray-700 mb-2 required">Harga Paket</label>
                                                                <div class="relative">
                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span class="text-gray-500 font-medium">Rp</span>
                                                                    </div>
                                                                    <input type="number" id="regular_price" name="regular_price" 
                                                                           value="{{ old('regular_price') }}" 
                                                                           class="w-40 pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm"
                                                                           placeholder="150.000" min="10000" step="1000"
                                                                           oninput="formatPrice(this)">
                                                                    <div class="absolute right-3 top-3">
                                                                        <span class="text-xs text-gray-400">IDR</span>
                                                                    </div>
                                                                </div>
                                                                <p class="mt-2 text-xs text-gray-500">Minimal Rp 10.000</p>
                                                            </div>
                                                            @error('regular_price')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="absolute top-4 right-4">
                                                    <div class="w-7 h-7 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all duration-300 shadow">
                                                        <i class="fas fa-check text-white text-sm transform scale-0 peer-checked:scale-100 transition-transform duration-300"></i>
                                                    </div>
                                                </div>
                                            </label>
                                            
                                            <!-- Paket Premium -->
                                            <label class="relative">
                                                <input type="radio" name="package_type" value="premium" 
                                                       class="hidden peer" 
                                                       id="package_premium"
                                                       @if(old('package_type') == 'premium') checked @endif
                                                       onchange="updateFacilities()">
                                                <div class="cursor-pointer border-2 border-gray-300 rounded-xl p-6 peer-checked:border-yellow-500 peer-checked:ring-2 peer-checked:ring-yellow-100 peer-checked:bg-gradient-to-br peer-checked:from-yellow-50 peer-checked:to-white transition-all duration-300 hover:border-yellow-400 hover:shadow-lg">
                                                    <div class="absolute -top-2 left-4">
                                                        <span class="px-3 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-xs font-bold rounded-full shadow">
                                                            PREMIUM
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-start">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-4">
                                                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-3 shadow">
                                                                    <i class="fas fa-crown text-white"></i>
                                                                </div>
                                                                <div>
                                                                    <h4 class="font-bold text-gray-800 text-lg">Paket Premium</h4>
                                                                    <p class="text-xs text-gray-500 font-medium">Eksklusif • Pengalaman Terbaik</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <p class="text-sm text-gray-600 mb-5">Paket lengkap dengan semua fasilitas untuk pengalaman lomba yang tak terlupakan</p>
                                                            
                                                            <div class="space-y-3 mb-5">
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Race Kit Lengkap</span>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Sertifikat Digital & Cetak</span>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Kaos Event Premium</span>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                                                        <i class="fas fa-check text-green-600 text-xs"></i>
                                                                    </div>
                                                                    <span class="text-sm font-medium text-gray-700">Medali Finisher Eksklusif</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="text-right ml-6 border-l border-gray-200 pl-6">
                                                            <div class="mb-4">
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Paket</label>
                                                                <div class="relative">
                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span class="text-gray-500 font-medium">Rp</span>
                                                                    </div>
                                                                    <input type="number" id="premium_price" name="premium_price" 
                                                                           value="{{ old('premium_price') }}" 
                                                                           class="w-40 pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm"
                                                                           placeholder="250.000" min="0" step="1000"
                                                                           oninput="formatPrice(this)">
                                                                    <div class="absolute right-3 top-3">
                                                                        <span class="text-xs text-gray-400">IDR</span>
                                                                    </div>
                                                                </div>
                                                                <p class="mt-2 text-xs text-gray-500">Opsional, minimal Rp 10.000</p>
                                                            </div>
                                                            @error('premium_price')
                                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="absolute top-4 right-4">
                                                    <div class="w-7 h-7 border-2 border-gray-300 rounded-full peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center transition-all duration-300 shadow">
                                                        <i class="fas fa-check text-white text-sm transform scale-0 peer-checked:scale-100 transition-transform duration-300"></i>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Container untuk Event Gratis -->
                                <div id="free-event-container" class="hidden smooth-transition">
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6 mb-6 shadow-sm">
                                        <div class="flex items-start">
                                            <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mr-4 flex-shrink-0 shadow">
                                                <i class="fas fa-info-circle text-white text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <h4 class="font-bold text-green-800 text-lg mb-1">Informasi Event Gratis</h4>
                                                        <p class="text-green-700 text-sm">Event ini tidak memungut biaya pendaftaran. Peserta dapat mengikuti tanpa membayar.</p>
                                                    </div>
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">
                                                        GRATIS
                                                    </span>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                                    <div class="bg-white rounded-lg p-3 border border-green-100">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                                <i class="fas fa-check text-green-600"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-gray-800 text-sm">Tanpa Biaya</div>
                                                                <div class="text-xs text-gray-500">Pendaftaran gratis</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg p-3 border border-green-100">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                                <i class="fas fa-users text-green-600"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-gray-800 text-sm">Terbuka Umum</div>
                                                                <div class="text-xs text-gray-500">Untuk semua peserta</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg p-3 border border-green-100">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                                <i class="fas fa-award text-green-600"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-gray-800 text-sm">Sertifikat Partisipasi</div>
                                                                <div class="text-xs text-gray-500">Digital</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="is_free" id="is_free" value="0">
                                                <input type="hidden" name="regular_price" id="free_regular_price" value="0">
                                                <input type="hidden" name="premium_price" id="free_premium_price" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kuota Peserta & Informasi Fasilitas -->
                                <div class="mt-10 pt-8 border-t border-gray-200">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        <!-- Kuota Peserta -->
                                        <div>
                                            <div class="flex items-center mb-4">
                                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-users text-purple-600"></i>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kuota Peserta</label>
                                                    <p class="text-xs text-gray-500">Batasan jumlah peserta yang dapat mendaftar</p>
                                                </div>
                                            </div>
                                            
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <i class="fas fa-user-friends text-gray-400"></i>
                                                </div>
                                                <input type="number" id="kuota_peserta" name="kuota_peserta" value="{{ old('kuota_peserta') }}" 
                                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white shadow-sm transition duration-200"
                                                       placeholder="Misal: 1000" min="0">
                                            </div>
                                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                                <span>Kosongkan jika kuota tidak terbatas</span>
                                            </div>
                                            @error('kuota_peserta')
                                                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-sm text-red-600 flex items-center">
                                                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                                    </p>
                                                </div>
                                            @enderror
                                        </div>
                                        
                                        <!-- Informasi Fasilitas Terpilih -->
                                        <div>
                                            <div class="flex items-center mb-4">
                                                <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-clipboard-check text-cyan-600"></i>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Fasilitas yang Disertakan</label>
                                                    <p class="text-xs text-gray-500">Fasilitas yang akan didapatkan peserta</p>
                                                </div>
                                            </div>
                                            
                                            <div id="fasilitas-info" class="p-5 bg-gradient-to-r from-gray-50 to-white rounded-xl border border-gray-200 shadow-sm min-h-[120px]">
                                                <div class="flex flex-col items-center justify-center h-full text-center">
                                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                        <i class="fas fa-box-open text-gray-400"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500 font-medium">Pilih jenis event terlebih dahulu</p>
                                                    <p class="text-xs text-gray-400 mt-1">Fasilitas akan ditampilkan sesuai pilihan Anda</p>
                                                </div>
                                            </div>
                                            
                                            <div id="fasilitas-status" class="mt-3 text-xs text-gray-500 flex items-center">
                                                <i class="fas fa-circle text-gray-300 mr-2 text-xs"></i>
                                                <span>Menunggu pilihan...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Inputs for Form Submission -->
                            <div id="fasilitas-inputs" class="hidden"></div>

                            <!-- Section 4: Event Details -->
                            <div class="pb-8">
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
                                                  placeholder="Jelaskan detail event, rute lomba, fasilitas, hadiah, dll." required>{{ old('deskripsi') }}</textarea>
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
                                    
                                   <!-- Status Event - Auto Set to "Mendatang" -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-3">Status Event</label>
    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-blue-500 rounded-full flex items-center justify-center text-white mr-4">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div>
                <div class="flex items-center">
                    <span class="font-bold text-gray-800 text-lg">Status: Mendatang</span>
                    <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                        OTOTMATIS
                    </span>
                </div>
                <p class="text-sm text-gray-600 mt-1">
                    Event akan otomatis berstatus <span class="font-medium text-blue-600">"Mendatang"</span> 
                    ketika ditambahkan. Status akan berubah otomatis sesuai tanggal event.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Hidden input untuk status -->
    <input type="hidden" name="status" value="mendatang">
    
    <!-- Info timeline status -->
    <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-center text-sm text-gray-600">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            <span>
                <span class="font-medium">Timeline Status Otomatis:</span> 
                Mendatang → Berlangsung (saat tanggal event) → Selesai (setelah event)
            </span>
        </div>
    </div>
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
                                    <p class="font-medium text-gray-800">Gambar/Poster</p>
                                    <p class="text-sm text-gray-600 mt-1">Ukuran maksimal 5MB, format JPG/PNG/GIF</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Harga</p>
                                    <p class="text-sm text-gray-600 mt-1">Harga premium harus lebih tinggi dari reguler</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Deskripsi</p>
                                    <p class="text-sm text-gray-600 mt-1">Jelaskan dengan detail untuk menarik peserta</p>
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
                                        <span id="previewHargaPremium" class="font-semibold text-purple-600 text-sm">Rp 0</span>
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
        const deskripsiLokasiInput = document.getElementById('deskripsi_lokasi');
        const ruteLombaInput = document.getElementById('rute_lomba');
        const syaratKetentuanInput = document.getElementById('syarat_ketentuan');
        const fasilitasInput = document.getElementById('fasilitas');
        
        const charCountDeskripsi = document.getElementById('charCountDeskripsi');
        const charCountLokasi = document.getElementById('charCountLokasi');
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
            { element: deskripsiLokasiInput, counter: charCountLokasi, maxLength: 500 },
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

        // Status radio button styling
        const statusOptions = document.querySelectorAll('.status-option');
        statusOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove all selected styles
                statusOptions.forEach(opt => {
                    opt.classList.remove('border-blue-500', 'bg-blue-50', 'border-green-500', 'bg-green-50', 'border-gray-500', 'bg-gray-50', 'border-red-500', 'bg-red-50');
                    opt.classList.add('border-gray-200', 'bg-gray-50');
                });
                
                // Add selected style based on value
                const value = this.getAttribute('data-value');
                if (value === 'mendatang') {
                    this.classList.remove('border-gray-200', 'bg-gray-50');
                    this.classList.add('border-blue-500', 'bg-blue-50');
                } else if (value === 'berlangsung') {
                    this.classList.remove('border-gray-200', 'bg-gray-50');
                    this.classList.add('border-green-500', 'bg-green-50');
                } else if (value === 'selesai') {
                    this.classList.remove('border-gray-200', 'bg-gray-50');
                    this.classList.add('border-gray-500', 'bg-gray-50');
                } else if (value === 'dibatalkan') {
                    this.classList.remove('border-gray-200', 'bg-gray-50');
                    this.classList.add('border-red-500', 'bg-red-50');
                }
                
                // Update preview
                updatePreview();
            });
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
            regular_price: document.getElementById('regular_price'),
            premium_price: document.getElementById('premium_price')
        };

        // Update preview on input change
        Object.keys(formInputs).forEach(key => {
            const input = formInputs[key];
            if (input) {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            }
        });

        // Also update for status radio buttons
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        // Category preview handling
        const kategoriSelect = document.getElementById('kategori_id');
        const categoryPreview = document.getElementById('categoryPreview');
        const categoryIcon = document.getElementById('categoryIcon');
        const categoryName = document.getElementById('categoryName');

        if (kategoriSelect) {
            kategoriSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const categoryText = selectedOption.text;
                    const categoryColor = selectedOption.getAttribute('data-color');
                    const categoryIconClass = selectedOption.getAttribute('data-icon');
                    
                    // Update preview
                    categoryName.textContent = categoryText;
                    categoryIcon.innerHTML = `<i class="${categoryIconClass}" style="color: ${categoryColor}"></i>`;
                    categoryPreview.classList.remove('hidden');
                    
                    // Update preview card
                    previewElements.kategoriEvent.textContent = categoryText;
                } else {
                    categoryPreview.classList.add('hidden');
                    previewElements.kategoriEvent.textContent = '-';
                }
            });
            
            // Trigger initial update
            if (kategoriSelect.value) {
                kategoriSelect.dispatchEvent(new Event('change'));
            }
        }

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
            
            // Harga - Check if event is free
            const eventType = document.querySelector('input[name="event_type"]:checked');
            if (eventType && eventType.value === 'free') {
                previewElements.hargaReguler.textContent = 'GRATIS';
                previewElements.hargaPremium.textContent = 'GRATIS';
            } else {
                // Berbayar event
                if (formInputs.regular_price.value) {
                    previewElements.hargaReguler.textContent = 'Rp ' + formatNumber(formInputs.regular_price.value);
                } else {
                    previewElements.hargaReguler.textContent = 'Rp 0';
                }
                
                if (formInputs.premium_price.value) {
                    previewElements.hargaPremium.textContent = 'Rp ' + formatNumber(formInputs.premium_price.value);
                } else {
                    previewElements.hargaPremium.textContent = 'Rp 0';
                }
            }
            
            // Status
            const selectedStatus = document.querySelector('input[name="status"]:checked');
            if (selectedStatus) {
                const statusValue = selectedStatus.value;
                const statusText = selectedStatus.closest('label').querySelector('.font-medium').textContent;
                
                // Update text and color
                previewElements.status.textContent = statusText;
                
                // Update color based on status
                previewElements.status.className = 'px-3 py-1 text-xs font-medium rounded-full';
                if (statusValue === 'mendatang') {
                    previewElements.status.classList.add('bg-blue-100', 'text-blue-800');
                } else if (statusValue === 'berlangsung') {
                    previewElements.status.classList.add('bg-green-100', 'text-green-800');
                } else if (statusValue === 'selesai') {
                    previewElements.status.classList.add('bg-gray-100', 'text-gray-800');
                } else if (statusValue === 'dibatalkan') {
                    previewElements.status.classList.add('bg-red-100', 'text-red-800');
                }
            }
        }

        function formatNumber(num) {
            return parseInt(num || 0).toLocaleString('id-ID');
        }

        // Handle event type change (paid vs free)
        function handleEventTypeChange() {
            const eventPaid = document.getElementById('event_paid');
            const eventFree = document.getElementById('event_free');
            const paidContainer = document.getElementById('paid-event-container');
            const freeContainer = document.getElementById('free-event-container');
            const isFreeInput = document.getElementById('is_free');
            const freeRegularPriceInput = document.getElementById('free_regular_price');
            const freePremiumPriceInput = document.getElementById('free_premium_price');
            const regularPriceInput = document.getElementById('regular_price');
            const premiumPriceInput = document.getElementById('premium_price');
            const fasilitasInfo = document.getElementById('fasilitas-info');
            const fasilitasStatus = document.getElementById('fasilitas-status');

            if (eventPaid.checked) {
                // Show paid event container
                paidContainer.classList.remove('hidden');
                freeContainer.classList.add('hidden');
                
                // Set event type to paid
                isFreeInput.value = '0';
                
                // Make price inputs required
                regularPriceInput.required = true;
                premiumPriceInput.required = false;
                
                // Clear hidden inputs for free event
                freeRegularPriceInput.value = '0';
                freePremiumPriceInput.value = '0';
                
                // Update facilities info
                updateFacilities();
                
                // Update status
                fasilitasStatus.innerHTML = `
                    <i class="fas fa-circle text-blue-500 mr-2 text-xs"></i>
                    <span class="text-blue-600 font-medium">Event Berbayar dipilih • Pilih paket untuk melihat fasilitas</span>
                `;
                
            } else if (eventFree.checked) {
                // Show free event container
                paidContainer.classList.add('hidden');
                freeContainer.classList.remove('hidden');
                
                // Set event type to free
                isFreeInput.value = '1';
                
                // Set prices to 0 in hidden inputs
                freeRegularPriceInput.value = '0';
                freePremiumPriceInput.value = '0';
                
                // Disable required validation for price inputs
                regularPriceInput.required = false;
                premiumPriceInput.required = false;
                
                // Clear visible price inputs (optional)
                regularPriceInput.value = '';
                premiumPriceInput.value = '';
                
                // Show free event facilities
                fasilitasInfo.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-award text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Sertifikat Partisipasi Digital</div>
                                <div class="text-sm text-gray-500">Bukti keikutsertaan dalam event</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-flag-checkered text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Akses Event Lengkap</div>
                                <div class="text-sm text-gray-500">Dapat mengikuti semua sesi lomba</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-user-check text-green-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Pendaftaran Gratis</div>
                                <div class="text-sm text-gray-500">Tanpa biaya apapun</div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Update status
                fasilitasStatus.innerHTML = `
                    <i class="fas fa-circle text-green-500 mr-2 text-xs"></i>
                    <span class="text-green-600 font-medium">Event Gratis dipilih • Fasilitas dasar tersedia</span>
                `;
            }
            
            // Update preview
            updatePreview();
        }

        // Update facilities based on package selection (for paid events)
        function updateFacilities() {
            const packageRegular = document.getElementById('package_regular');
            const packagePremium = document.getElementById('package_premium');
            const fasilitasInfo = document.getElementById('fasilitas-info');
            const fasilitasStatus = document.getElementById('fasilitas-status');

            if (packageRegular && packageRegular.checked) {
                fasilitasInfo.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Race Kit Lengkap</div>
                                <div class="text-sm text-gray-500">Paket perlengkapan lomba standar</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-file-certificate text-blue-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Sertifikat Digital</div>
                                <div class="text-sm text-gray-500">Sertifikat keikutsertaan dalam format digital</div>
                            </div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-3 mt-4">
                            <div class="flex items-center text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Paket Reguler - Cocok untuk peserta umum dengan budget terbatas</span>
                            </div>
                        </div>
                    </div>
                `;
                
                fasilitasStatus.innerHTML = `
                    <i class="fas fa-circle text-blue-500 mr-2 text-xs"></i>
                    <span class="text-blue-600 font-medium">Paket Reguler dipilih • Fasilitas dasar tersedia</span>
                `;
                
                // Make regular price required
                document.getElementById('regular_price').required = true;
                document.getElementById('premium_price').required = false;
                
            } else if (packagePremium && packagePremium.checked) {
                fasilitasInfo.innerHTML = `
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-box-open text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Race Kit Lengkap Premium</div>
                                <div class="text-sm text-gray-500">Paket perlengkapan lomba premium</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-certificate text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Sertifikat Digital & Cetak</div>
                                <div class="text-sm text-gray-500">Sertifikat dalam format digital dan cetak fisik</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-tshirt text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Kaos Event Premium</div>
                                <div class="text-sm text-gray-500">Kaos khusus dengan bahan dan design premium</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-medal text-yellow-600"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">Medali Finisher Eksklusif</div>
                                <div class="text-sm text-gray-500">Medali spesial untuk penyelesaian lomba</div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 mt-4">
                            <div class="flex items-center text-sm text-yellow-700">
                                <i class="fas fa-crown mr-2"></i>
                                <span>Paket Premium - Pengalaman lomba terbaik dengan fasilitas lengkap</span>
                            </div>
                        </div>
                    </div>
                `;
                
                fasilitasStatus.innerHTML = `
                    <i class="fas fa-circle text-yellow-500 mr-2 text-xs"></i>
                    <span class="text-yellow-600 font-medium">Paket Premium dipilih • Fasilitas lengkap tersedia</span>
                `;
                
                // Make premium price required
                document.getElementById('premium_price').required = true;
                document.getElementById('regular_price').required = false;
            }
        }

        // Format price input
        function formatPrice(input) {
            const value = input.value.replace(/\D/g, '');
            if (value) {
                const formatted = parseInt(value).toLocaleString('id-ID');
                input.value = formatted;
            }
            updatePreview();
        }

        // Form validation and submission
        const eventForm = document.getElementById('eventForm');
        const submitBtn = document.getElementById('submitBtn');

        if (eventForm && submitBtn) {
            eventForm.addEventListener('submit', function(e) {
                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
                submitBtn.disabled = true;
                
                // Get event type
                const eventType = document.querySelector('input[name="event_type"]:checked');
                
                // Validate event type is selected
                if (!eventType) {
                    e.preventDefault();
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                    submitBtn.disabled = false;
                    showToast('error', 'Pilih jenis event terlebih dahulu!');
                    return false;
                }
                
                // Validate required fields
                const requiredFields = ['nama', 'kategori', 'kategori_id', 'tanggal', 'lokasi'];
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
                
                // Validate paid event requirements
                if (eventType.value === 'paid') {
                    const packageType = document.querySelector('input[name="package_type"]:checked');
                    
                    // Validate package type is selected
                    if (!packageType) {
                        e.preventDefault();
                        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                        submitBtn.disabled = false;
                        showToast('error', 'Pilih jenis paket untuk event berbayar!');
                        return false;
                    }
                    
                    // Validate prices based on package type
                    if (packageType.value === 'regular') {
                        const regularPrice = document.getElementById('regular_price').value.replace(/\D/g, '');
                        if (!regularPrice || parseInt(regularPrice) < 10000) {
                            e.preventDefault();
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                            submitBtn.disabled = false;
                            showToast('error', 'Harga reguler minimal Rp 10.000!');
                            document.getElementById('regular_price').focus();
                            return false;
                        }
                    } else if (packageType.value === 'premium') {
                        const premiumPrice = document.getElementById('premium_price').value.replace(/\D/g, '');
                        if (!premiumPrice || parseInt(premiumPrice) < 10000) {
                            e.preventDefault();
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                            submitBtn.disabled = false;
                            showToast('error', 'Harga premium minimal Rp 10.000!');
                            document.getElementById('premium_price').focus();
                            return false;
                        }
                        
                        // Validate premium > regular if regular has value
                        const regularPrice = document.getElementById('regular_price').value.replace(/\D/g, '');
                        if (regularPrice && parseInt(premiumPrice) <= parseInt(regularPrice)) {
                            e.preventDefault();
                            submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                            submitBtn.disabled = false;
                            showToast('error', 'Harga premium harus lebih tinggi dari harga reguler!');
                            document.getElementById('premium_price').focus();
                            return false;
                        }
                    }
                }
                
                // Validate date not in the past for upcoming events
                const selectedStatus = document.querySelector('input[name="status"]:checked');
                if (selectedStatus && selectedStatus.value === 'mendatang') {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const eventDate = new Date(formInputs.tanggal.value);
                    
                    if (eventDate < today) {
                        e.preventDefault();
                        submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Event';
                        submitBtn.disabled = false;
                        showToast('error', 'Tanggal event mendatang tidak boleh di masa lalu!');
                        formInputs.tanggal.focus();
                        return false;
                    }
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
            // Set default date
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            const tanggalInput = document.getElementById('tanggal');
            if (tanggalInput && !tanggalInput.value) {
                tanggalInput.min = today.toISOString().split('T')[0];
                tanggalInput.value = tomorrow.toISOString().split('T')[0];
            }
            
            // Initialize event type handling
            handleEventTypeChange();
            
            // Initialize preview
            updatePreview();
            
            // Initialize status styling
            const initialStatus = document.querySelector('input[name="status"]:checked');
            if (initialStatus) {
                const statusOption = initialStatus.closest('.status-option');
                statusOption.click();
            }
            
            // Setup price formatting
            document.getElementById('regular_price')?.addEventListener('blur', function() {
                formatPrice(this);
            });
            
            document.getElementById('premium_price')?.addEventListener('blur', function() {
                formatPrice(this);
            });
            
            // Reset format on focus
            document.getElementById('regular_price')?.addEventListener('focus', function() {
                this.value = this.value.replace(/\D/g, '');
            });
            
            document.getElementById('premium_price')?.addEventListener('focus', function() {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    </script>
</body>
</html> 