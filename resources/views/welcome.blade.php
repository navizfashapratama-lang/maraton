<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathon Events - Lari Bersama, Sehat Bersama</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
    
    <style>
        /* Semua style dari kode awal TETAP ADA di sini */
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
            background-color: #f8fafc;
        }
        
        .navbar-custom {
            background: white !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
        }
        
        .brand-logo {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: var(--dark) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--primary) !important;
        }
        
        .btn-primary-custom {
            background: var(--gradient);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-outline-custom:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.05)), 
                        url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            color: white;
            position: relative;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 3.5rem;
            margin-bottom: 20px;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        /* Features Section */
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            height: 100%;
            text-align: center;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 30px;
            color: var(--primary);
        }
        
        /* Events Section */
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .event-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .event-img {
            height: 200px;
            object-fit: cover;
        }
        
        .event-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* CTA Section */
        .cta-section {
            background: var(--gradient);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .cta-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 2.8rem;
            margin-bottom: 20px;
        }
        
        .footer {
            background: #1a1a2e;
            color: white;
            padding: 60px 0 30px;
        }
        
        /* Dashboard Cinema Style */
        .dashboard-cinema {
            padding: 50px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .cinema-header {
            margin-bottom: 40px;
        }
        
        .cinema-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 900;
            font-size: 2.5rem;
        }
        
        .cinema-filter {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: white;
            color: var(--primary);
        }
        
        .cinema-poster-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
            padding: 20px 0;
        }
        
        .cinema-poster {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cinema-poster:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .poster-image {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }
        
        .poster-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            padding: 20px;
            color: white;
        }
        
        .poster-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .poster-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* Paket Info Modal */
        .paket-info-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: white;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .paket-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .paket-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .detail-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }
        
        .detail-item h5 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        /* Pembayaran Section */
        .pembayaran-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-top: 30px;
        }
        
        .metode-pembayaran {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .metode-btn {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .metode-btn:hover, .metode-btn.active {
            border-color: var(--primary);
            background: rgba(67, 97, 238, 0.1);
        }
        
        .metode-btn i {
            font-size: 24px;
            margin-bottom: 10px;
            display: block;
            color: var(--primary);
        }
        
        .form-pembayaran {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .cinema-poster-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }
            
            .metode-pembayaran {
                flex-direction: column;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-mendatang {
            background: #28a745;
            color: white;
        }
        
        .status-selesai {
            background: #6c757d;
            color: white;
        }
        
        .status-dibatalkan {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                <i class="fas fa-running me-2"></i>MARATHON EVENTS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('events') }}">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    
                    <?php
                    // Start session dan koneksi database
                    session_start();
                    $host = 'localhost';
                    $dbname = 'maraton_db';
                    $username = 'root';
                    $password = '';
                    
                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch(PDOException $e) {
                        die("Connection failed: " . $e->getMessage());
                    }
                    
                    $is_logged_in = isset($_SESSION['user_id']) ? true : false;
                    $user_nama = isset($_SESSION['user_nama']) ? $_SESSION['user_nama'] : '';
                    $user_peran = isset($_SESSION['user_peran']) ? $_SESSION['user_peran'] : '';
                    ?>
                    
                    <?php if($is_logged_in): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($user_nama); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if(in_array($user_peran, ['admin', 'superadmin'])): ?>
                                    <li><a class="dropdown-item" href="admin/dashboard.php">Dashboard Admin</a></li>
                                <?php elseif($user_peran == 'staff'): ?>
                                    <li><a class="dropdown-item" href="staff/dashboard.php">Dashboard Staff</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="profile.php">Dashboard Saya</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a href="logout.php" class="dropdown-item">Logout</a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary-custom btn-sm" href="register.php">
                                Daftar Sekarang
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Clean & Attractive -->
    <section class="hero-clean">
        <div class="container">
            <div class="hero-wrapper">
                <div class="row align-items-center">
                    <!-- Content Column -->
                    <div class="col-lg-6">
                        <div class="hero-content pe-lg-4">
                            <!-- Badge -->
                            <div class="mb-3">
                                <span class="hero-badge" style="background: var(--gradient); color: white; padding: 8px 20px; border-radius: 50px; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-medal me-2"></i>Event Terpercaya
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h1 class="hero-title mb-3" style="color: #1e293b;">
                                Lari Bersama, 
                                <span style="color: #4361ee;">Sehat Bersama</span>
                            </h1>
                            
                            <!-- Description -->
                            <p class="hero-description mb-4" style="color: #64748b; line-height: 1.6;">
                                Bergabunglah dengan ribuan pelari dalam event marathon terbaik di Indonesia. 
                                Dari fun run 5K hingga marathon 42K, ada untuk semua level kemampuan.
                            </p>
                            
                            <!-- CTA Buttons -->
                            <div class="hero-actions d-flex flex-wrap gap-3 mb-4">
                                <a href="#dashboard" class="btn btn-primary btn-lg px-4" style="background: var(--gradient); border: none;">
                                    <i class="fas fa-running me-2"></i>Lihat Event Tersedia
                                </a>
                                <?php if(!$is_logged_in): ?>
                                <a href="register.php" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                </a>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Quick Stats -->
                            <?php
                            // Query untuk statistik
                            $totalEvents = $pdo->query("SELECT COUNT(*) as total FROM lomba")->fetch()['total'];
                            $totalUsers = $pdo->query("SELECT COUNT(*) as total FROM pengguna")->fetch()['total'];
                            $upcomingEvents = $pdo->query("SELECT COUNT(*) as total FROM lomba WHERE status = 'mendatang' AND tanggal >= CURDATE()")->fetch()['total'];
                            ?>
                            <div class="quick-stats d-flex flex-wrap gap-4">
                                <div class="stat-item">
                                    <div class="stat-number text-primary fw-bold"><?php echo $totalEvents; ?>+</div>
                                    <div class="stat-label text-muted">Event</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number text-primary fw-bold"><?php echo $totalUsers; ?>+</div>
                                    <div class="stat-label text-muted">Pengguna</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number text-primary fw-bold"><?php echo $upcomingEvents; ?></div>
                                    <div class="stat-label text-muted">Event Mendatang</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image Column -->
                    <div class="col-lg-6">
                        <div class="hero-image-container">
                            <div class="image-wrapper position-relative">
                                <img src="https://images.unsplash.com/photo-1552674605-db6ffd8facb5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     alt="Marathon Runners" 
                                     class="hero-image img-fluid rounded-3 shadow">
                                <div class="image-overlay"></div>
                                
                                <!-- Floating Card -->
                                <div class="floating-card position-absolute d-flex align-items-center p-3 rounded-3 shadow" style="background: white; bottom: 20px; left: 20px; max-width: 250px;">
                                    <div class="card-icon me-3">
                                        <i class="fas fa-award fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="card-title fw-bold">Event Terbaik 2024</div>
                                        <div class="card-sub text-muted">Berdasarkan review peserta</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Cinema Section -->
    <section id="dashboard" class="dashboard-cinema">
        <div class="container">
            <div class="cinema-header text-center">
                <h2>Pilih Event Marathon Anda</h2>
                <p>Temukan berbagai event lomba dari database kami</p>
            </div>
            
            <?php
            // Query untuk mendapatkan semua kategori yang ada
            $kategoriQuery = $pdo->query("SELECT DISTINCT kategori FROM lomba WHERE kategori IS NOT NULL AND kategori != '' ORDER BY kategori");
            $kategoris = $kategoriQuery->fetchAll(PDO::FETCH_ASSOC);
            
            // Query untuk mendapatkan semua event
            $eventQuery = $pdo->query("SELECT * FROM lomba WHERE status = 'mendatang' ORDER BY tanggal DESC");
            $events = $eventQuery->fetchAll(PDO::FETCH_ASSOC);
            ?>
            
            <div class="cinema-filter">
                <button class="filter-btn active" data-filter="all">Semua Event</button>
                <?php foreach($kategoris as $kat): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $kat['kategori']))); ?>">
                        <?php echo htmlspecialchars($kat['kategori']); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <div class="cinema-poster-grid">
                <?php if(count($events) > 0): ?>
                    <?php foreach($events as $event): 
                        $eventId = $event['id'];
                        $eventKategori = strtolower(str_replace(' ', '-', $event['kategori']));
                        $eventName = htmlspecialchars($event['nama']);
                        $eventDate = date('d M Y', strtotime($event['tanggal']));
                        $eventLocation = htmlspecialchars($event['lokasi']);
                        $eventPrice = number_format($event['harga_reguler'] ?? 0, 0, ',', '.');
                        $eventImage = $event['poster_url'] ?: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                        $eventStatus = $event['status'];
                        $kuotaPeserta = $event['kuota_peserta'];
                        
                        // Hitung jumlah pendaftaran untuk event ini
                        $pendaftaranQuery = $pdo->prepare("SELECT COUNT(*) as total FROM pendaftaran WHERE id_lomba = ? AND status_pendaftaran = 'disetujui'");
                        $pendaftaranQuery->execute([$eventId]);
                        $totalPendaftar = $pendaftaranQuery->fetch()['total'];
                        $kuotaTersedia = $kuotaPeserta - $totalPendaftar;
                    ?>
                        <div class="cinema-poster" data-category="<?php echo $eventKategori; ?>" onclick="openEventInfo(<?php echo $eventId; ?>)">
                            <img src="<?php echo $eventImage; ?>" alt="<?php echo $eventName; ?>" class="poster-image">
                            <div class="poster-overlay">
                                <h4 class="poster-title"><?php echo $eventName; ?></h4>
                                <p class="poster-subtitle">
                                    <i class="fas fa-calendar me-1"></i> <?php echo $eventDate; ?>
                                </p>
                                <p class="poster-subtitle">
                                    <i class="fas fa-map-marker-alt me-1"></i> <?php echo $eventLocation; ?>
                                </p>
                                <p class="poster-subtitle mb-1">
                                    <i class="fas fa-tag me-1"></i> Rp <?php echo $eventPrice; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="badge bg-<?php echo $eventStatus == 'mendatang' ? 'success' : ($eventStatus == 'selesai' ? 'secondary' : 'danger'); ?>">
                                        <?php echo ucfirst($eventStatus); ?>
                                    </span>
                                    <small><?php echo $kuotaTersedia; ?> kuota tersedia</small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada event yang tersedia. Silakan hubungi administrator.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Event Info Modal -->
    <div id="eventInfoModal" class="paket-info-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="mb-0">Detail Event</h4>
                <button class="modal-close" onclick="closeEventInfo()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="eventContent">
                    <!-- Content akan diisi oleh JavaScript -->
                </div>
                
                <!-- Paket yang tersedia -->
                <div class="paket-tersedia mt-4">
                    <h4>Pilih Paket Pendaftaran</h4>
                    <div id="paketList" class="row mt-3">
                        <!-- Paket akan diisi oleh JavaScript -->
                    </div>
                </div>
                
                <!-- Section Pembayaran -->
                <div class="pembayaran-section">
                    <h4>Metode Pembayaran</h4>
                    <p>Pilih metode pembayaran yang sesuai untuk Anda</p>
                    
                    <div class="metode-pembayaran">
                        <div class="metode-btn" data-metode="tunai" onclick="selectMetode('tunai')">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Tunai</span>
                        </div>
                        <div class="metode-btn" data-metode="transfer" onclick="selectMetode('transfer')">
                            <i class="fas fa-university"></i>
                            <span>Transfer Bank</span>
                        </div>
                        <div class="metode-btn" data-metode="qris" onclick="selectMetode('qris')">
                            <i class="fas fa-qrcode"></i>
                            <span>QRIS</span>
                        </div>
                    </div>
                    
                    <!-- Form Pembayaran Tunai -->
                    <div id="formTunai" class="form-pembayaran">
                        <h5>Pembayaran Tunai</h5>
                        <p>Anda dapat melakukan pembayaran tunai di kantor kami:</p>
                        <div class="alert alert-info">
                            <strong>Alamat:</strong> Jl. Marathon No. 123, Jakarta Pusat<br>
                            <strong>Jam Operasional:</strong> Senin - Jumat, 09:00 - 17:00<br>
                            <strong>Telepon:</strong> (021) 1234-5678
                        </div>
                        <p class="text-muted">Simpan bukti pembayaran untuk konfirmasi.</p>
                    </div>
                    
                    <!-- Form Pembayaran Transfer -->
                    <div id="formTransfer" class="form-pembayaran">
                        <h5>Pembayaran Transfer Bank</h5>
                        <p>Transfer ke rekening berikut:</p>
                        <div class="alert alert-success">
                            <table class="table">
                                <tr>
                                    <td><strong>Bank</strong></td>
                                    <td>BCA (Bank Central Asia)</td>
                                </tr>
                                <tr>
                                    <td><strong>Nomor Rekening</strong></td>
                                    <td>1234-5678-9012</td>
                                </tr>
                                <tr>
                                    <td><strong>Atas Nama</strong></td>
                                    <td>PT Marathon Events Indonesia</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah</strong></td>
                                    <td id="jumlahTransfer">Rp 0</td>
                                </tr>
                            </table>
                        </div>
                        <p class="text-muted">Upload bukti transfer setelah melakukan pembayaran.</p>
                        <div class="mb-3">
                            <label for="buktiTransfer" class="form-label">Upload Bukti Transfer</label>
                            <input type="file" class="form-control" id="buktiTransfer" accept="image/*,.pdf">
                        </div>
                    </div>
                    
                    <!-- Form Pembayaran QRIS -->
                    <div id="formQris" class="form-pembayaran">
                        <h5>Pembayaran QRIS</h5>
                        <p>Scan QR code berikut untuk pembayaran:</p>
                        <div class="text-center my-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MarathonEvents-Payment-123456" 
                                 alt="QR Code Payment" class="img-fluid" style="max-width: 200px;">
                        </div>
                        <p class="text-muted">Pembayaran akan diverifikasi otomatis dalam 5 menit.</p>
                    </div>
                    
                    <!-- Tombol Daftar -->
                    <div class="text-center mt-4">
                        <button class="btn btn-primary-custom me-2" onclick="daftarEvent()">
                            <i class="fas fa-running me-2"></i>Daftar Event Ini
                        </button>
                        <button class="btn btn-outline-custom" onclick="closeEventInfo()">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section - Simple & Clean -->
    <section id="about" class="py-5" style="background-color: #f9fafb;">
        <div class="container">
            <!-- Header Simple -->
            <div class="text-center mb-5">
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 rounded-pill fw-medium">
                        <i class="fas fa-running me-2"></i>Tentang Marathon Events
                    </span>
                </div>
                <h2 class="fw-bold mb-3" style="color: #1e293b;">
                    Lebih dari Sekedar Event Lari
                </h2>
                <p class="text-muted lead" style="max-width: 700px; margin: 0 auto;">
                    Kami membangun komunitas, menciptakan pengalaman tak terlupakan, 
                    dan menginspirasi gaya hidup sehat melalui lari
                </p>
            </div>

            <!-- Main Content dengan 2 Kolom -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="position-relative">
                        <!-- Image dengan efek simple -->
                        <div class="about-image-wrapper">
                            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 alt="Marathon Community" 
                                 class="img-fluid rounded-3 shadow">
                        </div>
                        
                        <!-- Stats simple di atas gambar -->
                        <?php
                        // Query untuk statistik lengkap
                        $completedEvents = $pdo->query("SELECT COUNT(*) as total FROM lomba WHERE status = 'selesai'")->fetch()['total'];
                        $totalRegistrants = $pdo->query("SELECT COUNT(*) as total FROM pendaftaran WHERE status_pendaftaran = 'disetujui'")->fetch()['total'];
                        $cities = $pdo->query("SELECT COUNT(DISTINCT lokasi) as total FROM lomba WHERE lokasi IS NOT NULL")->fetch()['total'];
                        ?>
                        <div class="stats-simple d-flex justify-content-center gap-4 mt-3">
                            <div class="stat-item text-center">
                                <div class="stat-number text-primary fw-bold"><?php echo $completedEvents; ?></div>
                                <div class="stat-label text-muted">Event Selesai</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number text-primary fw-bold"><?php echo $totalRegistrants; ?></div>
                                <div class="stat-label text-muted">Pendaftar</div>
                            </div>
                            <div class="stat-item text-center">
                                <div class="stat-number text-primary fw-bold"><?php echo $cities; ?></div>
                                <div class="stat-label text-muted">Kota</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="ps-lg-4">
                        <h3 class="fw-bold mb-4" style="color: #334155;">Misi Kami</h3>
                        <p class="mb-4" style="line-height: 1.8;">
                            Sejak 2015, Marathon Events berkomitmen untuk menciptakan event lari 
                            yang tidak hanya menantang secara fisik, tetapi juga membangun 
                            komunitas yang solid dan mendukung gaya hidup sehat.
                        </p>
                        
                        <div class="mission-list">
                            <div class="mission-item d-flex mb-3">
                                <div class="mission-icon me-3">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Event Berkualitas</h6>
                                    <p class="text-muted mb-0">Standar pelayanan dan keamanan internasional</p>
                                </div>
                            </div>
                            
                            <div class="mission-item d-flex mb-3">
                                <div class="mission-icon me-3">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Komunitas Kuat</h6>
                                    <p class="text-muted mb-0">Membangun jaringan pelari terbesar di Indonesia</p>
                                </div>
                            </div>
                            
                            <div class="mission-item d-flex mb-3">
                                <div class="mission-icon me-3">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Akses Untuk Semua</h6>
                                    <p class="text-muted mb-0">Event untuk semua level, dari pemula hingga profesional</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CTA Simple -->
                        <div class="mt-4">
                            <a href="events.php" class="btn btn-primary px-4 py-2 rounded-pill" style="background: var(--gradient); border: none;">
                                <i class="fas fa-calendar-alt me-2"></i>Lihat Jadwal Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features dalam Grid 3 Kolom -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-simple text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <div class="icon-wrapper" style="width: 60px; height: 60px; background: rgba(67, 97, 238, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="fas fa-shield-alt text-primary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Safety First</h5>
                        <p class="text-muted">
                            Tim medis, ambulance, dan security standby di setiap titik. 
                            Keselamatan peserta adalah prioritas utama kami.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-simple text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <div class="icon-wrapper" style="width: 60px; height: 60px; background: rgba(67, 97, 238, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="fas fa-award text-primary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Kualitas Terbaik</h5>
                        <p class="text-muted">
                            Medali premium, kaos teknikal berkualitas, dan sertifikat resmi 
                            untuk semua finisher.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-simple text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <div class="icon-wrapper" style="width: 60px; height: 60px; background: rgba(67, 97, 238, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-3">Komunitas Solid</h5>
                        <p class="text-muted">
                            Bergabung dengan komunitas pelari terbesar. Sharing pengalaman, 
                            tips, dan motivasi bersama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer - Clean & Professional -->
    <footer class="footer" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container">
            <!-- Main Footer Content -->
            <div class="row g-4">
                <!-- Brand & Description -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-brand mb-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="footer-logo me-2">
                                <i class="fas fa-running text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-0" style="color: white;">MARATHON EVENTS</h4>
                        </div>
                        <p class="text-light mb-3 opacity-75">
                            Platform event lari terpercaya di Indonesia. 
                            Membangun komunitas sehat sejak 2015.
                        </p>
                    </div>
                    
                    <!-- Newsletter Subscription -->
                    <div class="newsletter-form">
                        <h6 class="text-white mb-2 fw-medium">Berlangganan Newsletter</h6>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-sm" 
                                   placeholder="Email anda" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                            <button class="btn btn-primary btn-sm" type="button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <small class="text-white-50 d-block mt-2">
                            Dapatkan info event terbaru & promo spesial
                        </small>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3 pb-2" style="border-bottom: 2px solid #4361ee; display: inline-block;">
                        Menu Utama
                    </h5>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2">
                            <a href="index.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-home me-2 fa-sm"></i> Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="events.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-calendar-alt me-2 fa-sm"></i> Event
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#about" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-info-circle me-2 fa-sm"></i> Tentang
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#contact" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-envelope me-2 fa-sm"></i> Kontak
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Account Links -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3 pb-2" style="border-bottom: 2px solid #4361ee; display: inline-block;">
                        Akun
                    </h5>
                    <ul class="list-unstyled footer-links">
                        <?php if(!$is_logged_in): ?>
                        <li class="mb-2">
                            <a href="login.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-sign-in-alt me-2 fa-sm"></i> Login
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="register.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-user-plus me-2 fa-sm"></i> Daftar
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="mb-2">
                            <a href="profile.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-tachometer-alt me-2 fa-sm"></i> Dashboard
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="logout.php" class="text-white-50 text-decoration-none d-flex align-items-center link-hover">
                                <i class="fas fa-sign-out-alt me-2 fa-sm"></i> Logout
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Contact & Social -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="text-white fw-bold mb-3 pb-2" style="border-bottom: 2px solid #4361ee; display: inline-block;">
                        Kontak Kami
                    </h5>
                    <ul class="list-unstyled contact-info">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-3 mt-1 text-primary"></i>
                            <div>
                                <span class="text-white">Jl. Marathon No. 123</span><br>
                                <small class="text-white-50">Jakarta Pusat, Indonesia</small>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone me-3 text-primary"></i>
                            <span class="text-white-50">(021) 1234-5678</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope me-3 text-primary"></i>
                            <span class="text-white-50">info@marathonevents.id</span>
                        </li>
                    </ul>

                    <!-- Social Media -->
                    <div class="social-media mt-4">
                        <h6 class="text-white mb-2 fw-medium">Follow Kami</h6>
                        <div class="d-flex gap-2">
                            <a href="#" class="social-icon facebook" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon instagram" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon twitter" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon youtube" style="width: 36px; height: 36px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none;">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-top border-secondary my-4 opacity-25"></div>

            <!-- Bottom Footer -->
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="text-white-50 mb-0">
                        &copy; 2024 <span class="text-white">Marathon Events</span>. 
                        All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-bottom-links">
                        <a href="#" class="text-white-50 text-decoration-none me-3">Privacy Policy</a>
                        <a href="#" class="text-white-50 text-decoration-none me-3">Terms of Service</a>
                        <a href="#" class="text-white-50 text-decoration-none">FAQ</a>
                    </div>
                </div>
            </div>

            <!-- Back to Top Button -->
            <div class="text-center mt-4">
                <a href="#" class="back-to-top text-decoration-none" id="backToTop">
                    <i class="fas fa-arrow-up text-white me-2"></i>
                    <span class="text-white-50">Back to Top</span>
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let currentEventId = null;
        let currentPaketId = null;
        let selectedMetode = 'tunai';

        // Show loading
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Check login status
        function checkLoginStatus() {
            showLoading();
            
            // Cek session PHP
            <?php if($is_logged_in): ?>
                hideLoading();
                // Jika sudah login, buka form pendaftaran
                openRegistrationForm();
            <?php else: ?>
                hideLoading();
                // Jika belum login, redirect ke halaman login
                redirectToLogin();
            <?php endif; ?>
        }

        // Redirect to login
        function redirectToLogin() {
            if (!currentEventId) return;
            
            // Simpak event yang dipilih di sessionStorage
            sessionStorage.setItem('selected_event', currentEventId);
            if (currentPaketId) {
                sessionStorage.setItem('selected_paket', currentPaketId);
            }
            
            // Show message
            Swal.fire({
                title: 'Login Diperlukan',
                text: 'Anda perlu login terlebih dahulu untuk mendaftar event.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke halaman login dengan parameter event
                    window.location.href = `login.php?redirect=continue-registration&event=${currentEventId}`;
                }
            });
        }

        // Open registration form
        function openRegistrationForm() {
            if (!currentEventId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Event tidak ditemukan!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
            
            // Redirect ke form pendaftaran
            window.location.href = `daftar.php?event_id=${currentEventId}${currentPaketId ? '&paket_id=' + currentPaketId : ''}`;
        }

        // Filter event
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const posters = document.querySelectorAll('.cinema-poster');
                
                posters.forEach(poster => {
                    if (filter === 'all' || poster.getAttribute('data-category') === filter) {
                        poster.style.display = 'block';
                    } else {
                        poster.style.display = 'none';
                    }
                });
            });
        });

        // Open event info modal
        function openEventInfo(eventId) {
            currentEventId = eventId;
            currentPaketId = null;
            
            showLoading();
            
            // Fetch event data via AJAX
            fetch(`get_event_details.php?event_id=${eventId}`)
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        displayEventDetails(data.event, data.pakets);
                        document.getElementById('eventInfoModal').style.display = 'flex';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Gagal memuat data event',
                            confirmButtonColor: '#4361ee'
                        });
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memuat data',
                        confirmButtonColor: '#4361ee'
                    });
                });
        }

        // Display event details
        function displayEventDetails(event, pakets) {
            // Update modal title
            document.getElementById('modalTitle').textContent = event.nama;
            
            // Format date
            const eventDate = new Date(event.tanggal).toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const registrationCloseDate = event.pendaftaran_ditutup ? 
                new Date(event.pendaftaran_ditutup).toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }) : '-';
            
            // Update modal content
            const content = document.getElementById('eventContent');
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <img src="${event.poster_url || 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'}" 
                             alt="${event.nama}" class="paket-image">
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="badge bg-${event.status === 'mendatang' ? 'success' : (event.status === 'selesai' ? 'secondary' : 'danger')} fs-6">
                                ${event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                            </span>
                            <h4 class="text-primary mb-0">Rp ${parseInt(event.harga_reguler || 0).toLocaleString('id-ID')}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Deskripsi</h4>
                        <p>${event.deskripsi || 'Tidak ada deskripsi tersedia.'}</p>
                        
                        <h5 class="mt-4">Detail Event</h5>
                        <div class="paket-details">
                            <div class="detail-item">
                                <h6>Tanggal Event</h6>
                                <p class="mb-0"><i class="fas fa-calendar me-2"></i>${eventDate}</p>
                            </div>
                            <div class="detail-item">
                                <h6>Lokasi</h6>
                                <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>${event.lokasi || 'Tidak ditentukan'}</p>
                            </div>
                            <div class="detail-item">
                                <h6>Kategori</h6>
                                <p class="mb-0"><i class="fas fa-tag me-2"></i>${event.kategori}</p>
                            </div>
                            <div class="detail-item">
                                <h6>Kuota Peserta</h6>
                                <p class="mb-0"><i class="fas fa-users me-2"></i>${event.kuota_peserta} orang</p>
                            </div>
                            <div class="detail-item">
                                <h6>Pendaftaran Ditutup</h6>
                                <p class="mb-0"><i class="fas fa-clock me-2"></i>${registrationCloseDate}</p>
                            </div>
                            <div class="detail-item">
                                <h6>Harga Reguler</h6>
                                <p class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Rp ${parseInt(event.harga_reguler || 0).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                        
                        ${event.rute_lomba ? `
                            <h5 class="mt-4">Rute Lomba</h5>
                            <p>${event.rute_lomba}</p>
                        ` : ''}
                        
                        ${event.fasilitas ? `
                            <h5 class="mt-4">Fasilitas</h5>
                            <p>${event.fasilitas}</p>
                        ` : ''}
                    </div>
                </div>
            `;
            
            // Display pakets
            displayPakets(pakets);
            
            // Reset pembayaran
            resetPembayaran();
        }

        // Display pakets
        function displayPakets(pakets) {
            const paketList = document.getElementById('paketList');
            
            if (pakets.length > 0) {
                paketList.innerHTML = pakets.map(paket => `
                    <div class="col-md-6 mb-3">
                        <div class="card paket-card ${currentPaketId === paket.id ? 'border-primary' : ''}" 
                             onclick="selectPaket(${paket.id}, ${paket.harga})" 
                             style="cursor: pointer; transition: all 0.3s;">
                            <div class="card-body">
                                <h5 class="card-title">${paket.nama}</h5>
                                <h6 class="text-primary">Rp ${parseInt(paket.harga).toLocaleString('id-ID')}</h6>
                                <div class="facilities mt-2">
                                    ${paket.termasuk_race_kit ? '<span class="badge bg-success me-1 mb-1">Race Kit</span>' : ''}
                                    ${paket.termasuk_medali ? '<span class="badge bg-success me-1 mb-1">Medali</span>' : ''}
                                    ${paket.termasuk_kaos ? '<span class="badge bg-success me-1 mb-1">Kaos</span>' : ''}
                                    ${paket.termasuk_sertifikat ? '<span class="badge bg-success me-1 mb-1">Sertifikat</span>' : ''}
                                    ${paket.termasuk_snack ? '<span class="badge bg-success me-1 mb-1">Snack</span>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                paketList.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Belum ada paket tersedia untuk event ini.
                        </div>
                    </div>
                `;
            }
        }

        // Select paket
        function selectPaket(paketId, harga) {
            currentPaketId = paketId;
            
            // Remove selection from all paket cards
            document.querySelectorAll('.paket-card').forEach(card => {
                card.classList.remove('border-primary');
                card.classList.remove('shadow');
            });
            
            // Add selection to selected paket card
            const selectedCard = document.querySelector(`.paket-card[onclick*="${paketId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('border-primary');
                selectedCard.classList.add('shadow');
            }
            
            // Update transfer amount
            document.getElementById('jumlahTransfer').textContent = `Rp ${parseInt(harga).toLocaleString('id-ID')}`;
        }

        // Close event info modal
        function closeEventInfo() {
            document.getElementById('eventInfoModal').style.display = 'none';
            currentEventId = null;
            currentPaketId = null;
            selectedMetode = null;
        }

        // Select metode pembayaran
        function selectMetode(metode) {
            selectedMetode = metode;
            
            // Remove active class from all buttons
            document.querySelectorAll('.metode-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to selected button
            document.querySelector(`[data-metode="${metode}"]`).classList.add('active');
            
            // Hide all forms
            document.querySelectorAll('.form-pembayaran').forEach(form => {
                form.style.display = 'none';
            });
            
            // Show selected form
            const formId = `form${metode.charAt(0).toUpperCase() + metode.slice(1)}`;
            const formElement = document.getElementById(formId);
            if (formElement) {
                formElement.style.display = 'block';
            }
        }

        // Reset pembayaran form
        function resetPembayaran() {
            selectedMetode = 'tunai';
            document.querySelectorAll('.metode-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.form-pembayaran').forEach(form => {
                form.style.display = 'none';
            });
            
            // Default show tunai form
            document.getElementById('formTunai').style.display = 'block';
            document.querySelector('[data-metode="tunai"]').classList.add('active');
        }

        // Daftar event
        function daftarEvent() {
            if (!currentEventId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih event terlebih dahulu!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
            
            // Jika ada paket, wajib pilih paket
            const pakets = document.querySelectorAll('.paket-card');
            if (pakets.length > 0 && !currentPaketId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih paket pendaftaran terlebih dahulu!',
                    confirmButtonColor: '#4361ee'
                });
                return;
            }
            
            // Cek status login sebelum mendaftar
            checkLoginStatus();
        }

        // Event listener untuk close modal dengan ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEventInfo();
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('eventInfoModal');
            if (event.target == modal) {
                closeEventInfo();
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada event yang tersimpan dari redirect
            const savedEvent = sessionStorage.getItem('selected_event');
            if (savedEvent) {
                try {
                    const eventId = parseInt(savedEvent);
                    const savedPaket = sessionStorage.getItem('selected_paket');
                    
                    // Hapus dari storage
                    sessionStorage.removeItem('selected_event');
                    sessionStorage.removeItem('selected_paket');
                    
                    // Tampilkan modal dengan event yang disimpan
                    openEventInfo(eventId);
                } catch (e) {
                    console.error('Error parsing saved event:', e);
                }
            }
            
            // Initialize filter
            resetPembayaran();
            
            // Back to Top functionality
            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.style.opacity = '1';
                        backToTop.style.visibility = 'visible';
                    } else {
                        backToTop.style.opacity = '0';
                        backToTop.style.visibility = 'hidden';
                    }
                });
                
                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>
</body>
</html>