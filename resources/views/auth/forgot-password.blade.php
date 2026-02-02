
<div class="login-container bg-gradient-primary">
    <div class="login-card fade-in">
        <div class="card">
            <!-- Header -->
            <div class="card-header bg-gradient-primary text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="fas fa-key me-2"></i>Reset Password
                </h3>
                <p class="mb-0 opacity-75">Masukkan email untuk reset password</p>
            </div>
            
            <!-- Body -->
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2 text-primary"></i>Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-at"></i>
                            </span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Masukkan email terdaftar" required autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Link Reset
                        </button>
                    </div>
                    
                    <!-- Back to Login -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="card-footer text-center py-3 bg-light">
                <small class="text-muted">
                    Link reset akan dikirim ke email Anda.
                </small>
            </div>
        </div>
    </div>
</div>
