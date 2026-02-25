<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Event - {{ $event->nama }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }

        .registration-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 2rem 2rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }
        
        .form-section {
            background: white;
            border-radius: 1.25rem;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            border: 1px solid #f1f5f9;
        }
        
        .package-card {
            border: 2px solid #f1f5f9;
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            background: #fff;
        }
        
        .package-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(67, 97, 238, 0.1);
        }
        
        .package-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        .package-check {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 26px;
            height: 26px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .package-card.selected .package-check { display: flex; }
        
        .payment-option {
            border: 2px solid #f1f5f9;
            border-radius: 1rem;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .payment-option:hover { border-color: var(--primary); background: #f8fafc; }
        .payment-option.active { border-color: var(--primary); background: var(--primary-light); }

        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            padding: 1rem 2rem;
            border-radius: 1rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .required::after { content: " *"; color: var(--danger); }

        /* x-small font helper */
        .x-small { font-size: 0.75rem; }
    </style>
</head>
<body>
    <div class="registration-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2"><i class="fas fa-running me-2"></i>Pendaftaran Event</h2>
                    <h4 class="opacity-90 mb-1">{{ $event->nama }}</h4>
                    <p class="mb-0 small">
                        <i class="fas fa-calendar me-2"></i>{{ date('d F Y', strtotime($event->tanggal)) }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-map-marker-alt me-2"></i>{{ $event->lokasi ?? 'Lokasi akan diumumkan' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ url('/event/' . $event->id) }}" class="btn btn-outline-light rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-8">
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    </div>
                @endif
                
                <form action="{{ url('/event/' . $event->id . '/register') }}" method="POST" id="registrationForm">
                    @csrf
                    
                    <div class="form-section">
                        <h4 class="fw-bold mb-4 text-slate-800">
                            <i class="fas fa-user-circle me-2 text-primary"></i>Data Pribadi
                        </h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control form-control-lg rounded-3" value="{{ $user->nama ?? '' }}" required placeholder="Sesuai KTP">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg rounded-3" value="{{ $user->email ?? '' }}" required placeholder="email@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Nomor Telepon</label>
                                <input type="tel" name="telepon" class="form-control form-control-lg rounded-3" value="{{ $user->telepon ?? '' }}" required placeholder="0812xxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control form-control-lg rounded-3" value="{{ $user->tanggal_lahir ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-semibold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select form-control-lg rounded-3" required>
                                    <option value="">Pilih</option>
                                    <option value="L" {{ ($user->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ ($user->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ukuran Jersey</label>
                                <select name="ukuran_jersey" class="form-select form-control-lg rounded-3">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="XS">XS</option><option value="S">S</option><option value="M">M</option>
                                    <option value="L">L</option><option value="XL">XL</option><option value="XXL">XXL</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label required fw-semibold">Kota</label>
                                <input type="text" name="kota" class="form-control form-control-lg rounded-3" value="{{ $user->kota ?? '' }}" required placeholder="Contoh: Jakarta">
                            </div>
                            <div class="col-12">
                                <label class="form-label required fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control rounded-3" rows="3" required>{{ $user->alamat ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="fw-bold mb-4 text-slate-800">
                            <i class="fas fa-box-open me-2 text-primary"></i>Pilihan Paket
                        </h4>
                        <div class="row g-3">
                            @foreach($packages as $package)
                                <div class="col-md-6">
                                    <div class="package-card {{ $loop->first ? 'selected' : '' }}" onclick="selectPackage({{ $package->id }})">
                                        <div class="package-check"><i class="fas fa-check"></i></div>
                                        <input type="radio" name="id_paket" id="package{{ $package->id }}" value="{{ $package->id }}" {{ $loop->first ? 'checked' : '' }} style="display: none;">
                                        <h5 class="fw-bold mb-1">{{ $package->nama }}</h5>
                                        <div class="mb-2">
                                            <span class="h5 fw-bold text-primary">Rp {{ number_format($package->harga, 0, ',', '.') }}</span>
                                        </div>
                                        <ul class="list-unstyled mb-0 x-small text-muted">
                                            @if($package->termasuk_kaos) <li><i class="fas fa-check text-success me-1"></i>Jersey</li> @endif
                                            @if($package->termasuk_medali) <li><i class="fas fa-check text-success me-1"></i>Medali</li> @endif
                                            @if($package->termasuk_race_kit) <li><i class="fas fa-check text-success me-1"></i>Race Kit</li> @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-section">
                        <h4 class="fw-bold mb-4 text-slate-800">
                            <i class="fas fa-wallet me-2 text-primary"></i>Metode Pembayaran
                        </h4>
                        
                        <p class="text-muted small mb-4">Pilih metode pembayaran. Instruksi detail akan diberikan setelah pendaftaran selesai.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="payment-option active w-100" id="labelTF">
                                    <input type="radio" name="metode_pembayaran" value="transfer" checked class="d-none" onchange="togglePayment('transfer')">
                                    <div class="d-flex align-items-center">
                                        <div class="p-3 bg-blue-100 text-blue-600 rounded-3 me-3"><i class="fas fa-university fa-lg"></i></div>
                                        <div>
                                            <p class="mb-0 fw-bold">Transfer Bank</p>
                                            <span class="text-primary x-small fw-bold uppercase">BCA / BRI</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="payment-option w-100" id="labelCash">
                                    <input type="radio" name="metode_pembayaran" value="cash" class="d-none" onchange="togglePayment('onsite')">
                                    <div class="d-flex align-items-center">
                                        <div class="p-3 bg-orange-100 text-orange-600 rounded-3 me-3"><i class="fas fa-hand-holding-usd fa-lg"></i></div>
                                        <div>
                                            <p class="mb-0 fw-bold">Bayar di Tempat</p>
                                            <span class="text-muted x-small uppercase">Cash Onsite</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="infoTransfer" class="mt-4 alert alert-primary border-0 rounded-4 p-3">
                            <i class="fas fa-info-circle me-2"></i> Anda akan mendapatkan nomor rekening BCA/BRI setelah menekan tombol daftar di bawah.
                        </div>
                        <div id="infoCash" class="mt-4 alert alert-warning border-0 rounded-4 p-3" style="display:none;">
                            <i class="fas fa-handshake me-2"></i> Anda dapat membayar secara tunai di lokasi event. Pendaftaran tetap harus diselesaikan sekarang.
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="fw-bold mb-4"><i class="fas fa-sticky-note me-2 text-primary"></i>Catatan Khusus</h4>
                        <textarea name="catatan_khusus" class="form-control rounded-3" rows="2" placeholder="Alergi, kondisi kesehatan, dll (opsional)"></textarea>
                    </div>
                    
                    <div class="form-section bg-light border-0">
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms" required>
                            <label class="form-check-label small" for="agree_terms">
                                Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a> yang berlaku.
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg shadow">
                            <i class="fas fa-paper-plane me-2"></i>Selesaikan Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 20px;">
                    <div class="form-section border-top border-primary border-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Info Event</h5>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-3 d-flex"><i class="fas fa-users text-primary me-3 mt-1"></i><span>Kuota: {{ $kuotaTersedia }} Peserta</span></li>
                            <li class="mb-0 d-flex"><i class="fas fa-tag text-primary me-3 mt-1"></i><span>Kategori: {{ $event->kategori ?? 'Marathon' }}</span></li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-info rounded-4 border-0 shadow-sm">
                        <h6 class="fw-bold"><i class="fas fa-shield-alt me-2"></i>Verifikasi</h6>
                        <p class="small mb-0">Staff kami akan memverifikasi pendaftaran Anda segera setelah pembayaran diterima.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-muted">1. Data harus sesuai kartu identitas.<br>2. Pendaftaran tidak dapat dibatalkan setelah disetujui.<br>3. Peserta wajib sehat jasmani & rohani.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Package selection logic
        function selectPackage(packageId) {
            document.getElementById('package' + packageId).checked = true;
            document.querySelectorAll('.package-card').forEach(card => card.classList.remove('selected'));
            document.querySelector(`[onclick="selectPackage(${packageId})"]`).classList.add('selected');
        }

        // Toggle Payment Visibility (Pilih Saja)
        function togglePayment(method) {
            const infoTransfer = document.getElementById('infoTransfer');
            const infoCash = document.getElementById('infoCash');
            const labelTF = document.getElementById('labelTF');
            const labelCash = document.getElementById('labelCash');

            if (method === 'transfer') {
                infoTransfer.style.display = 'block';
                infoCash.style.display = 'none';
                labelTF.classList.add('active');
                labelCash.classList.remove('active');
            } else {
                infoTransfer.style.display = 'none';
                infoCash.style.display = 'block';
                labelTF.classList.remove('active');
                labelCash.classList.add('active');
            }
        }
        
        // SweetAlert on Form Submit
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            if (!document.getElementById('agree_terms').checked) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Setujui syarat & ketentuan dahulu.', confirmButtonColor: '#4361ee' });
                return;
            }
            
            Swal.fire({
                title: 'Sedang mendaftar...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
        });

        // Initialize age validation (Min 16 Years)
        const dobInput = document.querySelector('input[name="tanggal_lahir"]');
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - 16);
        dobInput.max = maxDate.toISOString().split('T')[0];
    </script>
</body>
</html>