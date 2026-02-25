@extends('layouts.app')

@section('title', 'Pendaftaran Event - Marathon Runner')

@section('content')
<div class="container py-5">
    <!-- Alert Container -->
    <div class="alert-container"></div>
    
    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('event.detail', $event->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Event
        </a>
    </div>

    <div class="row">
        <!-- Left Column - Event Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-running me-2"></i>Ringkasan Event</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $event->nama }}</h6>
                    <hr>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Tanggal Event</small>
                        <strong>{{ \Carbon\Carbon::parse($event->tanggal)->translatedFormat('d F Y') }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Lokasi</small>
                        <strong>{{ $event->lokasi }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Kategori</small>
                        <strong>{{ $event->kategori }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Status Event</small>
                        <span class="badge bg-success">Mendatang</span>
                    </div>
                    
                    @if(isset($selectedPackage))
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted d-block">Paket Dipilih</small>
                        <strong>{{ $selectedPackage->nama }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Total Pembayaran</small>
                        <h4 class="fw-bold text-primary">Rp {{ number_format($selectedPackage->harga, 0, ',', '.') }}</h4>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Termasuk:</small>
                        <ul class="list-unstyled">
                            @if($selectedPackage->termasuk_race_kit)
                            <li><i class="fas fa-check-circle text-success me-2"></i>Race Kit</li>
                            @endif
                            @if($selectedPackage->termasuk_medali)
                            <li><i class="fas fa-check-circle text-success me-2"></i>Medali Finisher</li>
                            @endif
                            @if($selectedPackage->termasuk_kaos)
                            <li><i class="fas fa-check-circle text-success me-2"></i>Kaos Lari</li>
                            @endif
                            <li><i class="fas fa-check-circle text-success me-2"></i>Sertifikat Digital</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i>Konsumsi</li>
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Registration Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>Form Pendaftaran</h4>
                </div>
                <div class="card-body">
                    @if(!isset($selectedPackage))
                    <!-- Step 1: Pilih Paket -->
                    <div id="step1">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Silakan pilih paket pendaftaran terlebih dahulu.
                        </div>
                        
                        <h5 class="mb-4">Pilih Paket Pendaftaran</h5>
                        
                        @if($packages->count() > 0)
                        <div class="row g-4">
                            @foreach($packages as $package)
                            <div class="col-md-6">
                                <div class="card h-100 border package-card {{ request('paket') == $package->id ? 'border-primary' : '' }}" 
                                     onclick="selectPackage({{ $package->id }})"
                                     style="cursor: pointer;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title fw-bold">{{ $package->nama }}</h5>
                                            @if(strpos(strtolower($package->nama), 'premium') !== false)
                                            <span class="badge bg-primary">Rekomendasi</span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="fw-bold text-primary mb-4">
                                            Rp {{ number_format($package->harga, 0, ',', '.') }}
                                        </h3>
                                        
                                        <ul class="list-unstyled mb-4">
                                            <li class="mb-2">
                                                @if($package->termasuk_race_kit)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                @else
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                @endif
                                                Race Kit
                                            </li>
                                            <li class="mb-2">
                                                @if($package->termasuk_medali)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                @else
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                @endif
                                                Medali Finisher
                                            </li>
                                            <li class="mb-2">
                                                @if($package->termasuk_kaos)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                @else
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                                @endif
                                                Kaos Lari
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Sertifikat Digital
                                            </li>
                                            <li>
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                Konsumsi
                                            </li>
                                        </ul>
                                        
                                        <div class="text-center">
                                            <button class="btn btn-outline-primary w-100" onclick="selectPackage({{ $package->id }})">
                                                Pilih Paket Ini
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Belum ada paket pendaftaran untuk event ini.
                        </div>
                        @endif
                    </div>
                    @else
                    <!-- Step 2: Form Data Pribadi -->
                    <div id="step2">
                        <div class="progress mb-4" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%" 
                                 aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Anda memilih paket: <strong>{{ $selectedPackage->nama }}</strong>
                        </div>
                        
                        <form action="{{ route('event.register.submit', $event->id) }}" method="POST" id="registrationForm">
                            @csrf
                            <input type="hidden" name="paket_id" value="{{ $selectedPackage->id }}">
                            
                            <h5 class="mb-4">Data Pribadi</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           value="{{ session('user_nama') }}" required>
                                    <div class="invalid-feedback" id="nama_lengkap_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ session('user_email') }}" required readonly>
                                    <div class="invalid-feedback" id="email_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="telepon" class="form-label">Nomor Telepon/WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="telepon" name="telepon" 
                                           value="{{ session('user_telp') }}" 
                                           placeholder="Contoh: 081234567890"
                                           required>
                                    <small class="text-muted">Format: 08xxxxxxxxxx atau 628xxxxxxxxxxx</small>
                                    <div class="invalid-feedback" id="telepon_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                                    <div class="invalid-feedback" id="tanggal_lahir_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <div class="invalid-feedback" id="jenis_kelamin_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="golongan_darah" class="form-label">Golongan Darah</label>
                                    <select class="form-select" id="golongan_darah" name="golongan_darah">
                                        <option value="">Pilih Golongan Darah</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="AB">AB</option>
                                        <option value="O">O</option>
                                    </select>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required>{{ session('user_alamat') }}</textarea>
                                    <div class="invalid-feedback" id="alamat_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="kota" name="kota" required>
                                    <div class="invalid-feedback" id="kota_error"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="kode_pos" name="kode_pos">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="ukuran_jersey" class="form-label">Ukuran Jersey</label>
                                    <select class="form-select" id="ukuran_jersey" name="ukuran_jersey">
                                        <option value="">Pilih Ukuran</option>
                                        <option value="XS">XS (Extra Small)</option>
                                        <option value="S">S (Small)</option>
                                        <option value="M">M (Medium)</option>
                                        <option value="L">L (Large)</option>
                                        <option value="XL">XL (Extra Large)</option>
                                        <option value="XXL">XXL (Double Extra Large)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-4">Data Kesehatan</h5>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Informasi ini penting untuk keselamatan Anda selama lomba.
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                                    <input type="number" class="form-control" id="tinggi_badan" name="tinggi_badan" 
                                           min="100" max="250" placeholder="Contoh: 170">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                    <input type="number" class="form-control" id="berat_badan" name="berat_badan" 
                                           min="30" max="200" placeholder="Contoh: 65">
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit (jika ada)</label>
                                    <textarea class="form-control" id="riwayat_penyakit" name="riwayat_penyakit" rows="2" 
                                              placeholder="Contoh: Asma, Hipertensi, Diabetes, dll."></textarea>
                                </div>
                                
                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="setuju_kesehatan" name="setuju_kesehatan" required>
                                        <label class="form-check-label" for="setuju_kesehatan">
                                            Saya menyatakan bahwa kondisi kesehatan saya memungkinkan untuk mengikuti lomba marathon ini. 
                                            Saya bersedia bertanggung jawab penuh atas kondisi kesehatan saya selama mengikuti lomba.
                                        </label>
                                        <div class="invalid-feedback" id="setuju_kesehatan_error"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="mb-4">Pernyataan</h5>
                            
                            <div class="mb-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="setuju_syarat" name="setuju_syarat" required>
                                    <label class="form-check-label" for="setuju_syarat">
                                        Saya telah membaca dan menyetujui semua syarat dan ketentuan yang berlaku.
                                    </label>
                                    <div class="invalid-feedback" id="setuju_syarat_error"></div>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="setuju_data" name="setuju_data" required>
                                    <label class="form-check-label" for="setuju_data">
                                        Saya menyetujui penggunaan data pribadi saya untuk keperluan pendaftaran dan event.
                                    </label>
                                    <div class="invalid-feedback" id="setuju_data_error"></div>
                                </div>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="setuju_foto" name="setuju_foto" required>
                                    <label class="form-check-label" for="setuju_foto">
                                        Saya menyetujui penggunaan foto/video saya selama event untuk keperluan dokumentasi dan promosi.
                                    </label>
                                    <div class="invalid-feedback" id="setuju_foto_error"></div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('event.register', $event->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Ganti Paket
                                </a>
                                
                                <button type="button" class="btn btn-primary btn-lg" id="submitButton">
                                    <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Info Pembayaran -->
            @if(isset($selectedPackage))
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Setelah mendaftar, Anda akan mendapatkan kode pendaftaran. 
                        Silakan lakukan pembayaran dalam <strong>2x24 jam</strong> atau pendaftaran akan dibatalkan.
                    </div>
                    
                    <h6>Metode Pembayaran:</h6>
                    <ul>
                        <li><strong>Transfer Bank:</strong> BCA 1234567890 a.n. Marathon Events</li>
                        <li><strong>E-Wallet:</strong> Dana, OVO, Gopay (akan muncul setelah pendaftaran)</li>
                        <li><strong>QRIS:</strong> Scan QR code yang tersedia</li>
                    </ul>
                    
                    <p class="mb-0 text-muted">
                        <small>Pembayaran akan diverifikasi dalam 1x24 jam. Status pembayaran dapat dicek di halaman profil Anda.</small>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-white mt-3">Memproses pendaftaran...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Function untuk pilih paket
function selectPackage(packageId) {
    window.location.href = "{{ route('event.register', $event->id) }}?paket=" + packageId;
}

// Date validation - minimal 16 tahun
document.addEventListener('DOMContentLoaded', function() {
    // Set max tanggal lahir (minimal 16 tahun)
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    if (tanggalLahirInput) {
        const today = new Date();
        const minDate = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
        const maxDate = new Date(today.getFullYear() - 70, today.getMonth(), today.getDate());
        
        tanggalLahirInput.max = minDate.toISOString().split('T')[0];
        tanggalLahirInput.min = maxDate.toISOString().split('T')[0];
        
        // Auto-fill untuk testing (opsional)
        if (!tanggalLahirInput.value) {
            // Contoh: 30 tahun yang lalu
            const defaultDate = new Date(today.getFullYear() - 30, today.getMonth(), today.getDate());
            tanggalLahirInput.value = defaultDate.toISOString().split('T')[0];
        }
    }
    
    // Auto-fill jenis kelamin (opsional untuk testing)
    const jenisKelaminSelect = document.getElementById('jenis_kelamin');
    if (jenisKelaminSelect && !jenisKelaminSelect.value) {
        jenisKelaminSelect.value = 'L';
    }
    
    // Auto-fill kota (opsional)
    const kotaInput = document.getElementById('kota');
    if (kotaInput && !kotaInput.value) {
        kotaInput.value = 'Jakarta';
    }
    
    // Auto-fill alamat jika kosong
    const alamatInput = document.getElementById('alamat');
    if (alamatInput && !alamatInput.value.trim()) {
        alamatInput.value = 'Jl. Contoh No. 123';
    }
    
    // Auto-fill ukuran jersey
    const jerseySelect = document.getElementById('ukuran_jersey');
    if (jerseySelect && !jerseySelect.value) {
        jerseySelect.value = 'M';
    }
    
    // Form validation dan submit
    const form = document.getElementById('registrationForm');
    const submitButton = document.getElementById('submitButton');
    
    if (form && submitButton) {
        // Tangani klik tombol submit
        submitButton.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // Reset error states
            clearErrors();
            
            // Validasi form
            if (validateForm()) {
                try {
                    // Show loading
                    showLoading(true);
                    
                    // Prepare form data
                    const formData = new FormData(form);
                    
                    // Submit via AJAX
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    const result = await response.json();
                    
                    // Hide loading
                    showLoading(false);
                    
                    if (result.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Pendaftaran Berhasil!',
                            html: `Pendaftaran Anda telah berhasil.<br><br>
                                  <strong>Kode Pendaftaran:</strong> ${result.kode_pendaftaran}<br>
                                  <strong>Nomor Start:</strong> ${result.nomor_start || 'Akan di-generate setelah verifikasi'}<br><br>
                                  Silakan lakukan pembayaran dalam 2x24 jam.`,
                            showConfirmButton: true,
                            confirmButtonText: 'Lanjut ke Pembayaran',
                            showCancelButton: true,
                            cancelButtonText: 'Lihat Detail'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect ke halaman pembayaran
                                window.location.href = `/registration/${result.registration_id}/payment`;
                            } else {
                                // Redirect ke halaman sukses
                                window.location.href = `/registration/${result.registration_id}/success`;
                            }
                        });
                    } else {
                        // Show error message
                        if (result.errors) {
                            // Display validation errors
                            displayErrors(result.errors);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Pendaftaran Gagal',
                                text: result.message || 'Terjadi kesalahan saat mendaftar.'
                            });
                        }
                    }
                } catch (error) {
                    // Hide loading
                    showLoading(false);
                    
                    // Fallback: submit form secara normal
                    console.log('AJAX failed, submitting normally...');
                    form.submit();
                }
            }
        });
    }
    
    // Add hover effects to package cards
    document.querySelectorAll('.package-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 5px 15px rgba(67, 97, 238, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = 'none';
        });
    });
});

function validateForm() {
    let isValid = true;
    
    // Required fields
    const requiredFields = [
        { id: 'nama_lengkap', message: 'Nama lengkap harus diisi' },
        { id: 'email', message: 'Email harus diisi' },
        { id: 'telepon', message: 'Nomor telepon harus diisi' },
        { id: 'tanggal_lahir', message: 'Tanggal lahir harus diisi' },
        { id: 'jenis_kelamin', message: 'Jenis kelamin harus dipilih' },
        { id: 'alamat', message: 'Alamat harus diisi' },
        { id: 'kota', message: 'Kota harus diisi' }
    ];
    
    // Check required fields
    for (const field of requiredFields) {
        const element = document.getElementById(field.id);
        if (element && !element.value.trim()) {
            markFieldInvalid(element, field.message);
            isValid = false;
        }
    }
    
    // Validate phone number
    const phoneInput = document.getElementById('telepon');
    if (phoneInput && phoneInput.value.trim()) {
        const phone = phoneInput.value.trim();
        const cleanPhone = phone.replace(/\D/g, '');
        
        // Validasi panjang
        if (cleanPhone.length < 10 || cleanPhone.length > 15) {
            markFieldInvalid(phoneInput, 'Nomor telepon harus 10-15 digit angka');
            isValid = false;
        } else {
            // Validasi format Indonesia
            const indonesianRegex = /^(0|62|\+62)[0-9]{9,13}$/;
            const phoneWithoutSpaces = phone.replace(/\s/g, '');
            
            if (!indonesianRegex.test(phoneWithoutSpaces)) {
                markFieldInvalid(phoneInput, 'Format nomor telepon tidak valid. Gunakan format: 0812xxxxxxx atau 62812xxxxxxx');
                isValid = false;
            }
        }
    }
    
    // Validate tanggal lahir (minimal 16 tahun)
    const tanggalLahirInput = document.getElementById('tanggal_lahir');
    if (tanggalLahirInput && tanggalLahirInput.value) {
        const birthDate = new Date(tanggalLahirInput.value);
        const today = new Date();
        const age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        if (age < 16) {
            markFieldInvalid(tanggalLahirInput, 'Minimal usia 16 tahun untuk mendaftar');
            isValid = false;
        }
    }
    
    // Validate email format
    const emailInput = document.getElementById('email');
    if (emailInput && emailInput.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            markFieldInvalid(emailInput, 'Format email tidak valid');
            isValid = false;
        }
    }
    
    // Check checkboxes
    const checkboxes = [
        { id: 'setuju_syarat', message: 'Anda harus menyetujui syarat dan ketentuan' },
        { id: 'setuju_data', message: 'Anda harus menyetujui penggunaan data pribadi' },
        { id: 'setuju_foto', message: 'Anda harus menyetujui penggunaan foto/video' },
        { id: 'setuju_kesehatan', message: 'Anda harus menyetujui pernyataan kesehatan' }
    ];
    
    for (const checkbox of checkboxes) {
        const element = document.getElementById(checkbox.id);
        if (element && !element.checked) {
            markFieldInvalid(element, checkbox.message);
            isValid = false;
        }
    }
    
    return isValid;
}

function markFieldInvalid(element, message) {
    element.classList.add('is-invalid');
    const errorDiv = document.getElementById(element.id + '_error');
    if (errorDiv) {
        errorDiv.textContent = message;
    }
}

function clearErrors() {
    // Clear all is-invalid classes
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    
    // Clear all error messages
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
    });
}

function displayErrors(errors) {
    for (const field in errors) {
        const element = document.getElementById(field);
        if (element) {
            markFieldInvalid(element, errors[field][0]);
        } else {
            // For checkboxes
            const checkbox = document.querySelector(`input[name="${field}"]`);
            if (checkbox) {
                markFieldInvalid(checkbox, errors[field][0]);
            }
        }
    }
}

function showLoading(show) {
    const modal = document.getElementById('loadingModal');
    if (modal) {
        if (show) {
            const modalInstance = new bootstrap.Modal(modal, {
                backdrop: 'static',
                keyboard: false
            });
            modalInstance.show();
        } else {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    } else {
        // Fallback jika modal tidak ada
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            if (show) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitButton.disabled = true;
            } else {
                submitButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Daftar Sekarang';
                submitButton.disabled = false;
            }
        }
    }
}

// Auto-clean phone number format
document.getElementById('telepon')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
    // Auto add +62 if starts with 08
    if (value.startsWith('08') && value.length <= 11) {
        value = '62' + value.substring(1);
    }
    
    // Format display
    if (value.length > 2) {
        value = value.substring(0, 2) + ' ' + value.substring(2);
    }
    if (value.length > 6) {
        value = value.substring(0, 6) + ' ' + value.substring(6);
    }
    if (value.length > 10) {
        value = value.substring(0, 10) + ' ' + value.substring(10);
    }
    
    e.target.value = value.trim();
});
</script>
@endsection

@section('styles')
<style>
    .package-card {
        transition: all 0.3s ease;
    }
    
    .package-card:hover {
        transform: translateY(-5px);
        border-color: #4361ee !important;
    }
    
    .sticky-top {
        z-index: 1;
    }
    
    .form-control:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-primary:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    
    .invalid-feedback {
        display: block;
    }
    
    #loadingModal .modal-content {
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
    }
</style>
@endsection