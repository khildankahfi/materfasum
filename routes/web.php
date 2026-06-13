<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\ReportController as UserReportController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    
    $totalReports = \App\Models\Report::count();
    $resolvedReports = \App\Models\Report::where('status', 'selesai')->count();
    $totalUsers = \App\Models\User::where('role', 'user')->count();
    
    return view('welcome', compact('totalReports', 'resolvedReports', 'totalUsers'));
});

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[RegisterController::class, 'register'])->name('register.post');
    Route::get('/forgot-password',        [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password',       [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── User Panel ──
Route::middleware(['auth', 'user.role'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/map', [UserReportController::class, 'map'])->name('reports.map');
    Route::resource('reports', UserReportController::class)
         ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/notifications',             [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',  [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all',   [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ── Admin Panel ──
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Laporan
    Route::get('/reports',                         [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/map',                     [AdminReportController::class, 'map'])->name('reports.map');
    Route::get('/reports/export-csv',              [AdminReportController::class, 'exportCsv'])->name('reports.export-csv');
    Route::get('/reports/export-pdf',              [AdminReportController::class, 'exportPdf'])->name('reports.export-pdf');
    Route::get('/reports/{report}',                [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/update-status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::delete('/reports/{report}',             [AdminReportController::class, 'destroy'])->name('reports.destroy');

    // User Management
    Route::get('/users',                 [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
    Route::delete('/users/{user}',       [AdminUserController::class, 'destroy'])->name('users.destroy');
});