<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LombaController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Staff\ExportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Kasir\KasirController;
use App\Http\Middleware\AdminMiddleware; 
use App\Http\Controllers\Staff\PaymentController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events', [PublicEventController::class, 'index'])->name('events');
Route::get('/event/{id}', [PublicEventController::class, 'show'])->name('event.detail');

// Pages
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/check-login-status', [AuthController::class, 'checkLoginStatus']);

/*
|--------------------------------------------------------------------------
| USER PROTECTED ROUTES (Peserta)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    
    // My Registrations
    Route::get('/my-registrations', [LombaController::class, 'myRegistrations'])->name('my.registrations');
    
    // Registration Flow
    Route::get('/registration/success/{kode}', [RegistrationController::class, 'success'])->name('registration.success');
    Route::get('/registration/{id}/success', [RegistrationController::class, 'successById'])->name('registration.success.id');
    Route::get('/registration/{id}/payment', [RegistrationController::class, 'payment'])->name('registration.payment');
    Route::get('/payment/instructions/{registration_id}', [RegistrationController::class, 'paymentInstructions'])->name('payment.instructions');
    Route::post('/payment/upload-proof/{registration_id}', [RegistrationController::class, 'uploadProof'])->name('payment.upload-proof');
    Route::post('/registration/{id}/cancel', [RegistrationController::class, 'cancel'])->name('registration.cancel');
});

/*
|--------------------------------------------------------------------------
| EVENT REGISTRATION ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('event')->name('event.')->group(function () {
    // Public
    Route::get('/{id}', [PublicEventController::class, 'show'])->name('detail');
    
    // Protected Registration
    Route::middleware(['auth'])->group(function () {
        Route::get('/{id}/register', [LombaController::class, 'showRegisterForm'])->name('register.form');
        Route::post('/{id}/register', [LombaController::class, 'storeRegistration'])->name('register.store');
    });
});

// Legacy route
Route::get('/continue-registration', [LombaController::class, 'continueRegistration'])->name('continue.registration');

/*
|--------------------------------------------------------------------------
| TEST ROUTES (Hapus di Production)
|--------------------------------------------------------------------------
*/

Route::post('/event/{id}/register-test', [LombaController::class, 'testRegister']);
Route::get('/test-success/{id}', [LombaController::class, 'testSuccess']);

/*
|--------------------------------------------------------------------------
| STAFF ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('staff')->name('staff.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [StaffController::class, 'dashboardAlt'])->name('dashboard');

    // ===== EVENTS (STAFF) =====
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{id}', [EventController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EventController::class, 'update'])->name('update');
    Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
});
    
    // ===== REGISTRATIONS =====
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [StaffController::class, 'registrationsIndex'])->name('index');
        Route::get('/{id}', [StaffController::class, 'registrationsView'])->name('view');
        Route::post('/{id}/approve', [StaffController::class, 'registrationsApprove'])->name('approve');
        Route::post('/{id}/reject', [StaffController::class, 'registrationsReject'])->name('reject');
    });
    
    // ===== PAYMENTS =====
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [StaffController::class, 'paymentsIndex'])->name('index');
        Route::get('/{id}', [StaffController::class, 'paymentsView'])->name('view');
        Route::post('/{id}/verify', [StaffController::class, 'paymentsVerify'])->name('verify');
        Route::post('/{id}/reject', [StaffController::class, 'paymentsReject'])->name('reject');
    });
    
    // ===== PACKAGES =====
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [StaffController::class, 'packagesIndex'])->name('index');
        Route::get('/create', [StaffController::class, 'packagesCreate'])->name('create');
        Route::post('/', [StaffController::class, 'packagesStore'])->name('store');
        Route::get('/{id}/edit', [StaffController::class, 'packagesEdit'])->name('edit');
        Route::post('/{id}/update', [StaffController::class, 'packagesUpdate'])->name('update');
    });
    
    // ===== RESULTS =====
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [StaffController::class, 'resultsIndex'])->name('index');
        Route::get('/create', [StaffController::class, 'resultsCreate'])->name('create');
        Route::post('/', [StaffController::class, 'resultsStore'])->name('store');
        Route::get('/{id}/edit', [StaffController::class, 'resultsEdit'])->name('edit');
        Route::post('/{id}/update', [StaffController::class, 'resultsUpdate'])->name('update');
        Route::post('/{id}/delete', [StaffController::class, 'resultsDestroy'])->name('destroy');
        Route::get('/by-event/{event_id}', [StaffController::class, 'resultsByEvent'])->name('event');
    });
    
    // ===== EXPORT =====
    Route::get('/export', [ExportController::class, 'index'])->name('export.index');
    Route::get('/export/registrations', [ExportController::class, 'exportRegistrations'])->name('export.registrations');
    Route::get('/export/payments', [ExportController::class, 'exportPayments'])->name('export.payments');
    Route::get('/export/events', [ExportController::class, 'exportEvents'])->name('export.events');
    
    // ===== PROFILE =====
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StaffController::class, 'profileIndex'])->name('index');
        Route::get('/edit', [StaffController::class, 'profileEdit'])->name('edit');
        Route::post('/update', [StaffController::class, 'profileUpdate'])->name('update');
    });
    
    // Legacy route for verify cash
    Route::post('/registrations/verify-payment/{id}', [StaffController::class, 'verifyCashPayment'])->name('registrations.verify_cash');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // ===== USERS =====
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'usersIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'usersCreate'])->name('create');
        Route::post('/store', [AdminController::class, 'usersStore'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'usersEdit'])->name('edit');
        Route::post('/{id}/update', [AdminController::class, 'usersUpdate'])->name('update');
        Route::get('/{id}/delete', [AdminController::class, 'usersDestroy'])->name('destroy');
    });
    
    // ===== EVENTS =====
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{id}', [EventController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
    });
    
    // ===== PACKAGES =====
    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [AdminController::class, 'packagesIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'packagesCreate'])->name('create');
        Route::post('/store', [AdminController::class, 'packagesStore'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'packagesEdit'])->name('edit');
        Route::post('/{id}/update', [AdminController::class, 'packagesUpdate'])->name('update');
        Route::get('/{id}/delete', [AdminController::class, 'packagesDestroy'])->name('destroy');
    });
    
    // ===== REGISTRATIONS =====
    Route::prefix('registrations')->name('registrations.')->group(function () {
        Route::get('/', [AdminController::class, 'registrationsIndex'])->name('index');
        Route::get('/{id}/view', [AdminController::class, 'registrationsView'])->name('view');
    });
    
    // ===== PAYMENTS =====
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [AdminController::class, 'paymentsIndex'])->name('index');
        Route::get('/{id}/view', [AdminController::class, 'paymentsView'])->name('view');
        Route::post('/{id}/verify', [AdminController::class, 'paymentsVerify'])->name('verify');
        Route::post('/{id}/reject', [AdminController::class, 'paymentsReject'])->name('reject');
    });
    
    // ===== LOGS =====
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [AdminController::class, 'logsIndex'])->name('index');
    });
    
    // ===== PROFILE =====
    Route::get('/profile', [AdminController::class, 'profileIndex'])->name('profile');
    Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');
});