<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Marathon Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-color: #4361ee; }
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .nav-link.active { color: var(--primary-color) !important; font-weight: bold; }
        .badge-payment-paid { background-color: #10b981; color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-payment-unpaid { background-color: #f59e0b; color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-running me-2"></i>
                <span style="color: #4361ee;">MARATHON</span>EVENTS
            </a>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/events') }}">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">Contact</a></li>
                @if(session('is_logged_in'))
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/profile') }}">Profile</a></li>
                    <li class="nav-item">
                        <form action="{{ url('/logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link py-0" style="text-decoration: none;">Logout</button>
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $user->nama ?? 'User' }}</h4>
                        <p class="text-muted small mb-0">{{ $user->email ?? '' }}</p>
                        
                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <a href="{{ url('/profile/edit') }}" class="btn btn-outline-primary rounded-3">
                                    <i class="fas fa-edit me-2"></i>Edit Profile
                                </a>
                                <a href="{{ url('/my-registrations') }}" class="btn btn-primary rounded-3">
                                    <i class="fas fa-ticket-alt me-2"></i>My Registrations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 py-3">
                        <h5 class="mb-0 small fw-bold">
                            <i class="fas fa-user me-2"></i>PROFILE INFORMATION
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Full Name</label>
                                <span class="fw-bold">{{ $user->nama ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Role</label>
                                <span class="badge bg-primary px-3">{{ $user->peran ?? 'Participant' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Email</label>
                                <span class="fw-bold">{{ $user->email ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Account Status</label>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Phone</label>
                                <span class="fw-bold">{{ $user->telepon ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small d-block">Last Login</label>
                                <span class="fw-bold">{{ $user->terakhir_login ? date('d M Y H:i', strtotime($user->terakhir_login)) : 'Never' }}</span>
                            </div>
                            <div class="col-12 mb-0">
                                <label class="text-muted small d-block">Address</label>
                                <span class="fw-bold text-wrap">{{ $user->alamat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($registrations) && count($registrations) > 0)
                    <div class="card shadow-sm border-0 rounded-4 mt-4">
                        <div class="card-header bg-info text-white rounded-top-4 py-3">
                            <h5 class="mb-0 small fw-bold">
                                <i class="fas fa-history me-2"></i>RECENT REGISTRATIONS
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 small border-0">Event</th>
                                            <th class="small border-0">No. Start</th>
                                            <th class="small border-0">Verif Pendaftaran</th>
                                            <th class="small border-0 pe-4">Status Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($registrations as $registration)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark">{{ $registration->event_nama ?? 'Event' }}</div>
                                                    <div class="text-muted extra-small" style="font-size: 10px;">
                                                        {{ date('d M Y', strtotime($registration->tanggal ?? now())) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($registration->nomor_start)
                                                        <span class="badge bg-dark px-2" style="letter-spacing: 1px;">{{ $registration->nomor_start }}</span>
                                                    @else
                                                        <span class="text-muted small italic">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($registration->status_pendaftaran == 'disetujui') bg-success
                                                        @elseif($registration->status_pendaftaran == 'menunggu') bg-warning
                                                        @else bg-danger @endif px-2">
                                                        {{ strtoupper($registration->status_pendaftaran ?? 'Pending') }}
                                                    </span>
                                                </td>
                                                <td class="pe-4">
                                                    @if($registration->status_pembayaran == 'lunas')
                                                        <span class="badge badge-payment-paid px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-check-circle me-1"></i> PAID
                                                        </span>
                                                    @else
                                                        <span class="badge badge-payment-unpaid px-3 py-2 rounded-pill shadow-sm">
                                                            <i class="fas fa-clock me-1"></i> UNPAID
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 rounded-bottom-4 py-3 text-center">
                            <a href="{{ url('/my-registrations') }}" class="btn btn-sm btn-outline-primary px-4 rounded-pill">
                                View All Registrations <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>