<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;


// --- Auth Routes ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tampilkan form lupa password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');

// Kirim email reset password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

// Tampilkan form reset password
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

// Proses reset password
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');



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

Route::put('/struks/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
Route::post('/struks/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
Route::put('/struks/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
Route::delete('/struks/{struk}/item/{index}', [StrukController::class, 'deleteItem'])->name('struks.deleteItem');
