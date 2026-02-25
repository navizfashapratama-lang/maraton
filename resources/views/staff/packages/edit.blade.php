<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Paket - Staff Area</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-active { @apply bg-white/20 border-l-4 border-white; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-indigo-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <i class="fas fa-running text-2xl mr-2"></i>
                    <span class="font-bold text-xl tracking-tight">Marathon System</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm bg-indigo-500 px-3 py-1 rounded-full">Staff Area</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-edit text-indigo-600 mr-2"></i>Edit Paket Layanan
                </h1>
                <p class="text-gray-500 text-sm mt-1">Perbarui informasi paket pendaftaran event marathon.</p>
            </div>
            <a href="{{ route('staff.packages.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        @if(session('success'))
        <div id="alert-success" class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm transition-opacity duration-500">
            <i class="fas fa-check-circle mr-3"></i>
            <span class="flex-grow">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div id="alert-error" class="mb-6 flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm transition-opacity duration-500">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span class="flex-grow">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 md:p-8">
                <form action="{{ route('staff.packages.update', $package->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-2">
                            <label for="lomba_id" class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i> Pilih Event *
                            </label>
                            <select id="lomba_id" name="lomba_id" required 
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                <option value="">-- Pilih Event --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('lomba_id', $package->lomba_id) == $event->id ? 'selected' : '' }}>
                                        {{ $event->nama }} ({{ date('d/m/Y', strtotime($event->tanggal)) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="nama" class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-tag mr-2 text-indigo-500"></i> Nama Paket *
                            </label>
                            <input type="text" id="nama" name="nama" required 
                                   value="{{ old('nama', $package->nama) }}"
                                   placeholder="Contoh: Paket Premium / Paket Hemat"
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="harga" class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-money-bill-wave mr-2 text-indigo-500"></i> Harga (Rp) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" id="harga" name="harga" required min="0" step="1000"
                                       value="{{ old('harga', $package->harga) }}"
                                       class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium">
                            </div>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <label class="text-sm font-bold text-gray-800 uppercase tracking-wider block mb-4 border-b pb-2">
                                Fasilitas yang Termasuk
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                
                                <label class="relative flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 transition-all group">
                                    <input type="checkbox" name="termasuk_race_kit" value="1" {{ old('termasuk_race_kit', $package->termasuk_race_kit) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-gift text-green-500 mr-2 group-hover:scale-110 transition-transform"></i> Race Kit
                                    </span>
                                </label>

                                <label class="relative flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 transition-all group">
                                    <input type="checkbox" name="termasuk_medali" value="1" {{ old('termasuk_medali', $package->termasuk_medali) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-medal text-yellow-500 mr-2 group-hover:scale-110 transition-transform"></i> Medali
                                    </span>
                                </label>

                                <label class="relative flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 transition-all group">
                                    <input type="checkbox" name="termasuk_kaos" value="1" {{ old('termasuk_kaos', $package->termasuk_kaos) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-tshirt text-blue-500 mr-2 group-hover:scale-110 transition-transform"></i> Kaos
                                    </span>
                                </label>

                                <label class="relative flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 transition-all group">
                                    <input type="checkbox" name="termasuk_sertifikat" value="1" {{ old('termasuk_sertifikat', $package->termasuk_sertifikat) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-certificate text-indigo-500 mr-2 group-hover:scale-110 transition-transform"></i> Sertifikat
                                    </span>
                                </label>

                                <label class="relative flex items-center p-3 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 transition-all group">
                                    <input type="checkbox" name="termasuk_snack" value="1" {{ old('termasuk_snack', $package->termasuk_snack) ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700 flex items-center">
                                        <i class="fas fa-utensils text-orange-500 mr-2 group-hover:scale-110 transition-transform"></i> Snack
                                    </span>
                                </label>

                            </div>
                        </div>

                        <div class="md:col-span-2 pt-6 border-t mt-4 flex flex-col md:flex-row gap-3 justify-end">
                            <a href="{{ route('staff.packages.index') }}" 
                               class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-center">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 shadow-md hover:shadow-lg transition-all text-center">
                                <i class="fas fa-save mr-2"></i> Update Paket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100">
                <div class="flex items-center text-indigo-700 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span class="font-bold">Status Data</span>
                </div>
                <div class="space-y-1">
                    <p class="text-sm text-gray-600 italic">ID Paket: #{{ $package->id }}</p>
                    <p class="text-sm text-gray-600 italic">Dibuat: {{ date('d M Y H:i', strtotime($event->created_at)) }}</p>
                    <p class="text-sm text-gray-600 italic">Terakhir Update: {{ date('d M Y H:i', strtotime($package->updated_at)) }}</p>
                </div>
            </div>

            <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center text-gray-800 mb-4">
                    <i class="fas fa-box-open text-indigo-600 mr-2"></i>
                    <span class="font-bold">Ringkasan Fasilitas Saat Ini</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @php
                        $facilities = [
                            ['Race Kit', $package->termasuk_race_kit, 'bg-green-100 text-green-700'],
                            ['Medali', $package->termasuk_medali, 'bg-yellow-100 text-yellow-700'],
                            ['Kaos', $package->termasuk_kaos, 'bg-blue-100 text-blue-700'],
                            ['Sertifikat', $package->termasuk_sertifikat, 'bg-indigo-100 text-indigo-700'],
                            ['Snack', $package->termasuk_snack, 'bg-orange-100 text-orange-700']
                        ];
                    @endphp

                    @foreach($facilities as $f)
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $f[1] ? $f[2] : 'bg-gray-100 text-gray-400 line-through' }}">
                            {{ $f[0] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('#alert-success, #alert-error');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Simple validation for price
        document.getElementById('harga').addEventListener('input', function(e) {
            if (this.value < 0) this.value = 0;
        });
    </script>
</body>
</html>