<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pendaftaran - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <a href="{{ route('admin.registrations.index') }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                        <h1 class="text-2xl font-bold text-gray-800">Detail Pendaftaran</h1>
                        <p class="text-gray-600">Kode: {{ $registration->kode_pendaftaran ?? 'N/A' }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($registration->status == 'menunggu')
                        <button onclick="approveRegistration()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                            <i class="fas fa-check mr-2"></i> Setujui
                        </button>
                        <button onclick="rejectRegistration()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                            <i class="fas fa-times mr-2"></i> Tolak
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-6 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Participant Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Peserta</h2>
                            @php
                                $statusClass = '';
                                $statusText = '';
                                switch($registration->status) {
                                    case 'menunggu':
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        $statusText = 'Menunggu';
                                        break;
                                    case 'disetujui':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        $statusText = 'Disetujui';
                                        break;
                                    case 'ditolak':
                                        $statusClass = 'bg-red-100 text-red-800';
                                        $statusText = 'Ditolak';
                                        break;
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Data Pribadi</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Nama Lengkap</p>
                                        <p class="font-medium">{{ $registration->nama_lengkap ?? $registration->user_nama ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="font-medium">{{ $registration->email ?? $registration->user_email ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="font-medium">{{ $registration->telepon ?? $registration->user_phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Lahir</p>
                                        <p class="font-medium">{{ $registration->tanggal_lahir ? date('d M Y', strtotime($registration->tanggal_lahir)) : ($registration->user_birthdate ? date('d M Y', strtotime($registration->user_birthdate)) : 'N/A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                        <p class="font-medium">
                                            @if($registration->jenis_kelamin == 'L')
                                                Laki-laki
                                            @elseif($registration->jenis_kelamin == 'P')
                                                Perempuan
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Alamat & Lainnya</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Alamat</p>
                                        <p class="font-medium">{{ $registration->alamat ?? $registration->user_alamat ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Ukuran Jersey</p>
                                        <p class="font-medium">{{ $registration->ukuran_jersey ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Nomor Start</p>
                                        <p class="font-medium">{{ $registration->nomor_start ?? 'Belum ditetapkan' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Catatan Khusus</p>
                                        <p class="font-medium">{{ $registration->catatan_khusus ?? 'Tidak ada' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Informasi Event</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Detail Event</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Nama Event</p>
                                        <p class="font-medium">{{ $registration->nama ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Kategori</p>
                                        <p class="font-medium">{{ $registration->kategori ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Paket Dipilih</p>
                                        <p class="font-medium">{{ $registration->package_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Harga Paket</p>
                                        <p class="font-medium">Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Tanggal & Waktu</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Event</p>
                                        <p class="font-medium">{{ $registration->tanggal ? date('d M Y', strtotime($registration->tanggal)) : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Lokasi</p>
                                        <p class="font-medium">{{ $registration->lokasi ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Tanggal Pendaftaran</p>
                                        <p class="font-medium">{{ date('d M Y H:i', strtotime($registration->created_at)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Terakhir Diperbarui</p>
                                        <p class="font-medium">{{ date('d M Y H:i', strtotime($registration->updated_at)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Actions Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-6">Aksi</h2>
                        
                        <div class="space-y-3">
                            @if($registration->status == 'menunggu')
                            <button onclick="approveRegistration()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium flex items-center justify-center transition">
                                <i class="fas fa-check mr-2"></i> Setujui Pendaftaran
                            </button>
                            
                            <button onclick="rejectRegistration()" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium flex items-center justify-center transition">
                                <i class="fas fa-times mr-2"></i> Tolak Pendaftaran
                            </button>
                            @endif
                            
                            <button onclick="window.print()" class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg font-medium flex items-center justify-center transition">
                                <i class="fas fa-print mr-2"></i> Cetak Detail
                            </button>
                            
                            @if($payment)
                            <a href="{{ route('admin.payments.view', $payment->id) }}" class="block w-full border border-blue-300 hover:bg-blue-50 text-blue-600 py-3 rounded-lg font-medium flex items-center justify-center transition">
                                <i class="fas fa-credit-card mr-2"></i> Lihat Pembayaran
                            </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Package Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Paket</h2>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                <span class="text-gray-700">Paket: {{ $registration->package_name }}</span>
                            </div>
                            
                            @if($registration->termasuk_race_kit)
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Termasuk Race Kit</span>
                            </div>
                            @endif
                            
                            @if($registration->termasuk_medali)
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Termasuk Medali</span>
                            </div>
                            @endif
                            
                            @if($registration->termasuk_kaos)
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                <span class="text-gray-700">Termasuk Kaos</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Payment Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Pembayaran</h2>
                        
                        @php
                            $paymentStatusClass = '';
                            $paymentStatusText = '';
                            switch($registration->status_pembayaran) {
                                case 'lunas':
                                    $paymentStatusClass = 'bg-green-100 text-green-800';
                                    $paymentStatusText = 'Lunas';
                                    break;
                                case 'menunggu':
                                    $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                                    $paymentStatusText = 'Menunggu';
                                    break;
                                case 'gagal':
                                    $paymentStatusClass = 'bg-red-100 text-red-800';
                                    $paymentStatusText = 'Gagal';
                                    break;
                                default:
                                    $paymentStatusClass = 'bg-gray-100 text-gray-800';
                                    $paymentStatusText = 'Belum Dibayar';
                            }
                        @endphp
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $paymentStatusClass }}">
                                {{ $paymentStatusText }}
                            </span>
                        </div>
                        
                        @if($payment && $payment->bukti_pembayaran)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</p>
                            <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-invoice mr-2"></i> Lihat Bukti Pembayaran
                            </a>
                        </div>
                        @else
                        <p class="text-gray-500 text-sm">Belum ada bukti pembayaran diunggah.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Rejection Reason -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Alasan Penolakan</h3>
                    <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">Masukkan alasan penolakan (opsional)</label>
                    <textarea id="rejectReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Alasan penolakan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">Batal</button>
                    <button onclick="submitRejection()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                        <i class="fas fa-times mr-2"></i> Tolak Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function approveRegistration() {
            if (confirm('Apakah Anda yakin ingin menyetujui pendaftaran ini?')) {
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
                button.disabled = true;
                
                fetch('{{ route("admin.registrations.approve", $registration->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message);
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyetujui pendaftaran');
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        }
        
        let isRejecting = false;
        
        function rejectRegistration() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejectReason').value = '';
        }
        
        function submitRejection() {
            if (isRejecting) return;
            
            const reason = document.getElementById('rejectReason').value;
            isRejecting = true;
            
            const button = document.querySelector('#rejectModal button[onclick="submitRejection()"]');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
            button.disabled = true;
            
            fetch('{{ route("admin.registrations.reject", $registration->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeRejectModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert(data.message);
                    button.innerHTML = originalText;
                    button.disabled = false;
                    isRejecting = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menolak pendaftaran');
                button.innerHTML = originalText;
                button.disabled = false;
                isRejecting = false;
            });
        }
        
        // Close modal on ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeRejectModal();
            }
        });
    </script>
</body>
</html>