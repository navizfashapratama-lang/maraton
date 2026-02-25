<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathon Events - Experience The Ultimate Running Journey</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Fonts Premium -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@600;700;800;900&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #1e293b;
            --primary-light: #f1f5f9;
            --primary-dark: #0f172a;
            --secondary: #475569;
            --accent: #2563eb;
            --success: #0f766e;
            --warning: #b45309;
            --danger: #b91c1c;
            --light: #f8fafc;
            --dark: #0f172a;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --gradient-primary: linear-gradient(145deg, #1e293b, #0f172a);
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.02);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.04);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.06);
            --shadow-hover: 0 12px 32px rgba(0,0,0,0.08);
            --transition: all 0.25s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--primary-dark);
        }
        
        /* Custom Cursor - lebih halus */
        .custom-cursor {
            position: fixed;
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            opacity: 0.4;
            transition: opacity 0.2s;
        }
        
        .custom-cursor-follower {
            position: fixed;
            width: 32px;
            height: 32px;
            border: 1.5px solid var(--accent);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            transition: all 0.15s;
        }
        
        @media (max-width: 768px) {
            .custom-cursor,
            .custom-cursor-follower {
                display: none;
            }
        }
        
        /* Glassmorphism lebih subtle */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
        }
        
        /* Navigation lebih clean */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-light);
            padding: 1.25rem 0;
            transition: var(--transition);
        }
        
        .navbar.scrolled {
            padding: 0.75rem 0;
            box-shadow: var(--shadow-md);
        }
        
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-dark) !important;
            letter-spacing: -0.5px;
        }
        
        .navbar-brand i {
            color: var(--accent);
            margin-right: 0.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--text-secondary) !important;
            padding: 0.6rem 1.2rem !important;
            border-radius: 8px;
            transition: var(--transition);
            font-size: 0.95rem;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: var(--accent) !important;
            background: var(--primary-light);
        }
        
        .btn {
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition);
            border: 1px solid transparent;
        }
        
        .btn-primary {
            background: var(--accent);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-outline-primary {
            border: 1.5px solid var(--accent);
            color: var(--accent);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--accent);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-light {
            background: white;
            color: var(--primary-dark);
            border: 1px solid var(--border-light);
        }
        
        .btn-light:hover {
            background: var(--light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        /* Hero Section - lebih elegant */
        .hero-section {
            position: relative;
            padding: 6rem 0 4rem;
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            overflow: hidden;
        }
        
        .hero-badge {
            background: var(--primary-light);
            color: var(--accent);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }
        
        .hero-title {
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 1.2rem;
            color: var(--primary-dark);
            font-weight: 800;
        }
        
        .hero-title span {
            color: var(--accent);
            position: relative;
            display: inline-block;
        }
        
        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 0;
            width: 100%;
            height: 8px;
            background: rgba(37, 99, 235, 0.1);
            z-index: -1;
        }
        
        .hero-description {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 550px;
            line-height: 1.7;
        }
        
        .hero-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }
        
        .hero-image:hover {
            box-shadow: var(--shadow-hover);
        }
        
        .hero-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.4s;
        }
        
        .hero-image:hover img {
            transform: scale(1.02);
        }
        
        .floating-card {
            position: absolute;
            bottom: -20px;
            right: -20px;
            background: white;
            padding: 1.2rem 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            max-width: 260px;
            border: 1px solid var(--border-light);
            animation: float 5s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        
        /* Stats Cards - clean */
        .stats-section {
            padding: 4rem 0 2rem;
            margin-top: -1.5rem;
            position: relative;
            z-index: 10;
        }
        
        .stat-card {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 1px solid var(--border-light);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 0.3rem;
            font-family: 'Montserrat', sans-serif;
        }
        
        .stat-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.3rem;
        }
        
        .stat-label small {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 400;
        }
        
        /* Section Headers */
        .section-badge {
            display: inline-block;
            background: var(--primary-light);
            color: var(--accent);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }
        
        .section-title {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
            font-weight: 800;
        }
        
        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Events Section */
        .events-section {
            padding: 5rem 0;
            background: white;
        }
        
        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            border: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
        }
        
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }
        
        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.4s;
        }
        
        .event-card:hover .event-image {
            transform: scale(1.03);
        }
        
        .event-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .event-category {
            display: inline-block;
            background: var(--primary-light);
            color: var(--accent);
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            letter-spacing: 0.3px;
            align-self: flex-start;
        }
        
        .event-title {
            font-size: 1.25rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
            font-weight: 700;
            color: var(--primary-dark);
        }
        
        .event-meta {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .event-meta i {
            color: var(--accent);
            margin-right: 0.5rem;
            width: 16px;
            font-size: 0.9rem;
        }
        
        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-light);
            margin-top: auto;
        }
        
        .event-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--accent);
        }
        
        .price-badge {
            background: var(--accent);
            color: white;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .free-badge {
            background: var(--success);
            color: white;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .btn-sm {
            padding: 0.4rem 1.2rem;
            font-size: 0.8rem;
        }
        
        /* About Section */
        .about-section {
            padding: 5rem 0;
            background: var(--light);
        }
        
        .mission-item {
            display: flex;
            align-items: flex-start;
            gap: 1.2rem;
            padding: 1.2rem;
            background: white;
            border-radius: 16px;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border-light);
        }
        
        .mission-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent);
        }
        
        .mission-icon {
            width: 3.2rem;
            height: 3.2rem;
            background: var(--primary-light);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: white;
        }
        
        .feature-card {
            background: white;
            padding: 2rem 1.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            border: 1px solid var(--border-light);
        }
        
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent);
        }
        
        .feature-icon {
            width: 4.5rem;
            height: 4.5rem;
            background: var(--primary-light);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--accent);
            font-size: 2rem;
        }
        
        .feature-card h4 {
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }
        
        .feature-card .badge {
            background: var(--primary-light) !important;
            color: var(--accent) !important;
            font-weight: 500;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.7rem;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(145deg, #1e293b, #0f172a);
            padding: 4rem 0;
        }
        
        .cta-section h2 {
            color: white;
        }
        
        .cta-section p {
            color: rgba(255,255,255,0.9);
        }
        
        .cta-section .btn-light {
            background: white;
            color: var(--primary-dark);
            border: none;
            padding: 0.8rem 2rem;
        }
        
        .cta-section .btn-light:hover {
            background: #f8fafc;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }
        
        /* Footer */
        .footer {
            background: var(--primary-dark);
            color: #e2e8f0;
            padding: 5rem 0 2rem;
            position: relative;
        }
        
        .footer-logo {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1.2rem;
        }
        
        .footer-logo i {
            color: var(--accent);
        }
        
        .footer-description {
            color: #94a3b8;
            font-size: 0.9rem;
            line-height: 1.7;
        }
        
        .footer-heading {
            color: white;
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.7rem;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.9rem;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-link {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            margin-right: 0.5rem;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .social-link:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            margin-top: 3rem;
            padding-top: 1.5rem;
        }
        
        .footer-bottom p {
            color: #94a3b8;
            font-size: 0.85rem;
        }
        
        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 2.8rem;
            height: 2.8rem;
            background: white;
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            z-index: 1000;
            border: 1px solid var(--border-light);
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: var(--accent);
            color: white;
            transform: translateY(-5px);
        }
        
        /* Modal */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: var(--shadow-lg);
        }
        
        .modal-header {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem;
        }
        
        .modal-header .modal-title {
            color: var(--primary-dark);
            font-weight: 700;
        }
        
        .modal-header .btn-close {
            background: transparent;
            opacity: 0.5;
        }
        
        .option-card {
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            background: white;
        }
        
        .option-card:hover {
            border-color: var(--accent);
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .option-card i {
            color: var(--accent);
            margin-bottom: 1rem;
        }
        
        .option-card h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .option-card p {
            font-size: 0.8rem;
        }
        
        /* Toast */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 9999;
            transform: translateX(400px);
            transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 4px solid var(--accent);
        }
        
        .toast-notification.show {
            transform: translateX(0);
        }
        
        .toast-icon i {
            font-size: 1.5rem;
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            visibility: hidden;
            opacity: 0;
            transition: var(--transition);
        }
        
        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-light);
            border-top: 3px solid var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Animations - minimal */
        [data-aos] {
            opacity: 0;
            transition-property: opacity, transform;
        }
        
        [data-aos].aos-animate {
            opacity: 1;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
        
        /* Utility */
        .text-accent {
            color: var(--accent);
        }
        
        .bg-accent-soft {
            background: var(--primary-light);
        }
        
        .border-accent {
            border-color: var(--accent) !important;
        }
    </style>
</head>
<body>
    <!-- Custom Cursor -->
    <div class="custom-cursor" id="customCursor"></div>
    <div class="custom-cursor-follower" id="customCursorFollower"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-notification" id="toastNotification">
        <div class="toast-icon" id="toastIcon">
            <i class="fas fa-check-circle" style="color: var(--accent);"></i>
        </div>
        <div>
            <h6 class="mb-1 fw-bold" id="toastTitle" style="font-size: 0.9rem;">Berhasil!</h6>
            <p class="mb-0 text-muted small" id="toastMessage">Aksi berhasil dilakukan</p>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-running"></i>
                MARATHON EVENTS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/events') }}">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    
                    @if(session('is_logged_in'))
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                <span>{{ session('user_nama') }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2">
                                @if(session('user_peran') == 'admin' || session('user_peran') == 'superadmin')
                                    <li><a class="dropdown-item py-2" href="{{ url('/admin/dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-accent"></i>Dashboard Admin</a></li>
                                @elseif(session('user_peran') == 'staff')
                                    <li><a class="dropdown-item py-2" href="{{ url('/staff/dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-accent"></i>Dashboard Staff</a></li>
                                @else
                                    <li><a class="dropdown-item py-2" href="{{ url('/my-registrations') }}"><i class="fas fa-ticket-alt me-2 text-accent"></i>Pendaftaran Saya</a></li>
                                    <li><a class="dropdown-item py-2" href="{{ url('/profile') }}"><i class="fas fa-user me-2 text-accent"></i>Profil</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ url('/logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="{{ url('/login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary" href="{{ url('/register') }}">
                                <i class="fas fa-user-plus me-1"></i> Daftar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800">
                    <div class="hero-content">
                        <span class="hero-badge">
                            <i class="fas fa-medal me-2"></i>Premium Running Events Since 2015
                        </span>
                        
                        <h1 class="hero-title">
                            Elevate Your <br>
                            <span>Running Experience</span>
                        </h1>
                        
                        <p class="hero-description">
                            Join the elite community of runners across Indonesia. From scenic 5K fun runs 
                            to championship 42K marathons, experience running at its finest.
                        </p>
                        
                        <div class="hero-actions d-flex flex-wrap gap-3 mb-4">
                            <a href="#events" class="btn btn-primary">
                                <i class="fas fa-running me-2"></i>Explore Events
                            </a>
                            @if(!session('is_logged_in'))
                            <a href="{{ url('/register') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Join Now
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="position-relative">
                        <div class="hero-image">
                            <img src="https://image.Hm.jpg" 
                                 alt="Marathon Runners">
                        </div>
                        
                        <div class="floating-card">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-award fa-2x text-accent"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--primary-dark);">#1 Running Events 2024</h6>
                                    <p class="text-muted mb-0 small">Trusted by 10,000+ runners</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <?php
            $totalEvents = DB::table('lomba')->count();
            $totalUsers = DB::table('pengguna')->count();
            $upcomingEvents = DB::table('lomba')
                ->where('status', 'mendatang')
                ->count();
            ?>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-number">{{ $totalEvents }}+</div>
                        <div class="stat-label">Premium Events</div>
                        <small class="text-muted">Professional race management</small>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-number">{{ $totalUsers }}+</div>
                        <div class="stat-label">Active Runners</div>
                        <small class="text-muted">Strong running community</small>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-number">{{ $upcomingEvents }}</div>
                        <div class="stat-label">Upcoming Events</div>
                        <small class="text-muted">Ready to challenge yourself</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section id="events" class="events-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <span class="section-badge">
                    <i class="fas fa-calendar-alt me-2"></i>Featured Events
                </span>
                <h2 class="section-title">Choose Your Challenge</h2>
                <p class="section-subtitle text-muted mt-3">
                    Experience world-class marathon events with premium facilities, 
                    professional timing systems, and unforgettable race day experience.
                </p>
            </div>
            
            <?php
            $events = DB::table('lomba')
                ->where('status', 'mendatang')
                ->orderBy('tanggal', 'asc')
                ->limit(6)
                ->get();
            ?>
            
            <div class="row g-4 mt-3">
                @if(count($events) > 0)
                    @foreach($events as $index => $event)
                        <?php
                        $totalPendaftar = DB::table('pendaftaran')
                            ->where('id_lomba', $event->id)
                            ->where('status_pendaftaran', 'disetujui')
                            ->count();
                        
                        $kuotaTersedia = $event->kuota_peserta - $totalPendaftar;
                        $harga = $event->harga_reguler ?? $event->harga ?? $event->harga_min ?? 0;
                        $isFreeEvent = ($harga == 0);
                        ?>
                        
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                            <div class="event-card">
                                @if($event->poster_url)
                                    <img src="{{ asset('storage/' . $event->poster_url) }}" 
                                         alt="{{ $event->nama }}" class="event-image">
                                @else
                                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                                         alt="{{ $event->nama }}" class="event-image">
                                @endif
                                <div class="event-content">
                                    <span class="event-category">{{ $event->kategori ?? 'Premium Event' }}</span>
                                    <h3 class="event-title">{{ $event->nama }}</h3>
                                    <div class="event-meta">
                                        <p class="mb-2"><i class="fas fa-calendar-alt"></i> {{ date('d F Y', strtotime($event->tanggal)) }}</p>
                                        <p class="mb-2"><i class="fas fa-map-marker-alt"></i> {{ $event->lokasi ?? 'TBA' }}</p>
                                        <p class="mb-0"><i class="fas fa-users"></i> {{ max(0, $kuotaTersedia) }} spots left</p>
                                    </div>
                                    <div class="event-footer">
                                        @if($isFreeEvent)
                                            <span class="free-badge">
                                                <i class="fas fa-gift me-1"></i> FREE ENTRY
                                            </span>
                                        @else
                                            <span class="event-price">Rp {{ number_format($harga, 0, ',', '.') }}</span>
                                        @endif
                                        <div class="d-flex gap-2">
                                            <a href="{{ url('/event/' . $event->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-info-circle me-1"></i> Details
                                            </a>
                                            @if($isFreeEvent)
                                                <button class="btn btn-sm btn-success" 
                                                        onclick="handleRegistration({{ $event->id }}, 'free', '{{ addslashes($event->nama) }}')">
                                                    <i class="fas fa-user-plus me-1"></i> Join
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-primary" 
                                                        onclick="handleRegistration({{ $event->id }}, 'paid', '{{ addslashes($event->nama) }}')">
                                                    <i class="fas fa-running me-1"></i> Register
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-5">
                        <div class="glass-card p-5">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-muted"></i>
                            <h3 class="mb-2">No Events Available</h3>
                            <p class="text-muted mb-4">We're preparing amazing running events for you. Stay tuned!</p>
                            <button class="btn btn-primary" onclick="showToast('info', 'Info', 'We will notify you when events are available')">
                                <i class="fas fa-bell me-2"></i>Notify Me
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ url('/events') }}" class="btn btn-primary px-5">
                    <i class="fas fa-list me-2"></i>View All Events
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-content">
                        <span class="section-badge">
                            <i class="fas fa-running me-2"></i>Premium Experience
                        </span>
                        <h2 class="section-title mb-3">More Than Just<br>A Marathon</h2>
                        <p class="mb-4" style="color: var(--text-secondary);">
                            Since 2015, Marathon Events has redefined the running experience in Indonesia. 
                            We combine world-class organization with local hospitality to create unforgettable race days.
                        </p>
                        
                        <div class="mission-list mt-4">
                            <div class="mission-item">
                                <div class="mission-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 1rem;">International Standards</h5>
                                    <p class="text-muted mb-0 small">AIMS certified courses, professional timing</p>
                                </div>
                            </div>
                            
                            <div class="mission-item">
                                <div class="mission-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 1rem;">Runner-First Approach</h5>
                                    <p class="text-muted mb-0 small">Medical support, hydration stations every 2.5km</p>
                                </div>
                            </div>
                            
                            <div class="mission-item">
                                <div class="mission-icon">
                                    <i class="fas fa-globe-asia"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1" style="font-size: 1rem;">Sustainable Events</h5>
                                    <p class="text-muted mb-0 small">Eco-friendly initiatives and zero-waste operations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="position-relative">
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1517438476312-10d79c077509?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                 alt="Marathon Community" 
                                 class="img-fluid">
                        </div>
                        <div class="floating-card" style="bottom: -20px; left: -20px; right: auto;">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-users fa-2x text-accent"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--primary-dark);">50+ Premium Events</h6>
                                    <p class="text-muted mb-0 small">Across 15+ cities in Indonesia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <h2 class="section-title">Why Choose Us?</h2>
                <p class="section-subtitle text-muted mt-3">
                    Experience the difference with our premium features designed for runners
                </p>
            </div>
            
            <div class="row g-4 mt-3">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Safety Excellence</h4>
                        <p class="text-muted small mb-3">
                            Comprehensive medical coverage and emergency response teams at every kilometer.
                        </p>
                        <span class="badge">24/7 Medical</span>
                        <span class="badge ms-2">AED Ready</span>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h4>Premium Awards</h4>
                        <p class="text-muted small mb-3">
                            Exclusive finisher medals and high-quality technical shirts for all participants.
                        </p>
                        <span class="badge">Designer Medals</span>
                        <span class="badge ms-2">Premium Kits</span>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h4>Professional Coverage</h4>
                        <p class="text-muted small mb-3">
                            Professional race photography, live tracking, and instant race results.
                        </p>
                        <span class="badge">Free Photos</span>
                        <span class="badge ms-2">Live Tracking</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h2 class="display-5 fw-bold mb-3">Ready to Start Your Journey?</h2>
                    <p class="lead mb-4 opacity-90">Join thousands of runners who have experienced the thrill of our premium events.</p>
                    @if(!session('is_logged_in'))
                        <a href="{{ url('/register') }}" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-user-plus me-2"></i>Create Free Account
                        </a>
                    @else
                        <a href="{{ url('/events') }}" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-running me-2"></i>Browse Events
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="footer-logo">
                            <i class="fas fa-running me-2"></i>MARATHON EVENTS
                        </div>
                        <p class="footer-description">
                            Elevating the running experience in Indonesia since 2015. 
                            Professional race management, premium quality, and unforgettable moments.
                        </p>
                        
                        <div class="social-links mt-4">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/events') }}">Events</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">My Account</h5>
                    <ul class="footer-links">
                        @if(!session('is_logged_in'))
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Create Account</a></li>
                        @else
                        <li><a href="{{ url('/my-registrations') }}">My Registrations</a></li>
                        <li><a href="{{ url('/profile') }}">Profile Settings</a></li>
                        <li><a href="#">Race Results</a></li>
                        <li>
                            <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link text-secondary p-0 text-start" style="text-decoration: none; font-size: 0.9rem;">Logout</button>
                            </form>
                        </li>
                        @endif
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h5 class="footer-heading">Contact Info</h5>
                    <ul class="footer-links">
                        <li class="d-flex align-items-start mb-3">
                            <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                            <span style="font-size: 0.9rem;">Marathon Tower, Jl. Sudirman No. 123, Jakarta Pusat</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone me-3"></i>
                            <span style="font-size: 0.9rem;">+62 21 1234 5678</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope me-3"></i>
                            <span style="font-size: 0.9rem;">info@marathonevents.id</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fas fa-clock me-3"></i>
                            <span style="font-size: 0.9rem;">Mon - Fri: 09:00 - 18:00</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row">
                    <div class="col-md-6 text-md-start">
                        <p class="mb-0">
                            &copy; 2024 <span class="fw-bold text-white">Marathon Events</span>. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">
                            Designed with <i class="fas fa-heart text-danger"></i> for runners
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Registration Options Modal -->
    <div class="modal fade" id="registrationOptionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-running me-2 text-accent"></i>
                        Join The Race
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <span id="eventTypeBadge" class="badge mb-3" style="display: inline-block; padding: 0.5rem 1.2rem; border-radius: 50px;"></span>
                        <h4 id="modalEventName" class="fw-bold" style="font-size: 1.3rem;"></h4>
                        <p class="text-muted mt-2 small">Ready to challenge yourself? Choose your path to get started.</p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="option-card" onclick="chooseRegistrationOption('login')">
                                <div class="mb-2">
                                    <i class="fas fa-sign-in-alt fa-3x text-accent"></i>
                                </div>
                                <h5>Welcome Back!</h5>
                                <p class="text-muted small mb-3">Login to register instantly</p>
                                <button class="btn btn-outline-primary btn-sm w-100">Login Now</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="option-card" onclick="chooseRegistrationOption('register')">
                                <div class="mb-2">
                                    <i class="fas fa-user-plus fa-3x text-accent"></i>
                                </div>
                                <h5>New Runner</h5>
                                <p class="text-muted small mb-3">Join our community and start your journey</p>
                                <button class="btn btn-primary btn-sm w-100">Sign Up Free</button>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selectedEventId">
                    <input type="hidden" id="selectedEventType">
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-light btn-sm px-4" data-bs-dismiss="modal">Continue Browsing</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true,
            offset: 50,
            easing: 'ease-out'
        });

        // Global variables
        let currentEventId = null;
        let currentEventName = null;
        let currentEventType = null;

        // Custom Cursor
        const cursor = document.getElementById('customCursor');
        const cursorFollower = document.getElementById('customCursorFollower');

        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
            cursorFollower.style.left = e.clientX + 'px';
            cursorFollower.style.top = e.clientY + 'px';
        });

        document.addEventListener('mouseenter', () => {
            cursor.style.opacity = '0.4';
            cursorFollower.style.opacity = '0.2';
        });

        document.addEventListener('mouseleave', () => {
            cursor.style.opacity = '0';
            cursorFollower.style.opacity = '0';
        });

        // Navbar scroll effect
        const navbar = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Show toast notification
        function showToast(type, title, message) {
            const toast = document.getElementById('toastNotification');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            toast.classList.add('show');
            
            if (type === 'success') {
                toast.style.borderLeftColor = '#0f766e';
                toastIcon.innerHTML = '<i class="fas fa-check-circle" style="color: #0f766e;"></i>';
            } else if (type === 'error') {
                toast.style.borderLeftColor = '#b91c1c';
                toastIcon.innerHTML = '<i class="fas fa-exclamation-circle" style="color: #b91c1c;"></i>';
            } else {
                toast.style.borderLeftColor = '#2563eb';
                toastIcon.innerHTML = '<i class="fas fa-info-circle" style="color: #2563eb;"></i>';
            }
            
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        }

        // Show loading
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        // Hide loading
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        // Back to top button
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Main registration handler
        function handleRegistration(eventId, eventType, eventName) {
            currentEventId = eventId;
            currentEventName = eventName;
            currentEventType = eventType;
            
            @if(session('is_logged_in'))
                showLoading();
                setTimeout(() => {
                    window.location.href = "{{ url('event') }}/" + eventId + "/register";
                }, 400);
            @else
                showRegistrationOptionsModal(eventId, eventType, eventName);
            @endif
        }

        // Show registration options modal
        function showRegistrationOptionsModal(eventId, eventType, eventName) {
            document.getElementById('modalEventName').textContent = eventName;
            document.getElementById('selectedEventId').value = eventId;
            document.getElementById('selectedEventType').value = eventType;
            
            const eventTypeBadge = document.getElementById('eventTypeBadge');
            if (eventType === 'free') {
                eventTypeBadge.className = 'badge mb-3';
                eventTypeBadge.style.background = '#0f766e';
                eventTypeBadge.style.color = 'white';
                eventTypeBadge.innerHTML = '<i class="fas fa-gift me-2"></i>FREE ENTRY EVENT';
            } else {
                eventTypeBadge.className = 'badge mb-3';
                eventTypeBadge.style.background = '#2563eb';
                eventTypeBadge.style.color = 'white';
                eventTypeBadge.innerHTML = '<i class="fas fa-tag me-2"></i>PREMIUM EVENT';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('registrationOptionsModal'));
            modal.show();
        }

        // Choose registration option
        function chooseRegistrationOption(option) {
            const eventId = document.getElementById('selectedEventId').value;
            const eventName = currentEventName;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('registrationOptionsModal'));
            modal.hide();
            
            showLoading();
            
            setTimeout(() => {
                if (option === 'login') {
                    window.location.href = "{{ url('login') }}?event_id=" + eventId + "&event_name=" + encodeURIComponent(eventName);
                } else if (option === 'register') {
                    window.location.href = "{{ url('register') }}?event_id=" + eventId + "&event_name=" + encodeURIComponent(eventName);
                }
            }, 400);
        }

        // Document ready
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('success', 'Success!', '{{ session("success") }}');
            @endif
            
            @if(session('error'))
                showToast('error', 'Error!', '{{ session("error") }}');
            @endif
            
            @if(session('info'))
                showToast('info', 'Information', '{{ session("info") }}');
            @endif
        });
    </script>
</body>
</html>