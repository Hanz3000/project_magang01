<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MasterBarangController;

// ------------------- AUTH ROUTES -------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ------------------- MASTER BARANG ROUTES -------------------
Route::middleware('auth')->group(function () {
    Route::resource('master-barang', MasterBarangController::class);
});
Route::get('/master-barang/create', [MasterBarangController::class, 'create'])->name('master-barang.create');
Route::post('/master-barang', [MasterBarangController::class, 'store'])->name('master-barang.store');
Route::resource('master-barang', MasterBarangController::class);
Route::resource('master-barang', App\Http\Controllers\MasterBarangController::class)->middleware('auth');

// ------------------- PROTECTED ROUTES -------------------
Route::middleware('auth')->group(function () {

    // Halaman utama redirect ke daftar struk
    Route::get('/', function () {
        return redirect()->route('struks.index');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // STRUK: Route resource, kecuali index & show
    Route::resource('struks', StrukController::class)->except(['index', 'show']);

    // Index dan Show didefinisikan manual agar tidak tertimpa
    Route::get('/struks', [StrukController::class, 'index'])->name('struks.index');
    Route::get('/struks/{struk}', [StrukController::class, 'show'])->name('struks.show');

    // Export Excel & CSV
    Route::get('/struks/export/excel', [StrukController::class, 'exportExcel'])->name('struks.export.excel');
    Route::get('/struks/export/csv', [StrukController::class, 'exportCSV'])->name('struks.export.csv');

    // Tambah/Update item struk
    Route::post('/struks/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
    Route::put('/struks/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
    Route::put('/struks/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
});
<<<<<<< HEAD

Route::put('/struks/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
Route::post('/struks/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
Route::put('/struks/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
Route::delete('/struks/{struk}/item/{index}', [StrukController::class, 'deleteItem'])->name('struks.deleteItem');
=======
>>>>>>> 23c926d54c4f10be5c3df7171725c4b598c60ae4
