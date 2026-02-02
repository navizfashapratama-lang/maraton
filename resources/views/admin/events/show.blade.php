<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event - {{ $event->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Event</h1>
                    <p class="text-gray-600">Informasi lengkap event marathon</p>
                </div>
                <a href="{{ route('admin.events.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $event->nama }}</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-tag text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Kategori:</strong> {{ $event->kategori }}
                                </span>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($event->tanggal)->format('d F Y') }}
                                </span>
                            </div>
                            
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Lokasi:</strong> {{ $event->lokasi }}
                                </span>
                            </div>
                            
                            <!-- PERBAIKAN: Menggunakan harga_reguler bukan harga_standar -->
                            <div class="flex items-center">
                                <i class="fas fa-money-bill text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Harga Reguler:</strong> Rp {{ number_format($event->harga_reguler ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <!-- PERBAIKAN: Tambah null coalescing operator -->
                            @if($event->harga_premium > 0)
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Harga Premium:</strong> Rp {{ number_format($event->harga_premium ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            @endif
                            
                            <!-- Tambah informasi lain dari database -->
                            @if($event->kuota_peserta)
                            <div class="flex items-center">
                                <i class="fas fa-users text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Kuota Peserta:</strong> {{ $event->kuota_peserta }} orang
                                </span>
                            </div>
                            @endif
                            
                            @if($event->pendaftaran_ditutup)
                            <div class="flex items-center">
                                <i class="fas fa-calendar-times text-gray-400 w-6"></i>
                                <span class="ml-2 text-gray-700">
                                    <strong>Pendaftaran Ditutup:</strong> {{ \Carbon\Carbon::parse($event->pendaftaran_ditutup)->format('d F Y') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <div class="mb-6">
                            <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold 
                                {{ $event->status == 'mendatang' ? 'bg-green-100 text-green-800' : 
                                   ($event->status == 'selesai' ? 'bg-gray-100 text-gray-800' : 
                                   ($event->status == 'dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                Status: {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        
                        <!-- Registrations Count -->
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <h3 class="font-semibold text-blue-800 mb-2">Statistik Pendaftaran</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ DB::table('pendaftaran')->where('id_lomba', $event->id)->count() }}
                                    </div>
                                    <div class="text-sm text-blue-500">Total Pendaftar</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ DB::table('pendaftaran')->where('id_lomba', $event->id)->where('status', 'disetujui')->count() }}
                                    </div>
                                    <div class="text-sm text-green-500">Disetujui</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Informasi Tambahan -->
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800 mb-2">Informasi Event</h3>
                            <ul class="space-y-1 text-sm text-yellow-700">
                                <li><strong>ID Event:</strong> #{{ $event->id }}</li>
                                <li><strong>Dibuat:</strong> {{ \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') }}</li>
                                <li><strong>Diupdate:</strong> {{ \Carbon\Carbon::parse($event->updated_at)->format('d/m/Y H:i') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Deskripsi Event -->
                @if($event->deskripsi)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Deskripsi Event</h3>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $event->deskripsi }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex space-x-3">
                    <a href="{{ route('admin.events.edit', $event->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Event
                    </a>
                    
                    <a href="{{ route('admin.registrations.index') }}?event={{ $event->id }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-list mr-2"></i>Lihat Pendaftaran
                    </a>
                    
                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus event ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-trash mr-2"></i>Hapus Event
                        </button>
                    </form>
                    
                    <a href="{{ route('admin.packages.index') }}?event={{ $event->id }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-box mr-2"></i>Paket Event
                    </a>
                </div>
            </div>
            
            <!-- Paket Event -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Paket Event</h3>
                
                @php
                    $packages = DB::table('paket')->where('lomba_id', $event->id)->get();
                @endphp
                
                @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($packages as $package)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-bold text-gray-800 mb-2">{{ $package->nama }}</h4>
                        <div class="text-2xl font-bold text-blue-600 mb-3">
                            Rp {{ number_format($package->harga, 0, ',', '.') }}
                        </div>
                        
                        <div class="space-y-2">
                            @if($package->termasuk_race_kit)
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Termasuk Race Kit</span>
                            </div>
                            @endif
                            
                            @if($package->termasuk_medali)
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Termasuk Medali</span>
                            </div>
                            @endif
                            
                            @if($package->termasuk_kaos)
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Termasuk Kaos</span>
                            </div>
                            @endif
                            
                            @if($package->termasuk_sertifikat)
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Termasuk Sertifikat</span>
                            </div>
                            @endif
                            
                            @if($package->termasuk_snack)
                            <div class="flex items-center text-sm text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>Termasuk Snack</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-box-open text-4xl mb-3"></i>
                    <p>Belum ada paket untuk event ini</p>
                    <a href="{{ route('admin.packages.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        <i class="fas fa-plus mr-1"></i> Tambah Paket
                    </a>
                </div>
                @endif
            </div>
            
            <!-- Recent Registrations for this Event -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Pendaftaran Terbaru</h3>
                    <a href="{{ route('admin.registrations.index') }}?event={{ $event->id }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @php
                    $registrations = DB::table('pendaftaran')
                        ->join('pengguna', 'pendaftaran.id_pengguna', '=', 'pengguna.id')
                        ->join('paket', 'pendaftaran.id_paket', '=', 'paket.id')
                        ->where('pendaftaran.id_lomba', $event->id)
                        ->select('pendaftaran.*', 
                                 'pengguna.nama as user_nama',
                                 'pengguna.email as user_email',
                                 'paket.nama as package_name')
                        ->orderBy('pendaftaran.created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                
                @if($registrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registrations as $reg)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $reg->user_nama }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $reg->user_email }}</div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $reg->package_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($reg->created_at)->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $reg->status == 'disetujui' ? 'bg-green-100 text-green-800' : 
                                           ($reg->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($reg->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.registrations.view', $reg->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($reg->status == 'menunggu')
                                    <a href="#" 
                                       class="text-green-600 hover:text-green-900 approve-btn" 
                                       data-id="{{ $reg->id }}" title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users-slash text-4xl mb-3"></i>
                    <p>Belum ada pendaftaran untuk event ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal for Approve -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Setujui Pendaftaran</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyetujui pendaftaran ini?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg">
                    Batal
                </button>
                <button type="button" onclick="approveRegistration()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Setujui
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRegistrationId = null;
        
        // Modal functions
        function showApproveModal(registrationId) {
            currentRegistrationId = registrationId;
            document.getElementById('approveModal').classList.remove('hidden');
        }
        
        function closeModal() {
            currentRegistrationId = null;
            document.getElementById('approveModal').classList.add('hidden');
        }
        
        function approveRegistration() {
            if (!currentRegistrationId) return;
            
            fetch(`/admin/registrations/${currentRegistrationId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            })
            .finally(() => {
                closeModal();
            });
        }
        
        // Event listener for approve buttons
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-btn');
            approveButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const registrationId = this.getAttribute('data-id');
                    showApproveModal(registrationId);
                });
            });
            
            // Close modal on outside click
            document.getElementById('approveModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>