<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;

Route::get('/', [StrukController::class, 'index'])->name('struks.index');
Route::get('/struks/create', [StrukController::class, 'create'])->name('struks.create');
Route::post('/struks', [StrukController::class, 'store'])->name('struks.store');
Route::get('/struks/{struk}/edit', [StrukController::class, 'edit'])->name('struks.edit');
Route::put('/struks/{struk}', [StrukController::class, 'update'])->name('struks.update');
Route::delete('/struks/{struk}', [StrukController::class, 'destroy'])->name('struks.destroy');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/struks/export/excel', [StrukController::class, 'exportExcel'])->name('struks.export.excel');
Route::get('/struks/export/csv', [StrukController::class, 'exportCSV'])->name('struks.export.csv');
<<<<<<< HEAD
Route::get('/struks/{struk}', [StrukController::class, 'show'])->name('struks.show');
=======
Route::get('/struks/{struk}', [StrukController::class, 'show'])->name('struks.show');
>>>>>>> a19649668aa87c9671b2d99401aca7bbf1836054
