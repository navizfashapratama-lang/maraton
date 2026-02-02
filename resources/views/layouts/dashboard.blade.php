
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0">Total Peserta</h6>
                        <h3 class="mb-0">{{ $stats['total_peserta'] }}</h3>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0">Total Event</h6>
                        <h3 class="mb-0">{{ $stats['total_event'] }}</h3>
                    </div>
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-calendar-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0">Pendaftaran Aktif</h6>
                        <h3 class="mb-0">{{ $stats['pendaftaran_aktif'] }}</h3>
                    </div>
                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-running fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-0">Pembayaran Pending</h6>
                        <h3 class="mb-0">{{ $stats['pembayaran_pending'] }}</h3>
                    </div>
                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-credit-card fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Events Section -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Event Mendatang</h5>
                <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>
                                    <strong>{{ $event->nama }}</strong><br>
                                    <small class="text-muted">{{ $event->kategori }}</small>
                                </td>
                                <td>{{ $event->tanggal->format('d M Y') }}</td>
                                <td>
                                    @if($event->status == 'mendatang')
                                    <span class="badge bg-success">Aktif</span>
                                    @elseif($event->status == 'selesai')
                                    <span class="badge bg-secondary">Selesai</span>
                                    @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Registrations -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pendaftaran Terbaru</h5>
                <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Event</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $reg)
                            <tr>
                                <td>
                                    <strong>{{ $reg->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $reg->email }}</small>
                                </td>
                                <td>{{ $reg->lomba->nama }}</td>
                                <td>
                                    @if($reg->status_pendaftaran == 'disetujui' && $reg->status_pembayaran == 'lunas')
                                    <span class="badge bg-success">Aktif</span>
                                    @elseif($reg->status_pendaftaran == 'menunggu')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($reg->status_pendaftaran == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>{{ $reg->created_at->format('d M') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Pending Payments -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pembayaran Menunggu Verifikasi</h5>
                <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Event</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td><code>{{ $payment->kode_pembayaran }}</code></td>
                                <td>{{ $payment->nama_pembayar }}</td>
                                <td>{{ $payment->pendaftaran->lomba->nama }}</td>
                                <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $payment->created_at->format('d M Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Verifikasi
                                    </button>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>