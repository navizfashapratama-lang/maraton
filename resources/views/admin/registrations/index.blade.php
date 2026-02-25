<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pusat Kendali - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f0f7ff;
            background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(59, 130, 246, 0.1);
            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.1);
        }

        .tab-active { 
            color: #2563eb;
            background: white;
            border-bottom: 4px solid #2563eb;
        }

        .status-badge { 
            padding: 6px 14px; 
            border-radius: 99px; 
            font-size: 10px; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-menunggu { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
        .status-terverifikasi, .status-disetujui, .status-lunas { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .status-ditolak { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

        .btn-blue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
        }

        .hover-lift { transition: all 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }

        /* Smooth tab animation */
        .tab-content { animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased">

    <div class="max-w-7xl mx-auto px-4 py-12">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
            <div class="flex items-center space-x-5">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-xl shadow-blue-200">
                    <i class="fas fa-desktop text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight font-['Clash_Display']">Command Center</h1>
                    <p class="text-blue-500 font-bold text-sm tracking-widest uppercase">Admin Verification Portal</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-white border-2 border-slate-100 px-6 py-3 rounded-2xl font-bold text-slate-600 hover:text-blue-600 hover:border-blue-200 transition-all flex items-center shadow-sm">
                <i class="fas fa-arrow-left mr-3"></i> Dashboard
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2rem] hover-lift border-b-4 border-yellow-400">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pending Files</p>
                <div class="flex justify-between items-end">
                    <h3 class="text-4xl font-black text-slate-800">{{ $stats['pending'] ?? 0 }}</h3>
                    <i class="fas fa-hourglass-half text-yellow-400 text-2xl mb-1"></i>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] hover-lift border-b-4 border-green-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Total Revenue</p>
                <div class="flex justify-between items-end">
                    <h3 class="text-3xl font-black text-green-600">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</h3>
                    <i class="fas fa-wallet text-green-500 text-2xl mb-1"></i>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] hover-lift border-b-4 border-blue-600">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Total Athletes</p>
                <div class="flex justify-between items-end">
                    <h3 class="text-4xl font-black text-blue-600">{{ $stats['total'] ?? 0 }}</h3>
                    <i class="fas fa-users text-blue-600 text-2xl mb-1"></i>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden">
            
            <div class="flex bg-slate-50/50 border-b border-slate-100">
                <button onclick="switchTab('reg')" id="btn-reg" 
                        class="flex-1 py-6 text-xs font-black tracking-widest transition-all tab-active uppercase">
                    <i class="fas fa-user-check mr-3"></i> Registrations
                </button>
                <button onclick="switchTab('pay')" id="btn-pay" 
                        class="flex-1 py-6 text-xs font-black tracking-widest text-slate-400 hover:text-blue-500 transition-all uppercase">
                    <i class="fas fa-receipt mr-3"></i> Payments
                </button>
            </div>

            <div class="p-4">
                
                <div id="tab-reg" class="tab-content overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="p-8">Athletes Info</th>
                                <th class="p-8">Event Details</th>
                                <th class="p-8">Status</th>
                                <th class="p-8 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($registrations as $reg)
                            <tr class="hover:bg-blue-50/30 transition-all">
                                <td class="p-8">
                                    <div class="text-xs font-black text-blue-600 mb-1 font-mono tracking-tighter">{{ $reg->kode_pendaftaran }}</div>
                                    <div class="font-extrabold text-slate-800 text-lg leading-tight">{{ $reg->nama_lengkap }}</div>
                                </td>
                                <td class="p-8">
                                    <div class="font-bold text-slate-700">{{ $reg->event_nama }}</div>
                                    <div class="text-[11px] text-blue-500 font-black mt-1 uppercase">{{ $reg->package_name }}</div>
                                </td>
                                <td class="p-8">
                                    <span class="status-badge status-{{ $reg->status_pendaftaran }}">{{ $reg->status_pendaftaran }}</span>
                                </td>
                                <td class="p-8 text-center">
                                    <a href="{{ route('admin.registrations.view', $reg->id) }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-slate-100 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="4" class="p-20 text-center text-slate-400 font-bold uppercase tracking-widest">No registration data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                        {{ $registrations->links() }}
                    </div>
                </div>

                <div id="tab-pay" class="tab-content hidden overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="p-8 w-16">#</th>
                                <th class="p-8">Transaction Info</th>
                                <th class="p-8">Event</th>
                                <th class="p-8">Method</th>
                                <th class="p-8">Amount</th>
                                <th class="p-8 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($payments ?? [] as $index => $pay)
                            <tr class="hover:bg-blue-50/30 transition-all">
                                <td class="p-8 text-slate-300 font-black">{{ $index + 1 }}</td>
                                <td class="p-8">
                                    <div class="text-xs font-black text-blue-600 mb-1">{{ $pay->kode_pembayaran }}</div>
                                    <div class="font-extrabold text-slate-800 uppercase leading-tight">{{ $pay->user_nama }}</div>
                                </td>
                                <td class="p-8 font-bold text-slate-600">{{ $pay->event_nama }}</td>
                                <td class="p-8">
                                    <span class="bg-white px-4 py-2 rounded-xl text-[10px] font-black text-blue-600 border border-blue-100 shadow-sm uppercase">
                                        {{ $pay->metode_pembayaran }}
                                    </span>
                                </td>
                                <td class="p-8">
                                    <div class="font-black text-green-600 text-lg leading-none">Rp {{ number_format($pay->jumlah, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold mt-2 uppercase">
                                        <i class="far fa-clock mr-1"></i> {{ $pay->created_at ? date('d M Y', strtotime($pay->created_at)) : '-' }}
                                    </div>
                                </td>
                                <td class="p-8 text-center">
                                    <span class="status-badge status-{{ $pay->status }}">{{ $pay->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                            <i class="fas fa-folder-open text-3xl text-blue-200"></i>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase text-xs tracking-widest">Transaction History is Empty</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="mt-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">
            &copy; 2024 Marathon System • Secure Encryption Enabled
        </p>
    </div>

    <script>
        function switchTab(type) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('#btn-reg, #btn-pay').forEach(el => {
                el.classList.remove('tab-active');
                el.classList.add('text-slate-400');
                el.classList.remove('bg-white');
            });

            const targetTab = document.getElementById('tab-' + type);
            const targetBtn = document.getElementById('btn-' + type);
            
            targetTab.classList.remove('hidden');
            targetBtn.classList.add('tab-active');
            targetBtn.classList.remove('text-slate-400');
        }
    </script>
</body>
</html>