<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftaran - {{ $registration->event_nama }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }
        
        .detail-header {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            color: white;
            padding: 2.5rem 0;
            border-radius: 0 0 1.5rem 1.5rem;
        }
        
        .info-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 2rem;
            margin-bottom: 1.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="detail-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2">
                        <i class="fas fa-ticket-alt me-2"></i>
                        Detail Pendaftaran
                    </h2>
                    <h4 class="mb-0">{{ $registration->event_nama }}</h4>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ url('/my-registrations') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                <!-- Registration Info -->
                <div class="info-card">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Informasi Pendaftaran
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Kode Pendaftaran</label>
                            <p class="fw-bold h5 text-primary">{{ $registration->kode_pendaftaran }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tanggal Pendaftaran</label>
                            <p class="fw-bold">{{ date('d F Y H:i', strtotime($registration->created_at)) }}</p>
                        </div>
                    </div>
                    
                    <