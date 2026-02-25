<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opsi Pendaftaran - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 animate-fade-in">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-center text-white">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <i class="fas fa-running text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold">Siap untuk Berlari?</h2>
            <p class="text-blue-100 mt-2 text-sm">Pilih cara Anda untuk melanjutkan pendaftaran event ini.</p>
        </div>

        <div class="p-8 space-y-4">
            <a href="{{ route('login') }}" class="group block p-4 border-2 border-slate-100 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fas fa-sign-in-alt text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-800">Sudah Punya Akun</h3>
                        <p class="text-xs text-slate-500 italic">Masuk untuk pendaftaran lebih cepat.</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 group-hover:text-blue-500"></i>
                </div>
            </a>

            <a href="{{ route('register') }}" class="group block p-4 border-2 border-slate-100 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-800">Daftar Akun Baru</h3>
                        <p class="text-xs text-slate-500 italic">Buat akun untuk mengelola riwayat event.</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 group-hover:text-green-500"></i>
                </div>
            </a>

            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-slate-200"></span></div>
                <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-slate-400 font-medium">Atau</span></div>
            </div>

            <button onclick="window.history.back()" class="w-full py-3 text-slate-600 hover:text-slate-800 text-sm font-medium transition-colors">
                 Kembali ke Detail Event
            </button>
        </div>

        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Marathon Event Management System</p>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</body>
</html>