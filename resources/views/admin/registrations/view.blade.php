<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Peserta - {{ $registration->kode_pendaftaran ?? 'N/A' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f8fafc;
            color: #1e293b;
        }
        .glass-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
        }
        .premium-card {
            background: white;
            border-radius: 2rem;
            border: 1px solid rgba(59, 130, 246, 0.08);
            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.05);
        }
        .status-badge { 
            padding: 8px 20px; 
            border-radius: 99px; 
            font-size: 11px; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-menunggu { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
        .status-disetujui, .status-lunas { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .status-ditolak, .status-gagal { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }
        
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .premium-card { border: 1px solid #eee; box-shadow: none; }
        }
    </style>
</head>
<body class="antialiased">

    <header class="glass-header sticky top-0 z-50 no-print">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.registrations.index') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Detail Pendaftaran</h1>
                    <p class="text-xs font-bold text-blue-500 uppercase tracking-widest">{{ $registration->kode_pendaftaran }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center">
                    <i class="fas fa-print mr-2"></i> Cetak Dokumen
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <section class="premium-card p-8">
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-blue-200">
                                {{ strtoupper(substr($registration->nama_lengkap ?? 'N', 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-800">{{ $registration->nama_lengkap ?? 'N/A' }}</h2>
                                <p class="text-slate-400 font-medium italic">{{ $registration->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <span class="status-badge status-{{ $registration->status_pendaftaran }}">
                            {{ $registration->status_pendaftaran }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 border-t border-slate-50 pt-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nomor Telepon</p>
                            <p class="font-bold text-slate-700">{{ $registration->telepon ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ukuran Jersey</p>
                            <p class="font-bold text-blue-600 bg-blue-50 inline-block px-3 py-1 rounded-lg">{{ $registration->ukuran_jersey ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</p>
                            <p class="font-bold text-slate-700 leading-relaxed">{{ $registration->alamat ?? 'N/A' }}</p>
                        </div>
                    </div>
                </section>

                <section class="premium-card p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-running"></i>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Detail Kompetisi</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Event</p>
                                <p class="font-extrabold text-slate-800 text-lg">{{ $registration->nama ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori Lomba</p>
                                <p class="font-bold text-slate-700">{{ $registration->kategori ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal & Waktu</p>
                                <p class="font-bold text-slate-700">
                                    <i class="far fa-calendar-alt text-blue-500 mr-2"></i>
                                    {{ isset($registration->tanggal) ? date('d F Y', strtotime($registration->tanggal)) : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Pelaksanaan</p>
                                <p class="font-bold text-slate-700">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                    {{ $registration->lokasi ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="space-y-8">
                
                <div class="premium-card p-8 border-l-4 border-blue-600">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Status Transaksi</p>
                    <div class="flex items-center justify-between mb-6">
                        <span class="status-badge status-{{ $registration->status_pembayaran ?? 'menunggu' }}">
                            {{ $registration->status_pembayaran ?? 'Menunggu' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Biaya Pendaftaran</p>
                        <p class="text-3xl font-black text-blue-600">Rp {{ number_format($registration->package_price ?? 0, 0, ',', '.') }}</p>
                        <p class="text-[10px] font-bold text-slate-400 mt-2 italic">* Paket: {{ $registration->package_name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="premium-card p-8">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Lampiran Bukti</h3>
                    @if(isset($payment) && $payment->bukti_pembayaran)
                        <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="group block relative rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                            <img src="{{ asset('storage/' . $payment->bukti_pembayaran) }}" class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-blue-600/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white font-bold text-xs"><i class="fas fa-search-plus mr-2"></i> Perbesar Gambar</span>
                            </div>
                        </a>
                    @else
                        <div class="p-6 border-2 border-dashed border-slate-100 rounded-2xl text-center">
                            <i class="fas fa-image text-slate-200 text-3xl mb-3"></i>
                            <p class="text-xs font-bold text-slate-400 uppercase">Belum ada lampiran</p>
                        </div>
                    @endif
                </div>

                <div class="p-6 text-center">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Didaftarkan Pada</p>
                    <p class="text-xs font-bold text-slate-400 mt-1">{{ date('d/m/Y H:i', strtotime($registration->created_at)) }}</p>
                </div>

            </div>
        </div>
    </main>

</body>
</html>