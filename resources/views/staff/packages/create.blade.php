<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Paket - Staff Area</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                <i class="fas fa-box-plus text-primary"></i> Tambah Paket Baru
            </h1>
            <a href="{{ route('staff.packages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('staff.packages.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lomba_id" class="form-label">Event *</label>
                            <select class="form-select" id="lomba_id" name="lomba_id" required>
                                <option value="">Pilih Event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('lomba_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->nama }} ({{ date('d/m/Y', strtotime($event->tanggal)) }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih event yang akan dikaitkan dengan paket ini</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Paket *</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   required value="{{ old('nama') }}" 
                                   placeholder="Contoh: Paket Reguler, Paket Premium, dll">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga (Rp) *</label>
                            <input type="number" class="form-control" id="harga" name="harga" 
                                   required value="{{ old('harga') }}" min="0" step="1000">
                            <small class="text-muted">Masukkan harga dalam rupiah tanpa titik</small>
                        </div>
                        
                        <div class="col-12 mb-4">
                            <label class="form-label">Fasilitas yang Termasuk</label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termasuk_race_kit" 
                                               name="termasuk_race_kit" value="1" {{ old('termasuk_race_kit') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="termasuk_race_kit">
                                            <i class="fas fa-gift text-success"></i> Race Kit
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termasuk_medali" 
                                               name="termasuk_medali" value="1" {{ old('termasuk_medali') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="termasuk_medali">
                                            <i class="fas fa-medal text-warning"></i> Medali
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termasuk_kaos" 
                                               name="termasuk_kaos" value="1" {{ old('termasuk_kaos') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="termasuk_kaos">
                                            <i class="fas fa-tshirt text-info"></i> Kaos
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termasuk_sertifikat" 
                                               name="termasuk_sertifikat" value="1" {{ old('termasuk_sertifikat', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="termasuk_sertifikat">
                                            <i class="fas fa-certificate text-primary"></i> Sertifikat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termasuk_snack" 
                                               name="termasuk_snack" value="1" {{ old('termasuk_snack', 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="termasuk_snack">
                                            <i class="fas fa-utensils text-danger"></i> Snack
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Paket
                                </button>
                                <a href="{{ route('staff.packages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-info-circle text-primary"></i> Informasi Paket
                </h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> 
                        <strong>Race Kit:</strong> Biasanya berisi nomor dada, timing chip, dan merchandise lainnya
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-warning"></i> 
                        <strong>Medali:</strong> Diberikan kepada peserta yang menyelesaikan lomba
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-info"></i> 
                        <strong>Kaos:</strong> Kaos event dengan desain khusus
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-primary"></i> 
                        <strong>Sertifikat:</strong> Sertifikat digital atau fisik untuk peserta
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-danger"></i> 
                        <strong>Snack:</strong> Makanan dan minuman setelah lomba
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Format harga input
        document.getElementById('harga').addEventListener('input', function(e) {
            let value = e.target.value;
            e.target.value = value.replace(/\D/g, '');
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const harga = document.getElementById('harga').value;
            if (harga < 0) {
                e.preventDefault();
                alert('Harga tidak boleh negatif');
                return false;
            }
        });
    </script>
</body>
</html>