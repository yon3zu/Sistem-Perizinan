<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Security\IzinController;
use App\Http\Controllers\Katim\ApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\KaryawanController;
use Illuminate\Support\Facades\Route;

// Redirect root
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::delete('/profil/foto', [ProfileController::class, 'hapusFoto'])->name('profil.hapusFoto');

    // Security routes (accessible by security and admin)
    Route::middleware('role:security,admin')->group(function () {
        Route::get('/izin', [IzinController::class, 'index'])->name('izin.index');
        Route::get('/izin/buat', [IzinController::class, 'create'])->name('izin.create');
        Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');
        Route::get('/monitor', [IzinController::class, 'semuaIzin'])->name('izin.monitor');
        Route::get('/izin/{id}', [IzinController::class, 'show'])->name('izin.show');
        Route::post('/izin/{id}/kembali', [IzinController::class, 'konfirmasiKembali'])->name('izin.kembali');
    });

    // Katim routes
    Route::middleware('role:katim,admin')->group(function () {
        Route::get('/approval', [ApprovalController::class, 'index'])->name('approval.index');
        Route::get('/approval/{id}', [ApprovalController::class, 'show'])->name('approval.show');
        Route::post('/approval/{id}/setujui', [ApprovalController::class, 'approve'])->name('approval.approve');
        Route::post('/approval/{id}/tolak', [ApprovalController::class, 'reject'])->name('approval.reject');
    });

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/buat', [UserController::class, 'create'])->name('users.create');
        Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
        Route::get('/pengguna/{id}/ubah', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::get('/karyawan/buat', [KaryawanController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan', [KaryawanController::class, 'store'])->name('karyawan.store');
        Route::get('/karyawan/{id}', [KaryawanController::class, 'show'])->name('karyawan.show');
        Route::get('/karyawan/{id}/ubah', [KaryawanController::class, 'edit'])->name('karyawan.edit');
        Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

        // Teams
        Route::get('/tim', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/tim/buat', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/tim', [TeamController::class, 'store'])->name('teams.store');
        Route::get('/tim/{id}/ubah', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/tim/{id}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/tim/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');

        // Reports
        Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    });
});
