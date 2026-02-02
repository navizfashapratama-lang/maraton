
<div class="login-container bg-gradient-primary">
    <div class="login-card fade-in">
        <div class="card">
            <!-- Header -->
            <div class="card-header bg-gradient-primary text-white text-center py-4">
                <h3 class="mb-0">
                    <i class="fas fa-lock me-2"></i>Password Baru
                </h3>
                <p class="mb-0 opacity-75">Buat password baru untuk akun Anda</p>
            </div>
            
            <!-- Body -->
            <div class="card-body p-4">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    
                    <!-- Email (hidden) -->
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2 text-primary"></i>Password Baru
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Minimal 6 karakter" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2 text-primary"></i>Konfirmasi Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-key"></i>
                            </span>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Ulangi password baru" required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Password
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="card-footer text-center py-3 bg-light">
                <small class="text-muted">
                    Pastikan password Anda kuat dan mudah diingat.
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            confirmInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            confirmInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
