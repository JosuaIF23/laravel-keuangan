<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Struktur mengikuti TodoList lama tapi sudah disesuaikan dengan modul Keuangan.
|--------------------------------------------------------------------------
*/

// ==============================
// 🔑 AUTH
// ==============================
Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// ==============================
// 💰 APP (Dengan Middleware Login)
// ==============================
Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    // 🏠 Halaman utama (sekarang bernama app.home)
    Route::get('/home', [HomeController::class, 'keuangan'])->name('app.home');

    // 📄 Halaman detail transaksi
    Route::get('/keuangan/{id}', [HomeController::class, 'keuanganDetail'])->name('app.keuangan.detail');
});

// ==============================
// 🌐 DEFAULT REDIRECT
// ==============================
Route::get('/', function () {
    return redirect()->route('app.home');
});
