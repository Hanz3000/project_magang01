<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PegawaiController; // Master Pegawai
use App\Http\Controllers\PengeluaranController;


// ------------------- AUTH ROUTES -------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/pengeluarans', [PengeluaranController::class, 'store'])->name('pengeluarans.store');
Route::resource('pegawais', PegawaiController::class);

// ------------------- FORGOT PASSWORD -------------------
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ------------------- PROTECTED ROUTES (AUTH REQUIRED) -------------------
Route::middleware('auth')->group(function () {

    // ------------------- REDIRECT HOME -------------------
    Route::get('/', fn() => redirect()->route('dashboard'));

    // ------------------- DASHBOARD -------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ------------------- MASTER BARANG -------------------
    Route::delete('/master-barang/bulk-delete', [MasterBarangController::class, 'bulkDelete'])->name('master-barang.bulk-delete');
    Route::resource('master-barang', MasterBarangController::class);

    // ------------------- MASTER PEGAWAI -------------------
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::delete('/pegawai/bulk-delete', [PegawaiController::class, 'bulkDelete'])->name('pegawai.bulk-delete');
    Route::resource('pegawai', \App\Http\Controllers\PegawaiController::class);



    // ------------------- STRUK -------------------
    Route::resource('struks', StrukController::class)->except(['index', 'show']);

    // Khusus Index dan Show dibuat manual agar tidak tertimpa
    Route::get('/struks', [StrukController::class, 'index'])->name('struks.index');
    Route::get('/struks/{struk}', [StrukController::class, 'show'])->name('struks.show');

    // filepath: routes/web.php
    Route::get('/struks/{struk}/items', [StrukController::class, 'items']);

    // Export Excel & CSV
    Route::get('/struks/export/excel', [StrukController::class, 'exportExcel'])->name('struks.export.excel');
    Route::get('/struks/export/csv', [StrukController::class, 'exportCSV'])->name('struks.export.csv');

    // Tambah, Update, dan Hapus item struk
    Route::post('/struks/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
    Route::put('/struks/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
    Route::put('/struks/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
    Route::delete('/struks/{struk}/item/{index}', [StrukController::class, 'deleteItem'])->name('struks.deleteItem');
});


Route::put('/struks/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
Route::post('/struks/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
Route::put('/struks/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
Route::delete('/struks/{struk}/item/{index}', [StrukController::class, 'deleteItem'])->name('struks.deleteItem');

Route::get('/struks/autocomplete-items', [StrukController::class, 'autocompleteItems'])->name('struks.autocomplete-items');
Route::get('/struks/search-barang', [StrukController::class, 'searchBarang'])->name('struks.search-barang');


Route::prefix('pengeluarans')->name('pengeluarans.')->group(function () {
    // Export routes
    Route::get('/export/excel', [PengeluaranController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/csv', [PengeluaranController::class, 'exportCSV'])->name('export.csv');

    // Resource routes
    Route::get('/', [PengeluaranController::class, 'index'])->name('index');
    Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
    Route::post('/', [PengeluaranController::class, 'store'])->name('store');
    Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->whereNumber('pengeluaran')->name('edit');
    Route::get('/{pengeluaran}', [PengeluaranController::class, 'show'])->whereNumber('pengeluaran')->name('show');
    Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->whereNumber('pengeluaran')->name('update');
    Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->whereNumber('pengeluaran')->name('destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/pengeluarans', [PengeluaranController::class, 'index'])->name('pengeluarans.index');
    Route::get('/pengeluarans/create', [PengeluaranController::class, 'create'])->name('pengeluarans.create');
    Route::post('/pengeluarans', [PengeluaranController::class, 'store'])->name('pengeluarans.store');
    Route::get('/pengeluarans/{pengeluaran}', [PengeluaranController::class, 'show'])->name('pengeluarans.show');
});