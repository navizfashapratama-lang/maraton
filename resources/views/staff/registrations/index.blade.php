@extends('layouts.staff')

@section('title', 'Kelola Pendaftaran')
@section('page-title', 'Kelola Pendaftaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Pendaftaran</h6>
                <div class="d-flex">
                    <select id="filterEvent" class="form-select form-select-sm me-2" style="width: 200px;">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->nama }}
                            </option>
                        @endforeach
                    </select>
                    <select id="filterStatus" class="form-select form-select-sm me-2" style="width: 150px;">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button id="filterBtn" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center py-3">
                                <h5 class="mb-0">{{ $stats['total'] }}</h5>
                                <small class="text-muted">Total Pendaftaran</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning bg-opacity-10 border-0">
                            <div class="card-body text-center py-3">
                                <h5 class="mb-0">{{ $stats['pending'] }}</h5>
                                <small class="text-muted">Menunggu Verifikasi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success bg-opacity-10 border-0">
                            <div class="card-body text-center py-3">
                                <h5 class="mb-0">{{ $stats['approved'] }}</h5>
                                <small class="text-muted">Disetujui</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger bg-opacity-10 border-0">
                            <div class="card-body text-center py-3">
                                <h5 class="mb-0">{{ $stats['rejected'] }}</h5>
                                <small class="text-muted">Ditolak</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Peserta</th>
                                <th>Event</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($registrations as $registration)
                            <tr>
                                <td>
                                    <strong>{{ $registration->kode_pendaftaran }}</strong>
                                    @if($registration->nomor_start)
                                    <br>
                                    <small class="text-muted">No: {{ $registration->nomor_start }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $registration->nama_lengkap }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $registration->email }}</small>
                                </td>
                                <td>
                                    {{ Str::limit($registration->event_nama, 30) }}
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($registration->event_date)->format('d M Y') }}
                                    </small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($registration->created_at)->format('d M Y H:i') }}</td>
                                <td>
                                    @if($registration->status_pendaftaran == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($registration->status_pendaftaran == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    @if($registration->status_pembayaran == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @elseif($registration->status_pembayaran == 'menunggu')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('staff.registrations.view', $registration->id) }}" 
                                           class="btn btn-outline-primary" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($registration->status_pendaftaran == 'menunggu')
                                        <form action="{{ route('staff.registrations.approve', $registration->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" 
                                                    onclick="return confirm('Setujui pendaftaran ini?')" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data pendaftaran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $registrations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('filterBtn').addEventListener('click', function() {
        const eventId = document.getElementById('filterEvent').value;
        const status = document.getElementById('filterStatus').value;
        
        let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);
        
        if (eventId) {
            params.set('event_id', eventId);
        } else {
            params.delete('event_id');
        }
        
        if (status) {
            params.set('status', status);
        } else {
            params.delete('status');
        }
        
        window.location.href = url.pathname + '?' + params.toString();
    });
</script>
@endsection