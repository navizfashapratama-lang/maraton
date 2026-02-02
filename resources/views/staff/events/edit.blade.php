<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Staff Area</title>
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
                <i class="fas fa-edit text-primary"></i> Edit Event
            </h1>
            <a href="{{ route('staff.events.index') }}" class="btn btn-outline-secondary">
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

        <!-- Form -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('staff.events.update', $event->id) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Event *</label>
                            <input type="text" class="form-control" id="nama" name="nama" 
                                   required value="{{ old('nama', $event->nama) }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori *</label>
                            <input type="text" class="form-control" id="kategori" name="kategori" 
                                   required value="{{ old('kategori', $event->kategori) }}" 
                                   placeholder="5K, 10K, Half Marathon, dll">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal Event *</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   required value="{{ old('tanggal', $event->tanggal) }}">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lokasi" class="form-label">Lokasi *</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" 
                                   required value="{{ old('lokasi', $event->lokasi) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="kuota_peserta" class="form-label">Kuota Peserta</label>
                            <input type="number" class="form-control" id="kuota_peserta" name="kuota_peserta" 
                                   value="{{ old('kuota_peserta', $event->kuota_peserta) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="harga_reguler" class="form-label">Harga Reguler (Rp)</label>
                            <input type="number" class="form-control" id="harga_reguler" name="harga_reguler" 
                                   value="{{ old('harga_reguler', $event->harga_reguler) }}">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="harga_premium" class="form-label">Harga Premium (Rp)</label>
                            <input type="number" class="form-control" id="harga_premium" name="harga_premium" 
                                   value="{{ old('harga_premium', $event->harga_premium) }}">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Event *</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                      rows="4" required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="rute_lomba" class="form-label">Rute Lomba (Opsional)</label>
                            <textarea class="form-control" id="rute_lomba" name="rute_lomba" 
                                      rows="3">{{ old('rute_lomba', $event->rute_lomba) }}</textarea>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="syarat_ketentuan" class="form-label">Syarat & Ketentuan (Opsional)</label>
                            <textarea class="form-control" id="syarat_ketentuan" name="syarat_ketentuan" 
                                      rows="3">{{ old('syarat_ketentuan', $event->syarat_ketentuan) }}</textarea>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="fasilitas" class="form-label">Fasilitas (Opsional)</label>
                            <textarea class="form-control" id="fasilitas" name="fasilitas" 
                                      rows="3">{{ old('fasilitas', $event->fasilitas) }}</textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="mendatang" {{ old('status', $event->status) == 'mendatang' ? 'selected' : '' }}>
                                    Mendatang
                                </option>
                                <option value="selesai" {{ old('status', $event->status) == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                                <option value="dibatalkan" {{ old('status', $event->status) == 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Event
                            </button>
                            <a href="{{ route('staff.events.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>