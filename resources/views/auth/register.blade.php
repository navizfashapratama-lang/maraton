<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peserta - Marathon Events</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.05)), 
                        url('https://images.unsplash.com/photo-1552674605-db6ffd8facb5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .register-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .register-left {
            background: linear-gradient(135deg, rgba(67, 97, 238, 0.95) 0%, rgba(58, 12, 163, 0.95) 100%);
            color: white;
            padding: 60px 40px;
            height: 100%;
            border-radius: 20px 0 0 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .register-right {
            background: white;
            padding: 60px 40px;
            border-radius: 0 20px 20px 0;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .brand-logo {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .brand-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }
        
        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 40px 0;
        }
        
        .benefit-list li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .benefit-list i {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
        }
        
        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }
        
        .form-subtitle {
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        .input-group-custom {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .input-group-custom:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .input-group-custom .input-group-text {
            background: #f8f9fa;
            border: none;
            color: var(--primary);
            padding: 0 20px;
            min-width: 45px;
            justify-content: center;
        }
        
        .input-group-custom .form-control {
            border: none;
            padding: 15px;
            font-size: 1rem;
            background: #f8f9fa;
        }
        
        .input-group-custom .form-control:focus {
            box-shadow: none;
            background: white;
        }
        
        .btn-register {
            background: var(--gradient);
            border: none;
            color: white;
            padding: 15px;
            font-weight: 600;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }
        
        .login-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }
        
        .back-home {
            position: absolute;
            top: 30px;
            left: 30px;
        }
        
        .back-home a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .back-home a:hover {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .alert-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .step {
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            background: #e9ecef;
            color: #6c757d;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
        }
        
        .step.active .step-number {
            background: var(--primary);
            color: white;
        }
        
        .step-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .register-left, .register-right {
                border-radius: 20px;
                margin: 20px;
                padding: 40px 30px;
            }
            
            .register-left {
                border-radius: 20px 20px 0 0;
            }
            
            .register-right {
                border-radius: 0 0 20px 20px;
            }
            
            .back-home {
                top: 20px;
                left: 20px;
            }
            
            .progress-steps::before {
                left: 5%;
                right: 5%;
            }
        }
    </style>
</head>
<body>
    <!-- Back to Home -->
    <div class="back-home d-none d-md-block">
        <a href="{{ url('/') }}">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
        </a>
    </div>
    
    <div class="container-fluid register-container fade-in">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row g-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Left Side - Benefits -->
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="register-left">
                            <div class="mb-5">
                                <h1 class="brand-logo">
                                    <i class="fas fa-running me-2"></i>MARATHON EVENTS
                                </h1>
                                <p class="brand-subtitle">
                                    Bergabunglah dengan ribuan pelari di Indonesia
                                </p>
                            </div>
                            
                            <!-- Progress Steps -->
                            <div class="progress-steps">
                                <div class="step active">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Daftar</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Pilih Event</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Bayar</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">4</div>
                                    <div class="step-label">Ikut Event</div>
                                </div>
                            </div>
                            
                            <ul class="benefit-list">
                                <li>
                                    <i class="fas fa-ticket-alt"></i>
                                    <div>
                                        <h5 class="mb-1">Akses ke Semua Event</h5>
                                        <p class="mb-0 opacity-75">Daftar ke 100+ event marathon setiap tahun</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="fas fa-percentage"></i>
                                    <div>
                                        <h5 class="mb-1">Diskon Khusus Member</h5>
                                        <p class="mb-0 opacity-75">Harga khusus untuk member terdaftar</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="fas fa-bell"></i>
                                    <div>
                                        <h5 class="mb-1">Notifikasi Event</h5>
                                        <p class="mb-0 opacity-75">Dapatkan info event terbaru langsung</p>
                                    </div>
                                </li>
                                <li>
                                    <i class="fas fa-history"></i>
                                    <div>
                                        <h5 class="mb-1">Riwayat Lengkap</h5>
                                        <p class="mb-0 opacity-75">Simpan semua sertifikat dan hasil lomba</p>
                                    </div>
                                </li>
                            </ul>
                            
                            <div class="mt-auto pt-5">
                                <p class="opacity-75 mb-0">
                                    <i class="fas fa-quote-left me-2"></i>
                                    "Lari mengajarkan kita bahwa batas hanyalah ilusi. 
                                    Setiap langkah membawa kita lebih dekat ke versi terbaik diri kita."
                                </p>
                                <small class="opacity-75">- Marathon Runners Community</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - Register Form -->
                    <div class="col-lg-6">
                        <div class="register-right">
                            <!-- Mobile Brand -->
                            <div class="d-block d-lg-none text-center mb-4">
                                <h2 class="brand-logo text-primary">
                                    <i class="fas fa-running me-2"></i>MARATHON EVENTS
                                </h2>
                                <p class="text-muted">Daftar Akun Peserta</p>
                            </div>
                            
                            <!-- Mobile Back Button -->
                            <div class="d-block d-lg-none mb-4">
                                <a href="{{ url('/') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                                </a>
                            </div>
                            
                            <h1 class="form-title">Daftar Akun Peserta</h1>
                            <p class="form-subtitle">Isi data diri Anda untuk mulai bergabung</p>
                            
                            @if(session('success'))
                            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            @endif

                            @if($errors->any())
                            <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fas fa-user text-primary me-2"></i>Data Pribadi</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <div class="input-group-custom">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('nama') is-invalid @enderror" 
                                                   name="nama" 
                                                   value="{{ old('nama') }}" 
                                                   placeholder="Masukkan nama lengkap"
                                                   required>
                                        </div>
                                        @error('nama')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                            <div class="input-group-custom">
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                                <input type="email" 
                                                       class="form-control @error('email') is-invalid @enderror" 
                                                       name="email" 
                                                       value="{{ old('email') }}" 
                                                       placeholder="contoh@email.com"
                                                       required>
                                            </div>
                                            @error('email')
                                            <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Nomor Telepon</label>
                                            <div class="input-group-custom">
                                                <span class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control @error('telepon') is-invalid @enderror" 
                                                       name="telepon" 
                                                       value="{{ old('telepon') }}" 
                                                       placeholder="0812 3456 7890">
                                            </div>
                                            @error('telepon')
                                            <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Security -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fas fa-lock text-primary me-2"></i>Keamanan Akun</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                            <div class="input-group-custom">
                                                <span class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password" 
                                                       placeholder="Minimal 6 karakter"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary border-0" onclick="togglePassword('password', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                            <small class="text-danger mt-1">{{ $message }}</small>
                                            @enderror
                                            <div class="form-text">
                                                <small>Minimal 6 karakter</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
                                            <div class="input-group-custom">
                                                <span class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password_confirmation" 
                                                       name="password_confirmation" 
                                                       placeholder="Ketik ulang password"
                                                       required>
                                                <button type="button" class="btn btn-outline-secondary border-0" onclick="togglePassword('password_confirmation', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Information -->
                                <div class="mb-4">
                                    <h5 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Tambahan</h5>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat</label>
                                        <div class="input-group-custom">
                                            <span class="input-group-text">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('alamat') is-invalid @enderror" 
                                                   name="alamat" 
                                                   value="{{ old('alamat') }}" 
                                                   placeholder="Alamat lengkap">
                                        </div>
                                        @error('alamat')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="L">
                                                <label class="form-check-label" for="laki">Laki-laki</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="P">
                                                <label class="form-check-label" for="perempuan">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms & Conditions -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('terms') is-invalid @enderror" 
                                               type="checkbox" 
                                               id="terms" 
                                               name="terms" 
                                               required>
                                        <label class="form-check-label" for="terms">
                                            Saya menyetujui <a href="#" class="text-primary">Syarat & Ketentuan</a> dan 
                                            <a href="#" class="text-primary">Kebijakan Privasi</a> Marathon Events
                                        </label>
                                    </div>
                                    @error('terms')
                                    <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <button type="submit" class="btn-register">
                                    <i class="fas fa-user-plus me-2"></i>DAFTAR AKUN SEKARANG
                                </button>
                            </form>

                            <!-- Already Have Account -->
                            <div class="login-section">
                                <p class="mb-2">Sudah punya akun?</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk ke Akun Anda
                                </a>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Hanya akun <strong>peserta</strong> yang bisa mendaftar via website ini. 
                                        Untuk akun admin/staff, hubungi administrator.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Password strength indicator
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const strength = checkPasswordStrength(password);
            updateStrengthIndicator(strength);
        });
        
        function checkPasswordStrength(password) {
            let strength = 0;
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;
            
            return strength;
        }
        
        function updateStrengthIndicator(strength) {
            const indicator = document.getElementById('password-strength');
            if (!indicator) return;
            
            const texts = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const colors = ['danger', 'warning', 'info', 'primary', 'success'];
            
            indicator.textContent = `Kekuatan: ${texts[strength]}`;
            indicator.className = `badge bg-${colors[strength]}`;
        }
        
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>