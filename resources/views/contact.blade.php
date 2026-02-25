@extends('layouts.app')

@section('title', 'Kontak Kami - Marathon Runner')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-3">Hubungi Kami</h1>
            <p class="lead text-muted">Punya pertanyaan tentang event marathon? Tim kami siap membantu Anda!</p>
        </div>
    </div>

    <!-- Contact Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-icon mb-3">
                    <div class="icon-wrapper bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-map-marker-alt fa-2x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">Alamat Kantor</h5>
                    <p class="card-text text-muted">
                        Jl. Marathon Raya No. 123<br>
                        Jakarta Pusat 10110<br>
                        DKI Jakarta, Indonesia
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-icon mb-3">
                    <div class="icon-wrapper bg-success rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-phone fa-2x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">Telepon & WhatsApp</h5>
                    <p class="card-text text-muted">
                        <strong>Customer Service:</strong><br>
                        (021) 1234-5678<br>
                        <strong>WhatsApp:</strong><br>
                        0812-3456-7890
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-icon mb-3">
                    <div class="icon-wrapper bg-info rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-envelope fa-2x text-white"></i>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">Email</h5>
                    <p class="card-text text-muted">
                        <strong>Informasi Umum:</strong><br>
                        info@marathonrunner.com<br>
                        <strong>Pendaftaran:</strong><br>
                        registration@marathonrunner.com<br>
                        <strong>Sponsorship:</strong><br>
                        partnership@marathonrunner.com
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="fw-bold mb-0"><i class="fas fa-paper-plane me-2"></i>Kirim Pesan</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ session('user_nama') ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ session('user_email') ?? '' }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Pilih Subjek</option>
                                    <option value="pendaftaran">Pendaftaran Event</option>
                                    <option value="pembayaran">Pembayaran</option>
                                    <option value="event">Informasi Event</option>
                                    <option value="sponsorship">Sponsorship & Partnership</option>
                                    <option value="volunteer">Volunteer</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required 
                                          placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Saya ingin menerima informasi terbaru tentang event marathon via email
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info & FAQ -->
        <div class="col-lg-4">
            <!-- Office Hours -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0"><i class="fas fa-clock me-2"></i>Jam Operasional</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Senin - Jumat</span>
                            <strong>09:00 - 17:00</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Sabtu</span>
                            <strong>09:00 - 15:00</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Minggu & Libur</span>
                            <strong class="text-danger">Tutup</strong>
                        </li>
                    </ul>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Customer Service via WhatsApp tersedia 24 jam untuk pertanyaan mendesak.
                    </p>
                </div>
            </div>

            <!-- FAQ -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0"><i class="fas fa-question-circle me-2"></i>Pertanyaan Umum</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    Bagaimana cara mendaftar event?
                                </button>
                            </h6>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="small">Daftar akun terlebih dahulu, pilih event, pilih paket, isi form pendaftaran, dan lakukan pembayaran.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Apakah bisa refund jika batal?
                                </button>
                            </h6>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="small">Refund dapat dilakukan maksimal 7 hari sebelum event dengan potongan administrasi 20%.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Bagaimana jika hujan saat event?
                                </button>
                            </h6>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="small">Event tetap berjalan kecuali kondisi cuaca ekstrem. Informasi akan disampaikan via email & website.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#faq4">
                                    Apa yang harus dibawa saat event?
                                </button>
                            </h6>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="small">Kartu peserta, identitas diri, sepatu lari, pakaian ganti, dan botol minum.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('faq') }}" class="btn btn-outline-primary btn-sm">
                            Lihat FAQ Lengkap <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0"><i class="fas fa-map me-2"></i>Lokasi Kantor</h5>
                </div>
                <div class="card-body p-0">
                    <!-- Google Maps Embed -->
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8195613506864!3d-6.194741395493371!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5390917b759%3A0x6b45e67356080477!2sJakarta%2C%20Indonesia!5e0!3m2!1sen!2sus!4v1648034600000!5m2!1sen!2sus" 
                                style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row">
                        <div class="col-md-4">
                            <strong><i class="fas fa-subway me-2"></i>Transportasi Umum:</strong>
                            <p class="mb-0 small">Stasiun Gambir (500m), Halte Busway (200m)</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-parking me-2"></i>Parkir:</strong>
                            <p class="mb-0 small">Parkir khusus peserta tersedia di gedung sebelah</p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-wheelchair me-2"></i>Aksesibilitas:</strong>
                            <p class="mb-0 small">Gedung ramah disabilitas dengan lift dan toilet khusus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // Form validation
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const phone = document.getElementById('phone').value;
                if (phone && !/^[0-9]{10,13}$/.test(phone)) {
                    e.preventDefault();
                    alert('Nomor telepon harus 10-13 digit angka.');
                    return false;
                }
            });
        }
        
        // Phone input formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 13) value = value.substring(0, 13);
                e.target.value = value;
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .icon-wrapper {
        transition: transform 0.3s ease;
    }
    
    .card:hover .icon-wrapper {
        transform: scale(1.1);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .btn-primary {
        background-color: #4361ee;
        border-color: #4361ee;
        padding: 10px 30px;
    }
    
    .btn-primary:hover {
        background-color: #3a56d4;
        border-color: #3a56d4;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #4361ee;
        font-weight: 600;
    }
    
    .accordion-button:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .card {
        border-radius: 15px;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    iframe {
        border-radius: 0 0 15px 15px;
    }
</style>
@endsection