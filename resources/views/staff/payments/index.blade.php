<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembayaran - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-terverifikasi { background-color: #d4edda; color: #155724; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .status-kadaluarsa { background-color: #d6d8d9; color: #1b1e21; }
        .payment-method {
            display: inline-block;
            padding: 3px 8px;
            background: #e9ecef;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        .amount {
            font-weight: 600;
            color: #28a745;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('staff.dashboard') }}">
                <i class="fas fa-running"></i> Marathon System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.events.index') }}">
                            <i class="fas fa-calendar-alt"></i> Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.registrations.index') }}">
                            <i class="fas fa-users"></i> Pendaftaran
                        </a>
                    </li>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-credit-card"></i> Pembayaran
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="{{ route('staff.payments.index') }}">Semua Pembayaran</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.payments.index') }}?status=menunggu">Menunggu Verifikasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.payments.index') }}?status=terverifikasi">Terverifikasi</a></li>
                            <li><a class="dropdown-item" href="{{ route('staff.payments.index') }}?status=ditolak">Ditolak</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.packages.index') }}">
                            <i class="fas fa-box"></i> Paket
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-credit-card text-primary"></i> Kelola Pembayaran
            </h1>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Export ke Excel</a></li>
                    <li><a class="dropdown-item" href="#">Export ke PDF</a></li>
                </ul>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Pembayaran</h6>
                                <h3>{{ $stats['total'] }}</h3>
                            </div>
                            <i class="fas fa-wallet fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Menunggu</h6>
                                <h3>{{ $stats['pending'] }}</h3>
                            </div>
                            <i class="fas fa-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Terverifikasi</h6>
                                <h3>{{ $stats['verified'] }}</h3>
                            </div>
                            <i class="fas fa-check-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Ditolak</h6>
                                <h3>{{ $stats['rejected'] }}</h3>
                            </div>
                            <i class="fas fa-times-circle fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('staff.payments.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari kode pembayaran, nama peserta, atau event..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>
                                    Menunggu
                                </option>
                                <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>
                                    Terverifikasi
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                                <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>
                                    Kadaluarsa
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Pembayaran</th>
                                    <th>Nama Peserta</th>
                                    <th>Event</th>
                                    <th>Metode</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration + (($payments->currentPage() - 1) * $payments->perPage()) }}</td>
                                        <td>
                                            <strong>{{ $payment->kode_pembayaran }}</strong>
                                            <div class="text-muted small">
                                                {{ $payment->nomor_start ? 'No. Start: ' . $payment->nomor_start : '' }}
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $payment->peserta_nama }}</strong>
                                            <div class="text-muted small">{{ $payment->user_nama }}</div>
                                        </td>
                                        <td>
                                            {{ $payment->event_nama }}
                                            <div class="text-muted small">
                                                {{ date('d/m/Y', strtotime($payment->event_date)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="payment-method">
                                                {{ ucfirst($payment->metode_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="amount">
                                            Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($payment->created_at)) }}
                                            <div class="text-muted small">
                                                {{ date('H:i', strtotime($payment->created_at)) }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = 'status-' . $payment->status;
                                                $statusText = ucfirst($payment->status);
                                            @endphp
                                            <span class="payment-status {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('staff.payments.view', $payment->id) }}" 
                                                   class="btn btn-outline-primary" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($payment->status == 'menunggu')
                                                    <button type="button" class="btn btn-outline-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#verifyModal{{ $payment->id }}"
                                                            title="Verifikasi">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $payment->id }}"
                                                            title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- Verification Modal -->
                                            <div class="modal fade" id="verifyModal{{ $payment->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Verifikasi Pembayaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('staff.payments.verify', $payment->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p>Verifikasi pembayaran <strong>{{ $payment->kode_pembayaran }}</strong>?</p>
                                                                <p>Nama: {{ $payment->peserta_nama }}</p>
                                                                <p>Jumlah: Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                                                                <div class="mb-3">
                                                                    <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                                                    <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-success">Verifikasi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Rejection Modal -->
                                            <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Tolak Pembayaran</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('staff.payments.reject', $payment->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <p>Tolak pembayaran <strong>{{ $payment->kode_pembayaran }}</strong>?</p>
                                                                <p>Nama: {{ $payment->peserta_nama }}</p>
                                                                <p>Jumlah: Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                                                                <div class="mb-3">
                                                                    <label for="catatan" class="form-label">Alasan Penolakan *</label>
                                                                    <textarea class="form-control" id="catatan" name="catatan" rows="3" required></textarea>
                                                                    <small class="text-muted">Berikan alasan mengapa pembayaran ditolak</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $payments->firstItem() }} - {{ $payments->lastItem() }} dari {{ $payments->total() }} pembayaran
                        </div>
                        <div>
                            {{ $payments->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data pembayaran</h5>
                        <p class="text-muted">
                            @if(request('status') || request('search'))
                                Coba ubah filter pencarian Anda
                            @else
                                Belum ada pembayaran yang tercatat
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Summary Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar text-primary"></i> Ringkasan Pendapatan
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Pendapatan Terverifikasi:</span>
                            <strong class="text-success">
                                Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Rata-rata per Pembayaran:</span>
                            <strong>
                                Rp {{ $stats['verified'] > 0 ? number_format($stats['total_amount'] / $stats['verified'], 0, ',', '.') : 0 }}
                            </strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pembayaran Menunggu:</span>
                            <strong class="text-warning">{{ $stats['pending'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pembayaran Ditolak:</span>
                            <strong class="text-danger">{{ $stats['rejected'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-3 bg-light border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">
                &copy; {{ date('Y') }} Marathon System - Staff Area
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Auto-focus on search input
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.focus();
            }
        });
    </script>
</body>
</html>