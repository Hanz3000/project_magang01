<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// --- Auth Routes ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Protected Routes ---
Route::middleware('auth')->group(function () {

    // Halaman utama redirect ke daftar struk
    Route::get('/', function () {
        return redirect()->route('struks.index');
    });

    // Resource route untuk struk
    Route::resource('struks', StrukController::class)->except(['index', 'show']); // supaya tidak dobel

    // Route index dan show tetap didefinisikan manual agar bisa diakses tanpa override
    Route::get('/struks', [StrukController::class, 'index'])->name('struks.index');
    Route::get('/struks/{struk}', [StrukController::class, 'show'])->name('struks.show');

    // Export routes
    Route::get('/struks/export/excel', [StrukController::class, 'exportExcel'])->name('struks.export.excel');
    Route::get('/struks/export/csv', [StrukController::class, 'exportCSV'])->name('struks.export.csv');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
});
