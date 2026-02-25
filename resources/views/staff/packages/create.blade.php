<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Paket - Staff Area</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-indigo-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <i class="fas fa-running text-2xl mr-2"></i>
                    <span class="font-bold text-xl tracking-tight">Marathon System</span>
                </div>
                <div class="flex items-center space-x-4 text-sm font-medium">
                    <a href="{{ route('staff.dashboard') }}" class="hover:bg-indigo-500 px-3 py-2 rounded-lg transition">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <span class="p-2 bg-indigo-100 rounded-lg mr-3">
                        <i class="fas fa-box-plus text-indigo-600"></i>
                    </span>
                    Tambah Paket Baru
                </h1>
                <p class="text-gray-500 text-sm mt-1 ml-12">Buat paket pendaftaran baru untuk event yang tersedia.</p>
            </div>
            <a href="{{ route('staff.packages.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-6 md:p-8">
                <form action="{{ route('staff.packages.store') }}" method="POST" id="formPaket">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="lomba_id" class="text-sm font-semibold text-gray-700 block">Event <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select class="w-full pl-4 pr-10 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none transition-all" 
                                        id="lomba_id" name="lomba_id" required>
                                    <option value="">Pilih Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ old('lomba_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->nama }} ({{ date('d/m/Y', strtotime($event->tanggal)) }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Pilih event yang akan dikaitkan dengan paket ini</p>
                        </div>

                        <div class="space-y-2">
                            <label for="nama" class="text-sm font-semibold text-gray-700 block">Nama Paket <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" 
                                   id="nama" name="nama" required value="{{ old('nama') }}" 
                                   placeholder="Contoh: Paket Reguler, Paket Premium">
                        </div>

                        <div class="space-y-2">
                            <label for="harga" class="text-sm font-semibold text-gray-700 block">Harga (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                                <input type="number" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium" 
                                       id="harga" name="harga" required value="{{ old('harga') }}" min="0" step="1000">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Masukkan angka tanpa titik atau koma</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="text-sm font-bold text-gray-800 uppercase tracking-wider block mb-4 border-b pb-2">Fasilitas yang Termasuk</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                
                                <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                                    <input type="checkbox" name="termasuk_race_kit" value="1" {{ old('termasuk_race_kit') ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="fas fa-gift text-green-500 mr-1 group-hover:scale-110 transition"></i> Race Kit
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                                    <input type="checkbox" name="termasuk_medali" value="1" {{ old('termasuk_medali') ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="fas fa-medal text-yellow-500 mr-1 group-hover:scale-110 transition"></i> Medali
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                                    <input type="checkbox" name="termasuk_kaos" value="1" {{ old('termasuk_kaos') ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="fas fa-tshirt text-blue-500 mr-1 group-hover:scale-110 transition"></i> Kaos
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                                    <input type="checkbox" name="termasuk_sertifikat" value="1" {{ old('termasuk_sertifikat', 1) ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="fas fa-certificate text-indigo-500 mr-1 group-hover:scale-110 transition"></i> Sertifikat
                                    </span>
                                </label>

                                <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition-all group">
                                    <input type="checkbox" name="termasuk_snack" value="1" {{ old('termasuk_snack', 1) ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        <i class="fas fa-utensils text-red-500 mr-1 group-hover:scale-110 transition"></i> Snack
                                    </span>
                                </label>

                            </div>
                        </div>

                        <div class="md:col-span-2 pt-6 border-t mt-4 flex flex-col md:flex-row gap-3 justify-end">
                            <a href="{{ route('staff.packages.index') }}" 
                               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-center order-2 md:order-1">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all text-center order-1 md:order-2">
                                <i class="fas fa-save mr-2"></i> Simpan Paket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-100 shadow-sm">
                <h5 class="font-bold text-indigo-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Ketentuan Paket
                </h5>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-indigo-500 mt-1 mr-3"></i>
                        <span class="text-sm text-gray-700"><strong>Race Kit:</strong> Berisi nomor BIB, timing chip, dan tas kecil.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-indigo-500 mt-1 mr-3"></i>
                        <span class="text-sm text-gray-700"><strong>Medali:</strong> Finishing medal eksklusif saat mencapai garis finish.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-indigo-500 mt-1 mr-3"></i>
                        <span class="text-sm text-gray-700"><strong>Sertifikat:</strong> E-Sertifikat resmi tanda partisipasi event.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                <h5 class="font-bold text-blue-800 mb-4 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i> Tips Cepat
                </h5>
                <p class="text-sm text-gray-600 leading-relaxed italic">
                    "Pastikan harga yang dimasukkan sudah termasuk biaya pajak atau administrasi lainnya. Paket yang telah disimpan dapat diubah kembali melalui menu Edit di daftar paket."
                </p>
            </div>
        </div>
    </div>

    <script>
        // Format harga input agar hanya angka
        document.getElementById('harga').addEventListener('input', function(e) {
            let value = e.target.value;
            if (value < 0) e.target.value = 0;
        });

        // Form validation
        document.getElementById('formPaket').addEventListener('submit', function(e) {
            const harga = document.getElementById('harga').value;
            if (harga < 0) {
                e.preventDefault();
                alert('Harga tidak boleh negatif');
            }
        });
    </script>
</body>
</html>