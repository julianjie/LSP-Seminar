<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AccountVerificationController;
use App\Http\Controllers\Admin\SeminarController;
use App\Http\Controllers\Admin\RegistrationAdminController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Participant\ParticipantDashboardController;
use App\Http\Controllers\Participant\ParticipantRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---
Route::get('/', [PublicController::class, 'welcome'])->name('welcome');
Route::get('/seminars', [PublicController::class, 'allSeminars'])->name('public.seminars');
Route::get('/seminars/{slug}', [PublicController::class, 'seminarDetail'])->name('public.seminar.detail');

// --- Auth Guest Routes ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- Auth Logout Route ---
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// --- Admin Panel Routes ---
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Verifikasi Akun Peserta
        Route::get('/account-verification', [AccountVerificationController::class, 'index'])->name('accounts.index');
        Route::post('/account-verification/{user}/status', [AccountVerificationController::class, 'updateStatus'])->name('accounts.status');

        // CRUD Seminar
        Route::resource('seminars', SeminarController::class);

        // Verifikasi Pembayaran & Pendaftaran
        Route::get('/registrations', [RegistrationAdminController::class, 'index'])->name('registrations.index');
        Route::post('/registrations/{registration}/verify', [RegistrationAdminController::class, 'verifyPayment'])->name('registrations.verify');

        // CRUD Pengumuman
        Route::resource('announcements', AnnouncementController::class);
    });

// --- Participant Panel Routes ---
Route::prefix('participant')
    ->name('participant.')
    ->middleware(['auth', 'role:participant', 'approved']) // Harus Login, bertindak sebagai Participant, dan Akun Berstatus Approved
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');

        // Pendaftaran Seminar
        Route::post('/seminars/{seminar}/register', [ParticipantRegistrationController::class, 'register'])->name('seminars.register');
        Route::get('/registrations/{registration}', [ParticipantRegistrationController::class, 'show'])->name('registrations.show');
        Route::post('/registrations/{registration}/upload-payment', [ParticipantRegistrationController::class, 'uploadPayment'])->name('registrations.upload_payment');
    });
