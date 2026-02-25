<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran - Marathon Events</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            min-height: 100vh;
        }
        
        .page-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 1.5rem 1.5rem;
            margin-bottom: 2rem;
        }
        
        .payment-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
        }
        
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .status-badge {
            padding: 0.35rem 1rem;
            border-radius: 50rem;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-verified { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
        
        .search-box {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }
        
        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: #eef2ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary);
            font-size: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-running me-2"></i>
                <span style="color: #4361ee;">MARATHON</span>EVENTS
            </a>
            <div>
                <a href="{{ url('/my-registrations') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Pembayaran
                    </h1>
                    <p class="mb-0">Lihat semua riwayat pembayaran event yang pernah Anda lakukan</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ url('/my-registrations') }}" class="btn btn-light">
                        <i class="fas fa-running me-2"></i>Pendaftaran Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Search Filter -->
        <div class="search-box mb-4">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select class="form-control" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="terverifikasi">Terverifikasi</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Cari Event atau Kode</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari event atau kode pembayaran...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Urutkan</label>
                    <select class="form-control" id="sortFilter">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        <option value="tertinggi">Nominal Tertinggi</option>
                        <option value="terendah">Nominal Terendah</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary w-100" onclick="filterPayments()">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment List -->
        <div id="paymentList">
            @if($payments->count() > 0)
                @foreach($payments as $payment)
                    <div class="payment-card mb-3" 
                         data-status="{{ $payment->status }}"
                         data-search="{{ strtolower($payment->event_nama . ' ' . $payment->kode_pembayaran) }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="text-center">
                                        <div class="fw-bold text-primary mb-1">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</div>
                                        <small class="text-muted">{{ date('d M Y', strtotime($payment->tanggal_transfer)) }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="fw-bold mb-1">{{ $payment->event_nama }}</h6>
                                    <div class="small text-muted">
                                        <i class="fas fa-hashtag me-1"></i>{{ $payment->kode_pembayaran }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <i class="fas fa-user me-1 text-muted"></i>
                                        <span class="small">{{ $payment->nama_pembayar }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-university me-1"></i>{{ $payment->bank_tujuan }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    @if($payment->status == 'menunggu')
                                        <span class="status-badge badge-pending">
                                            <i class="fas fa-clock me-1"></i>Menunggu
                                        </span>
                                    @elseif($payment->status == 'terverifikasi')
                                        <span class="status-badge badge-verified">
                                            <i class="fas fa-check me-1"></i>Terverifikasi
                                        </span>
                                    @elseif($payment->status == 'ditolak')
                                        <span class="status-badge badge-rejected">
                                            <i class="fas fa-times me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-1 text-end">
                                    <a href="{{ url('/payment/status/' . $payment->kode_pembayaran) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <nav class="mt-4">
                    {{ $payments->links() }}
                </nav>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Belum Ada Riwayat Pembayaran</h4>
                    <p class="text-muted mb-4">Anda belum melakukan pembayaran untuk event apapun.</p>
                    <a href="{{ url('/events') }}" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Cari Event
                    </a>
                </div>
            @endif
        </div>

        <!-- Statistics -->
        @if($payments->count() > 0)
            <div class="row mt-5">
                <div class="col-md-3">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <h3 class="text-primary fw-bold">{{ $payments->total() }}</h3>
                            <p class="text-muted mb-0">Total Pembayaran</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <h3 class="text-success fw-bold">
                                {{ $payments->where('status', 'terverifikasi')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Terverifikasi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <h3 class="text-warning fw-bold">
                                {{ $payments->where('status', 'menunggu')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Menunggu</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light border-0">
                        <div class="card-body text-center">
                            <h3 class="text-danger fw-bold">
                                {{ $payments->where('status', 'ditolak')->count() }}
                            </h3>
                            <p class="text-muted mb-0">Ditolak</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="text-center">
                <p class="mb-0 small">
                    &copy; 2024 Marathon Events. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Filter payments
        function filterPayments() {
            const status = document.getElementById('statusFilter').value;
            const search = document.getElementById('searchInput').value.toLowerCase();
            const sort = document.getElementById('sortFilter').value;
            
            const paymentCards = document.querySelectorAll('.payment-card');
            
            paymentCards.forEach(card => {
                const cardStatus = card.dataset.status;
                const cardSearch = card.dataset.search;
                
                let show = true;
                
                // Filter by status
                if (status && cardStatus !== status) {
                    show = false;
                }
                
                // Filter by search
                if (search && !cardSearch.includes(search)) {
                    show = false;
                }
                
                // Show/hide card
                if (show) {
                    card.style.display = 'block';
                    // Add delay for animation
                    setTimeout(() => {
                        card.style.opacity = '1';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // Count visible cards
            const visibleCards = document.querySelectorAll('.payment-card[style*="display: block"]');
            
            // Show empty state if no cards visible
            const emptyState = document.querySelector('.empty-state');
            if (visibleCards.length === 0) {
                if (!emptyState) {
                    showEmptyState();
                }
            } else {
                if (emptyState) {
                    emptyState.remove();
                }
            }
        }
        
        function showEmptyState() {
            const paymentList = document.getElementById('paymentList');
            const emptyHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Tidak Ditemukan</h4>
                    <p class="text-muted mb-4">Tidak ada riwayat pembayaran yang sesuai dengan filter.</p>
                    <button onclick="resetFilters()" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Reset Filter
                    </button>
                </div>
            `;
            paymentList.innerHTML = emptyHTML;
        }
        
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('sortFilter').value = 'terbaru';
            
            // Reload page
            location.reload();
        }
        
        // Auto filter on input change
        document.getElementById('searchInput').addEventListener('input', filterPayments);
        document.getElementById('statusFilter').addEventListener('change', filterPayments);
        document.getElementById('sortFilter').addEventListener('change', filterPayments);
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add search data attribute to payment cards
            document.querySelectorAll('.payment-card').forEach(card => {
                const eventName = card.querySelector('h6').textContent;
                const paymentCode = card.querySelector('.small').textContent;
                card.dataset.search = (eventName + ' ' + paymentCode).toLowerCase();
            });
        });
    </script>
</body>
</html>