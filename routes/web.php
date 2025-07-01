<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\StrukController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\TransaksiController;

// ------------------- AUTH ROUTES -------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::prefix('master-barang')->group(function () {
        Route::delete('/bulk-delete', [MasterBarangController::class, 'bulkDelete'])->name('master-barang.bulk-delete');
        Route::get('/', [MasterBarangController::class, 'index'])->name('master-barang.index');
        Route::get('/create', [MasterBarangController::class, 'create'])->name('master-barang.create');
        Route::post('/', [MasterBarangController::class, 'store'])->name('master-barang.store');
        Route::get('/{master_barang}/edit', [MasterBarangController::class, 'edit'])->name('master-barang.edit');
        Route::put('/{master_barang}', [MasterBarangController::class, 'update'])->name('master-barang.update');
        Route::delete('/{master_barang}', [MasterBarangController::class, 'destroy'])->name('master-barang.destroy');
    });

    // ------------------- MASTER PEGAWAI -------------------
    Route::prefix('pegawai')->group(function () {
        Route::delete('/bulk-delete', [PegawaiController::class, 'bulkDelete'])->name('pegawai.bulk-delete');
        Route::get('/', [PegawaiController::class, 'index'])->name('pegawai.index');
        Route::get('/create', [PegawaiController::class, 'create'])->name('pegawai.create');
        Route::post('/', [PegawaiController::class, 'store'])->name('pegawai.store');
        Route::get('/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
        Route::put('/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
        Route::delete('/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    });

    // ------------------- TRANSAKSI GABUNGAN -------------------
    Route::prefix('transaksi')->group(function () {
        Route::get('/create', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/struk', [TransaksiController::class, 'storeStruk'])->name('transaksi.store.struk');
        Route::post('/pengeluaran', [TransaksiController::class, 'storePengeluaran'])->name('transaksi.store.pengeluaran');
    });

    // ------------------- STRUK (PEMASUKAN) -------------------
    Route::prefix('struks')->group(function () {
        Route::get('/', [StrukController::class, 'index'])->name('struks.index');
        Route::get('/create', [StrukController::class, 'create'])->name('struks.create');
        Route::post('/', [StrukController::class, 'store'])->name('struks.store');
        Route::get('/{struk}', [StrukController::class, 'show'])->name('struks.show');
        Route::get('/{struk}/edit', [StrukController::class, 'edit'])->name('struks.edit');
        Route::put('/{struk}', [StrukController::class, 'update'])->name('struks.update');
        Route::delete('/{struk}', [StrukController::class, 'destroy'])->name('struks.destroy');
        Route::delete('/bulk-delete', [StrukController::class, 'bulkDelete'])->name('struks.bulk-delete');

        // Item routes
        Route::get('/{struk}/items', [StrukController::class, 'items']);
        Route::post('/{id}/item', [StrukController::class, 'addItem'])->name('struks.addItem');
        Route::put('/{struk}/item/{index}', [StrukController::class, 'updateItem'])->name('struks.updateItem');
        Route::put('/{struk}/update-items', [StrukController::class, 'updateItems'])->name('struks.updateItems');
        Route::delete('/{struk}/item/{index}', [StrukController::class, 'deleteItem'])->name('struks.deleteItem');

        // Search routes
        Route::get('/autocomplete-items', [StrukController::class, 'autocompleteItems'])->name('struks.autocomplete-items');
        Route::get('/search-barang', [StrukController::class, 'searchBarang'])->name('struks.search-barang');

        // Export routes
        Route::get('/export/excel', [StrukController::class, 'exportExcel'])->name('struks.export.excel');
        Route::get('/export/csv', [StrukController::class, 'exportCSV'])->name('struks.export.csv');
    });

    // ------------------- PENGELUARAN -------------------
    Route::prefix('pengeluarans')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('pengeluarans.index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('pengeluarans.create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('pengeluarans.store');
        Route::get('/{pengeluaran}', [PengeluaranController::class, 'show'])->name('pengeluarans.show');
        Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('pengeluarans.edit');
        Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->name('pengeluarans.update');
        Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('pengeluarans.destroy');

        // Route khusus untuk pengeluaran dari struk
        Route::get('/from-struk/{struk}', [PengeluaranController::class, 'createByStruk'])
            ->name('pengeluarans.create-by-struk');
        Route::post('/from-struk/{struk}', [PengeluaranController::class, 'storeByStruk'])
            ->name('pengeluarans.store-by-struk');
    });

    Route::get('pengeluarans/export/excel', [PengeluaranController::class, 'exportExcel'])->name('pengeluarans.export.excel');
    Route::get('pengeluarans/export/csv', [PengeluaranController::class, 'exportCsv'])->name('pengeluarans.export.csv');
});
