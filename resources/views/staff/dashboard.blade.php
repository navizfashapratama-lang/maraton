@extends('layouts.staff')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff')

@section('content')
<div class="container-fluid px-0">
    <!-- Stats Overview Cards -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-grid">
                <!-- Total Events -->
                <div class="stat-card card-hover">
                    <div class="stat-icon-wrapper bg-primary-light">
                        <i class="fas fa-calendar-alt text-primary"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Total Event</h6>
                        <h3 class="stat-value">{{ $total_events }}</h3>
                        <div class="stat-action">
                            <a href="{{ route('staff.events.index') }}" class="stat-link">
                                Lihat semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Total Participants -->
                <div class="stat-card card-hover">
                    <div class="stat-icon-wrapper bg-success-light">
                        <i class="fas fa-users text-success"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Total Peserta</h6>
                        <h3 class="stat-value">{{ $total_registrations }}</h3>
                        <div class="stat-action">
                            <a href="{{ route('staff.registrations.index') }}" class="stat-link">
                                Lihat semua <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Verifications -->
                <div class="stat-card card-hover">
                    <div class="stat-icon-wrapper bg-warning-light">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Menunggu Verifikasi</h6>
                        <h3 class="stat-value">{{ $pending_registrations }}</h3>
                        <div class="stat-action">
                            <a href="{{ route('staff.registrations.index') }}?status=menunggu" class="stat-link">
                                Verifikasi sekarang <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Revenue -->
                <div class="stat-card card-hover">
                    <div class="stat-icon-wrapper bg-info-light">
                        <i class="fas fa-money-bill-wave text-info"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Total Pendapatan</h6>
                        <h3 class="stat-value">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                        <div class="stat-meta">
                            <span class="badge bg-success">Terverifikasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Left Column: Upcoming Events -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Event Mendatang</h5>
                            <p class="text-muted mb-0 small">Event yang akan segera dilaksanakan</p>
                        </div>
                        <a href="{{ route('staff.events.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($upcoming_events->count() > 0)
                        <div class="event-list">
                            @foreach($upcoming_events as $event)
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="event-day">{{ \Carbon\Carbon::parse($event->tanggal)->format('d') }}</div>
                                    <div class="event-month">{{ \Carbon\Carbon::parse($event->tanggal)->format('M') }}</div>
                                </div>
                                <div class="event-details">
                                    <h6 class="event-title mb-1">{{ $event->nama }}</h6>
                                    <p class="event-description mb-2 text-muted small">
                                        {{ Str::limit($event->deskripsi, 60) }}
                                    </p>
                                    <div class="event-meta">
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($event->lokasi, 25) }}
                                        </span>
                                        <span class="badge bg-primary">{{ $event->kategori }}</span>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    <div class="btn-group">
                                        <a href="{{ route('staff.events.edit', $event->id) }}" 
                                           class="btn btn-sm btn-icon" title="Edit Event">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('staff.registrations.index') }}?event_id={{ $event->id }}" 
                                           class="btn btn-sm btn-icon" title="Lihat Peserta">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada event mendatang</h5>
                                <p class="text-muted mb-4">Belum ada event yang akan datang</p>
                                <a href="{{ route('staff.events.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Buat Event Baru
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Recent Registrations & Quick Actions -->
        <div class="col-lg-4 mb-4">
            <!-- Recent Registrations -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Pendaftaran Terbaru</h5>
                            <p class="text-muted mb-0 small">Pendaftaran yang baru masuk</p>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $recent_registrations->count() }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recent_registrations->count() > 0)
                        <div class="registration-list">
                            @foreach($recent_registrations as $registration)
                            <div class="registration-item">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($registration->nama_lengkap, 0, 1)) }}
                                </div>
                                <div class="registration-details">
                                    <h6 class="user-name mb-0">{{ $registration->nama_lengkap }}</h6>
                                    <small class="text-muted d-block">{{ $registration->kode_pendaftaran }}</small>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($registration->created_at)->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="registration-status">
                                    @if($registration->status_pendaftaran == 'disetujui')
                                        <span class="status-badge status-approved">
                                            <i class="fas fa-check-circle me-1"></i> Disetujui
                                        </span>
                                    @elseif($registration->status_pendaftaran == 'menunggu')
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock me-1"></i> Menunggu
                                        </span>
                                    @else
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times-circle me-1"></i> Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada pendaftaran</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">Aksi Cepat</h5>
                    <p class="text-muted mb-0 small">Akses fitur penting dengan cepat</p>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <a href="{{ route('staff.events.create') }}" class="quick-action card-hover">
                            <div class="action-icon bg-primary">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0">Tambah Event</h6>
                                <small class="text-muted">Buat event baru</small>
                            </div>
                        </a>

                        <a href="{{ route('staff.packages.create') }}" class="quick-action card-hover">
                            <div class="action-icon bg-success">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0">Tambah Paket</h6>
                                <small class="text-muted">Buat paket lomba</small>
                            </div>
                        </a>

                        <a href="{{ route('staff.payments.index') }}?status=menunggu" class="quick-action card-hover">
                            <div class="action-icon bg-warning">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0">Verifikasi Pembayaran</h6>
                                <small class="text-muted">{{ $pending_payments }} menunggu</small>
                            </div>
                        </a>

                        <a href="{{ route('staff.results.create') }}" class="quick-action card-hover">
                            <div class="action-icon bg-info">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="action-content">
                                <h6 class="mb-0">Input Hasil</h6>
                                <small class="text-muted">Input hasil lomba</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom Styles for Dashboard */
:root {
    --primary-light: rgba(13, 110, 253, 0.1);
    --success-light: rgba(25, 135, 84, 0.1);
    --warning-light: rgba(255, 193, 7, 0.1);
    --info-light: rgba(13, 202, 240, 0.1);
}

/* Stats Grid Layout */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.bg-primary-light { background-color: var(--primary-light); }
.bg-success-light { background-color: var(--success-light); }
.bg-warning-light { background-color: var(--warning-light); }
.bg-info-light { background-color: var(--info-light); }

.stat-icon-wrapper i {
    font-size: 1.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 1rem;
}

.stat-action {
    border-top: 1px solid #f0f0f0;
    padding-top: 1rem;
}

.stat-link {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
    transition: color 0.2s;
}

.stat-link:hover {
    color: #0a58ca;
}

.stat-meta {
    margin-top: 0.5rem;
}

/* Event List Styles */
.event-list {
    max-height: 500px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
}

.event-item:hover {
    background-color: #f8f9fa;
}

.event-item:last-child {
    border-bottom: none;
}

.event-date {
    flex-shrink: 0;
    width: 60px;
    text-align: center;
    margin-right: 1rem;
}

.event-day {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0d6efd;
    line-height: 1;
}

.event-month {
    font-size: 0.875rem;
    color: #6c757d;
    text-transform: uppercase;
}

.event-details {
    flex-grow: 1;
}

.event-title {
    font-weight: 600;
    color: #212529;
}

.event-description {
    font-size: 0.875rem;
}

.event-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.event-actions {
    flex-shrink: 0;
}

.btn-icon {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

/* Registration List Styles */
.registration-list {
    max-height: 300px;
    overflow-y: auto;
}

.registration-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f0f0f0;
}

.registration-item:hover {
    background-color: #f8f9fa;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0d6efd, #6610f2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-right: 1rem;
    flex-shrink: 0;
}

.registration-details {
    flex-grow: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
}

.registration-status {
    flex-shrink: 0;
    margin-left: 0.5rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-approved {
    background-color: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.status-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.status-rejected {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-action {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.25rem;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    background: white;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.quick-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    border-color: #dee2e6;
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    color: white;
}

.action-icon.bg-primary { background: linear-gradient(135deg, #0d6efd, #6610f2); }
.action-icon.bg-success { background: linear-gradient(135deg, #198754, #20c997); }
.action-icon.bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
.action-icon.bg-info { background: linear-gradient(135deg, #0dcaf0, #20c997); }

.action-icon i {
    font-size: 1.25rem;
}

.action-content {
    text-align: center;
}

.action-content h6 {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #212529;
}

.action-content small {
    color: #6c757d;
    font-size: 0.75rem;
}

/* Empty State */
.empty-state {
    padding: 2rem 1rem;
}

.empty-state i {
    opacity: 0.5;
}

/* Scrollbar Styling */
.event-list::-webkit-scrollbar,
.registration-list::-webkit-scrollbar {
    width: 6px;
}

.event-list::-webkit-scrollbar-track,
.registration-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.event-list::-webkit-scrollbar-thumb,
.registration-list::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.event-list::-webkit-scrollbar-thumb:hover,
.registration-list::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .event-item {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .event-date {
        margin-bottom: 1rem;
    }
    
    .event-actions {
        margin-top: 1rem;
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endsection