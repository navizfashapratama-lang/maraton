<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hasil Lomba - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .time-input {
            font-family: monospace;
            font-size: 1.2rem;
            letter-spacing: 2px;
        }
        .registrations-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
        }
        .registration-item {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .registration-item:hover {
            background-color: #f8f9fa;
        }
        .registration-item.selected {
            background-color: #e7f5ff;
            border-left: 4px solid #0d6efd;
        }
        .registration-number {
            font-weight: bold;
            color: #0d6efd;
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
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-plus-circle text-primary"></i> Tambah Hasil Lomba
            </h1>
            <a href="{{ route('staff.results.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
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

        <!-- Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('staff.results.store') }}" method="POST" id="resultForm">
                    @csrf
                    
                    <div class="row">
                        <!-- Event Selection -->
                        <div class="col-md-6 mb-4">
                            <label for="event_id" class="form-label">Pilih Event *</label>
                            <select class="form-select" id="event_id" name="event_id" required onchange="loadRegistrations()">
                                <option value="">Pilih Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->nama }} ({{ date('d/m/Y', strtotime($event->tanggal)) }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih event untuk melihat daftar peserta</small>
                        </div>

                        <!-- Registration Selection -->
                        <div class="col-12 mb-4" id="registrationsSection" style="{{ !request('event_id') ? 'display: none;' : '' }}">
                            <label class="form-label">Pilih Peserta *</label>
                            <div class="registrations-list" id="registrationsList">
                                @if($registrations->count() > 0)
                                    @foreach($registrations as $registration)
                                        <div class="registration-item" onclick="selectRegistration(this, {{ $registration->id }})" 
                                             data-id="{{ $registration->id }}"
                                             data-name="{{ $registration->nama_lengkap }}"
                                             data-number="{{ $registration->nomor_start }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $registration->nama_lengkap }}</strong>
                                                    @if($registration->nomor_start)
                                                        <div class="registration-number">No. Start: {{ $registration->nomor_start }}</div>
                                                    @endif
                                                </div>
                                                <i class="fas fa-check text-success" style="display: none;"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted p-4">
                                        @if(request('event_id'))
                                            <i class="fas fa-users-slash fa-2x mb-3"></i>
                                            <p>Tidak ada peserta yang disetujui untuk event ini</p>
                                        @else
                                            <p>Pilih event terlebih dahulu untuk melihat daftar peserta</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" id="pendaftaran_id" name="pendaftaran_id" required>
                            <div class="mt-2" id="selectedParticipant" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-user-check"></i>
                                    <strong>Peserta Terpilih:</strong> 
                                    <span id="selectedName"></span>
                                    (<span id="selectedNumber"></span>)
                                </div>
                            </div>
                        </div>

                        <!-- Result Details -->
                        <div class="col-md-4 mb-3">
                            <label for="posisi" class="form-label">Posisi *</label>
                            <input type="number" class="form-control" id="posisi" name="posisi" 
                                   required min="1" value="{{ old('posisi') }}"
                                   placeholder="1, 2, 3, ...">
                            <small class="text-muted">Masukkan posisi finis (1 untuk juara pertama)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="waktu_total" class="form-label">Waktu Total *</label>
                            <input type="text" class="form-control time-input" id="waktu_total" name="waktu_total" 
                                   required value="{{ old('waktu_total') }}"
                                   placeholder="HH:MM:SS" pattern="\d{2}:\d{2}:\d{2}">
                            <small class="text-muted">Format: Jam:Menit:Detik (contoh: 01:23:45)</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="kategori_umur" class="form-label">Kategori Umur</label>
                            <input type="text" class="form-control" id="kategori_umur" name="kategori_umur" 
                                   value="{{ old('kategori_umur') }}"
                                   placeholder="Contoh: U-20, Senior, Master">
                        </div>
                        
                        <div class="col-12 mb-4">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" 
                                      rows="3" placeholder="Catatan tambahan tentang hasil lomba">{{ old('catatan') }}</textarea>
                            <small class="text-muted">Opsional: Catatan tentang kondisi lomba, diskualifikasi, dll.</small>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-save"></i> Simpan Hasil
                                </button>
                                <a href="{{ route('staff.results.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Time Format Helper -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-clock text-primary"></i> Panduan Format Waktu
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Contoh Format:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <code class="bg-light p-2 rounded">01:30:25</code>
                                <small class="text-muted"> - 1 jam 30 menit 25 detik</small>
                            </li>
                            <li class="mb-2">
                                <code class="bg-light p-2 rounded">00:45:12</code>
                                <small class="text-muted"> - 45 menit 12 detik</small>
                            </li>
                            <li class="mb-2">
                                <code class="bg-light p-2 rounded">02:15:30</code>
                                <small class="text-muted"> - 2 jam 15 menit 30 detik</small>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Kategori Umur Umum:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-1"><strong>U-20:</strong> Di bawah 20 tahun</li>
                            <li class="mb-1"><strong>U-30:</strong> 20-29 tahun</li>
                            <li class="mb-1"><strong>Senior:</strong> 30-39 tahun</li>
                            <li class="mb-1"><strong>Master:</strong> 40 tahun ke atas</li>
                        </ul>
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
        let selectedRegistrationId = null;

        function loadRegistrations() {
            const eventId = document.getElementById('event_id').value;
            if (eventId) {
                window.location.href = "{{ route('staff.results.create') }}?event_id=" + eventId;
            } else {
                document.getElementById('registrationsSection').style.display = 'none';
                resetSelection();
            }
        }

        function selectRegistration(element, registrationId) {
            // Remove selection from all items
            document.querySelectorAll('.registration-item').forEach(item => {
                item.classList.remove('selected');
                item.querySelector('.fa-check').style.display = 'none';
            });

            // Add selection to clicked item
            element.classList.add('selected');
            element.querySelector('.fa-check').style.display = 'inline-block';

            // Store selected data
            selectedRegistrationId = registrationId;
            document.getElementById('pendaftaran_id').value = registrationId;
            
            // Show selected participant info
            const selectedName = element.getAttribute('data-name');
            const selectedNumber = element.getAttribute('data-number');
            document.getElementById('selectedName').textContent = selectedName;
            document.getElementById('selectedNumber').textContent = selectedNumber || 'No. Start: -';
            document.getElementById('selectedParticipant').style.display = 'block';

            // Enable submit button
            document.getElementById('submitBtn').disabled = false;
        }

        function resetSelection() {
            selectedRegistrationId = null;
            document.getElementById('pendaftaran_id').value = '';
            document.getElementById('selectedParticipant').style.display = 'none';
            document.getElementById('submitBtn').disabled = true;
        }

        // Time input formatting
        document.getElementById('waktu_total').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 6) value = value.substr(0, 6);
            
            // Format as HH:MM:SS
            let formatted = '';
            if (value.length > 0) {
                formatted = value.substr(0, 2);
                if (value.length > 2) {
                    formatted += ':' + value.substr(2, 2);
                    if (value.length > 4) {
                        formatted += ':' + value.substr(4, 2);
                    }
                }
            }
            e.target.value = formatted;
        });

        // Form validation
        document.getElementById('resultForm').addEventListener('submit', function(e) {
            const waktuTotal = document.getElementById('waktu_total').value;
            const waktuRegex = /^\d{2}:\d{2}:\d{2}$/;
            
            if (!waktuRegex.test(waktuTotal)) {
                e.preventDefault();
                alert('Format waktu tidak valid. Gunakan format HH:MM:SS');
                return false;
            }

            if (!selectedRegistrationId) {
                e.preventDefault();
                alert('Silakan pilih peserta terlebih dahulu');
                return false;
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Auto-select if registration is pre-selected
        @if(request('event_id') && $registrations->count() > 0)
            document.getElementById('registrationsSection').style.display = 'block';
        @endif
    </script>
</body>
</html>