<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - {{ $event->nama }}</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --success: #10b981;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .auth-card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .auth-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
        }
        
        .option-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }
        
        .option-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .option-card i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
        }
        
        .event-info {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="auth-card">
                    <div class="auth-header">
                        <h2 class="fw-bold mb-3">
                            <i class="fas fa-running me-2"></i>
                            Daftar Event
                        </h2>
                        <h4 class="fw-bold mb-0">{{ $event->nama }}</h4>
                        <p class="mb-0">
                            <i class="fas fa-calendar me-2"></i>
                            {{ date('d F Y', strtotime($event->tanggal)) }}
                        </p>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h5 class="fw-bold text-muted">Silakan pilih opsi untuk melanjutkan pendaftaran</h5>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="event-info mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="fw-bold mb-2">Informasi Event:</h6>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-primary">
                                            <i class="fas fa-tag me-1"></i>{{ $event->kategori ?? 'Marathon' }}
                                        </span>
                                        <span class="badge bg-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->lokasi ?? 'Jakarta' }}
                                        </span>
                                        <?php
                                            // Calculate available slots
                                            $totalPendaftar = DB::table('pendaftaran')
                                                ->where('id_lomba', $event->id)
                                                ->whereIn('status_pendaftaran', ['menunggu', 'disetujui'])
                                                ->count();
                                            $kuotaTersedia = $event->kuota_peserta - $totalPendaftar;
                                        ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-users me-1"></i>{{ $kuotaTersedia }} kuota tersedia
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ url('/event/' . $event->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-info-circle me-1"></i>Detail Event
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Login/Register Options -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="option-card" onclick="chooseOption('login')">
                                    <div class="mb-3">
                                        <i class="fas fa-sign-in-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">Sudah Punya Akun?</h4>
                                    <p class="text-muted mb-4">
                                        Login dengan akun Anda untuk melanjutkan pendaftaran event.
                                    </p>
                                    <button class="btn btn-primary w-100">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="option-card" onclick="chooseOption('register')">
                                    <div class="mb-3">
                                        <i class="fas fa-user-plus fa-3x text-success"></i>
                                    </div>
                                    <h4 class="fw-bold mb-3">Buat Akun Baru</h4>
                                    <p class="text-muted mb-4">
                                        Daftar akun baru untuk mulai mendaftar event ini.
                                    </p>
                                    <button class="btn btn-success w-100">
                                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Note -->
                        <div class="alert alert-info mt-4">
                            <h6><i class="fas fa-info-circle me-2"></i>Catatan:</h6>
                            <p class="mb-0 small">
                                Setelah login/register, Anda akan langsung diarahkan ke form pendaftaran event ini.
                                Pastikan data pribadi yang diisi sesuai dengan KTP.
                            </p>
                        </div>
                        
                        <!-- Cancel Button -->
                        <div class="text-center mt-4">
                            <a href="{{ url('/event/' . $event->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function chooseOption(option) {
            if (option === 'login') {
                // Redirect to login with event parameters
                window.location.href = "{{ url('/login') }}?redirect=event&event_id={{ $event->id }}&event_name={{ urlencode($event->nama) }}";
            } else if (option === 'register') {
                // Redirect to register with event parameters
                window.location.href = "{{ url('/register') }}?redirect=event&event_id={{ $event->id }}&event_name={{ urlencode($event->nama) }}";
            }
        }
        
        // Add hover effects
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
                this.style.borderColor = '#4361ee';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
                this.style.borderColor = '#dee2e6';
            });
        });
    </script>
</body>
</html>