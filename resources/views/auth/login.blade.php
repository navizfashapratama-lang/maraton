<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Marathon Events</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#6366f1',
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        secondary: {
                            DEFAULT: '#8b5cf6',
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        }
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'slide-in-right': 'slideInRight 0.8s ease-out',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        'gradient-diagonal': 'linear-gradient(135deg, var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            min-height: 100vh;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .form-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            height: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.6);
        }
        
        .btn-demo {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .btn-demo:hover {
            transform: translateY(-2px) scale(1.02);
            border-color: currentColor;
        }
        
        .feature-icon {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        /* Floating elements */
        .floating-element {
            position: fixed;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(5px);
            z-index: 0;
            pointer-events: none;
        }
        
        /* Ensure content is above floating elements */
        .content-container {
            position: relative;
            z-index: 10;
        }
        
        /* Smooth scroll */
        .smooth-scroll {
            scroll-behavior: smooth;
        }
        
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        /* Form container adjustments */
        .form-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1rem;
        }
        
        @media (min-width: 640px) {
            .form-content {
                padding: 1.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .form-content {
                padding: 2rem;
            }
        }
        
        @media (min-width: 1024px) {
            .form-content {
                padding: 3rem;
            }
        }
    </style>
</head>
<body class="min-h-screen p-4 md:p-6 lg:p-8">
    
    <!-- Floating Background Elements -->
    <div class="floating-element w-64 h-64 top-10 left-10 animate-float opacity-60"></div>
    <div class="floating-element w-80 h-80 bottom-10 right-10 animate-float opacity-40" style="animation-delay: 1s"></div>
    <div class="floating-element w-48 h-48 top-1/2 left-1/3 animate-float opacity-50" style="animation-delay: 2s"></div>
    <div class="floating-element w-32 h-32 bottom-1/4 left-1/4 animate-float opacity-30" style="animation-delay: 3s"></div>
    
    <!-- Main Container -->
    <div class="content-container w-full max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 rounded-3xl overflow-hidden shadow-2xl min-h-[90vh] max-h-[90vh]">
            
            <!-- Left Side - Brand & Info (Hidden on mobile) -->
            <div class="hidden lg:block relative overflow-hidden bg-gradient-to-br from-primary-600 to-secondary-600">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10" style="
                    background-image: url('data:image/svg+xml,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"white\" fill-opacity=\"0.4\"><circle cx=\"30\" cy=\"30\" r=\"2\"/><circle cx=\"50\" cy=\"10\" r=\"1\"/><circle cx=\"10\" cy=\"50\" r=\"1\"/></g></svg>');
                "></div>
                
                <!-- Content Container -->
                <div class="relative z-10 p-12 h-full flex flex-col justify-between overflow-y-auto no-scrollbar">
                    
                    <!-- Brand Section -->
                    <div class="mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-2xl mb-6 backdrop-blur-sm">
                            <i class="fas fa-running text-white text-3xl"></i>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold text-white mb-4">
                            Marathon<br>
                            <span class="text-yellow-300">Events</span>
                        </h1>
                        <p class="text-white/80 text-lg">
                            Platform terbaik untuk pelari Indonesia. <br>
                            Bergabung dengan komunitas yang penuh semangat!
                        </p>
                    </div>
                    
                    <!-- Features Section -->
                    <div class="space-y-6 mb-8">
                        <div class="flex items-center group">
                            <div class="feature-icon mr-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-1">Event Premium</h3>
                                <p class="text-white/70">Event marathon terbaik di seluruh Indonesia</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center group">
                            <div class="feature-icon mr-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-1">Komunitas Aktif</h3>
                                <p class="text-white/70">50K+ pelari sudah bergabung</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center group">
                            <div class="feature-icon mr-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-1">Track Progress</h3>
                                <p class="text-white/70">Pantau perkembangan lari Anda</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quote Section -->
                    <div class="mt-auto glass-card p-6 rounded-2xl">
                        <div class="flex items-start">
                            <i class="fas fa-quote-left text-white/50 text-2xl mr-3 mt-1"></i>
                            <div>
                                <p class="text-white italic text-lg mb-2">
                                    "Setiap langkah adalah perjalanan, setiap finish adalah awal baru."
                                </p>
                                <p class="text-white/70">- Komunitas Pelari Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="form-card h-full">
                <div class="form-content">
                    <!-- Mobile Header -->
                    <div class="lg:hidden mb-6">
                        <a href="{{ url('/') }}" class="inline-flex items-center text-primary-600 hover:text-primary-800 mb-4 text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Beranda
                        </a>
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-2xl mb-3">
                                <i class="fas fa-running text-white text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold gradient-text">Marathon Events</h2>
                            <p class="text-gray-600 text-sm">Masuk ke akun Anda</p>
                        </div>
                    </div>
                    
                    <!-- Form Header -->
                    <div class="mb-6 md:mb-8">
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">
                            Selamat Datang
                            <span class="block gradient-text">Kembali!</span>
                        </h1>
                        <p class="text-gray-600 text-sm md:text-base">
                            Silakan masuk untuk mengakses dashboard dan event Anda
                        </p>
                    </div>
                    
                    <!-- Alerts Section -->
                    <div class="mb-6">
                        @if(session('success'))
                        <div class="mb-4 p-3 md:p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl md:rounded-2xl shadow-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-green-100 rounded-lg md:rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 text-sm md:text-base"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-green-800 text-sm md:text-base">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="p-3 md:p-4 bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 rounded-xl md:rounded-2xl shadow-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 md:w-10 md:h-10 bg-red-100 rounded-lg md:rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation text-red-600 text-sm md:text-base"></i>
                                </div>
                                <div class="flex-1">
                                    @foreach($errors->all() as $error)
                                    <p class="text-red-700 text-sm md:text-base">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4 md:space-y-6" id="loginForm">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-primary-500 transition-colors text-sm md:text-base"></i>
                                </div>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    class="input-glow w-full pl-10 md:pl-12 pr-3 md:pr-4 py-3 md:py-4 bg-gray-50/50 border-2 border-gray-200 rounded-lg md:rounded-xl focus:border-primary-500 focus:bg-white outline-none transition-all text-sm md:text-base"
                                    placeholder="your@email.com"
                                    required
                                    autofocus>
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 md:pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-primary-500 transition-colors text-sm md:text-base"></i>
                                </div>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password"
                                    class="input-glow w-full pl-10 md:pl-12 pr-10 md:pr-12 py-3 md:py-4 bg-gray-50/50 border-2 border-gray-200 rounded-lg md:rounded-xl focus:border-primary-500 focus:bg-white outline-none transition-all text-sm md:text-base"
                                    placeholder="••••••••"
                                    required>
                                <button 
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3 md:pr-4 flex items-center text-gray-400 hover:text-primary-600 transition-colors">
                                    <i class="fas fa-eye text-sm md:text-base" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Options -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input 
                                        type="checkbox" 
                                        name="remember" 
                                        id="remember"
                                        class="sr-only peer">
                                    <div class="w-4 h-4 md:w-5 md:h-5 bg-gray-200 rounded peer-checked:bg-primary-500 transition-colors"></div>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>
                                <span class="ml-2 text-gray-700 text-sm md:text-base group-hover:text-gray-900 transition-colors">
                                    Ingat saya
                                </span>
                            </label>
                            <a href="#" class="text-primary-600 hover:text-primary-800 font-medium transition-colors text-sm md:text-base group">
                                Lupa password?
                                <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        
                        <!-- Submit Button -->
                        <button 
                            type="submit"
                            id="submitBtn"
                            class="btn-primary w-full text-white font-semibold py-3 md:py-4 px-6 rounded-xl md:rounded-xl flex items-center justify-center group mt-4 md:mt-6 text-sm md:text-base">
                            <i class="fas fa-sign-in-alt mr-2 md:mr-3 group-hover:rotate-12 transition-transform"></i>
                            <span id="btnText">Masuk Sekarang</span>
                            <div id="loadingSpinner" class="hidden w-4 h-4 md:w-5 md:h-5 border-2 border-white border-t-transparent rounded-full animate-spin ml-2 md:ml-3"></div>
                        </button>
                    </form>
                    
                    <!-- Demo Accounts -->
                    <div class="mt-6 md:mt-8">
                        <div class="text-center mb-4 md:mb-6">
                            <div class="inline-flex items-center px-3 py-1.5 md:px-4 md:py-2 bg-gradient-to-r from-primary-50 to-secondary-50 rounded-full">
                                <i class="fas fa-key text-primary-500 mr-2 text-sm"></i>
                                <span class="text-xs md:text-sm font-semibold text-gray-700">Coba Akun Demo</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 md:gap-3">
                            <button 
                                onclick="fillDemo('admin')"
                                class="btn-demo p-2 md:p-4 bg-gradient-to-br from-red-50 to-pink-50 text-red-600 rounded-lg md:rounded-xl shadow-sm hover:shadow-md text-xs md:text-sm">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 md:w-12 md:h-12 bg-gradient-to-br from-red-100 to-pink-100 rounded-lg md:rounded-xl flex items-center justify-center mb-1 md:mb-3">
                                        <i class="fas fa-user-shield text-red-500 text-xs md:text-lg"></i>
                                    </div>
                                    <span class="font-semibold truncate w-full text-center">Admin</span>
                                    <span class="text-[10px] md:text-xs text-red-400 mt-1">Full Access</span>
                                </div>
                            </button>
                            
                            <button 
                                onclick="fillDemo('staff')"
                                class="btn-demo p-2 md:p-4 bg-gradient-to-br from-yellow-50 to-amber-50 text-yellow-600 rounded-lg md:rounded-xl shadow-sm hover:shadow-md text-xs md:text-sm">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 md:w-12 md:h-12 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-lg md:rounded-xl flex items-center justify-center mb-1 md:mb-3">
                                        <i class="fas fa-user-tie text-yellow-500 text-xs md:text-lg"></i>
                                    </div>
                                    <span class="font-semibold truncate w-full text-center">Staff</span>
                                    <span class="text-[10px] md:text-xs text-yellow-400 mt-1">Staff Access</span>
                                </div>
                            </button>
                            
                            <button 
                                onclick="fillDemo('peserta')"
                                class="btn-demo p-2 md:p-4 bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 rounded-lg md:rounded-xl shadow-sm hover:shadow-md text-xs md:text-sm">
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 md:w-12 md:h-12 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg md:rounded-xl flex items-center justify-center mb-1 md:mb-3">
                                        <i class="fas fa-user text-blue-500 text-xs md:text-lg"></i>
                                    </div>
                                    <span class="font-semibold truncate w-full text-center">Peserta</span>
                                    <span class="text-[10px] md:text-xs text-blue-400 mt-1">User Access</span>
                                </div>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Register Section -->
                    <div class="mt-6 md:mt-8 pt-4 md:pt-8 border-t border-gray-200/50">
                        <div class="text-center">
                            <p class="text-gray-700 text-sm md:text-base mb-3 md:mb-4">
                                <i class="fas fa-user-plus text-primary-500 mr-2"></i>
                                Belum punya akun?
                            </p>
                            <a 
                                href="{{ route('register') }}"
                                class="inline-flex items-center justify-center w-full py-3 md:py-4 border-2 border-dashed border-primary-300 text-primary-600 hover:bg-primary-50 hover:border-primary-500 font-semibold rounded-lg md:rounded-xl transition-all group text-sm md:text-base">
                                <i class="fas fa-user-plus mr-2 md:mr-3 group-hover:scale-110 transition-transform"></i>
                                Daftar Akun Peserta Baru
                                <i class="fas fa-arrow-right ml-2 md:ml-3 opacity-0 group-hover:opacity-100 translate-x-[-10px] group-hover:translate-x-0 transition-all"></i>
                            </a>
                            <p class="text-xs md:text-sm text-gray-500 mt-2 md:mt-3">
                                Gratis daftar. Mulai jelajahi event marathon sekarang!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Fill Demo Credentials
        function fillDemo(role) {
            const credentials = {
                'admin': { 
                    email: 'admin@marathon.id', 
                    password: 'Admin123!',
                    name: 'Administrator'
                },
                'staff': { 
                    email: 'staff@marathon.id', 
                    password: 'Staff123!',
                    name: 'Staff Event'
                },
                'peserta': { 
                    email: 'peserta@marathon.id', 
                    password: 'Peserta123!',
                    name: 'Budi Santoso'
                }
            };
            
            // Fill form
            document.querySelector('input[name="email"]').value = credentials[role].email;
            document.getElementById('password').value = credentials[role].password;
            
            // Show notification
            showNotification(`Halo ${credentials[role].name}! Akun demo ${role} telah dimasukkan. Klik "Masuk Sekarang" untuk melanjutkan.`);
            
            // Highlight form
            document.querySelector('input[name="email"]').focus();
        }
        
        // Show Notification
        function showNotification(message) {
            // Remove existing notification
            const existing = document.querySelector('.demo-notif');
            if (existing) existing.remove();
            
            // Create notification
            const notification = document.createElement('div');
            notification.className = 'demo-notif mt-4 p-3 md:p-4 bg-gradient-to-r from-primary-50 to-secondary-50 border border-primary-200 rounded-xl md:rounded-2xl shadow-sm animate-fade-in-up';
            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-primary-100 rounded-lg md:rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-user-check text-primary-600 text-sm md:text-base"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-primary-800 font-medium text-sm md:text-base">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-primary-400 hover:text-primary-600 ml-2">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            `;
            
            // Insert after form
            const form = document.getElementById('loginForm');
            form.parentNode.insertBefore(notification, form.nextSibling);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        }
        
        // Form Submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('loadingSpinner');
            
            // Show loading state
            btn.disabled = true;
            btnText.textContent = 'Memproses...';
            spinner.classList.remove('hidden');
            btn.classList.add('opacity-90');
            
            // Allow form to submit normally
        });
        
        // Add focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-primary-200', 'ring-opacity-50');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-primary-200', 'ring-opacity-50');
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('[class*="from-green-50"], [class*="from-red-50"]').forEach(alert => {
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
        
        // Add ripple effect to buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function(e) {
                // Skip for disabled buttons
                if (this.disabled) return;
                
                const rect = this.getBoundingClientRect();
                const ripple = document.createElement('span');
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    top: ${y}px;
                    left: ${x}px;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    if (ripple.parentNode === this) {
                        this.removeChild(ripple);
                    }
                }, 600);
            });
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Adjust floating elements on resize
        window.addEventListener('resize', function() {
            document.querySelectorAll('.floating-element').forEach(el => {
                el.style.display = 'block';
            });
        });
        
        // Initialize with proper sizing
        document.addEventListener('DOMContentLoaded', function() {
            // Set min height for mobile
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
            
            // Adjust main container height
            const mainContainer = document.querySelector('.content-container > div');
            if (mainContainer) {
                mainContainer.style.minHeight = 'calc(100vh - 2rem)';
                if (window.innerHeight < 600) {
                    mainContainer.style.minHeight = '100vh';
                }
            }
        });
        
        // Add ripple animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            /* Better scrollbar for desktop */
            @media (min-width: 1024px) {
                ::-webkit-scrollbar {
                    width: 8px;
                }
                
                ::-webkit-scrollbar-track {
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb {
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 4px;
                }
                
                ::-webkit-scrollbar-thumb:hover {
                    background: rgba(255, 255, 255, 0.4);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>