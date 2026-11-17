<?php

use App\Http\Controllers\DecoraHomeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'decora.home')->name('decora.home');
Route::view('/proyek', 'decora.portal.projects')->name('decora.portal.projects');
Route::view('/inventaris', 'decora.portal.inventory')->name('decora.portal.inventory');
Route::view('/produksi', 'decora.portal.production')->name('decora.portal.production');
Route::view('/alur-kerja', 'decora.portal.workflow')->name('decora.portal.workflow');

Route::get('/dashboard', [DecoraHomeController::class, 'dashboard'])
    ->middleware('auth')
    ->name('decora.dashboard');
Route::get('/modul/{module}', [DecoraHomeController::class, 'module'])->name('decora.module');
Route::get('/gudang-inventaris', [DecoraHomeController::class, 'inventory'])->name('decora.inventory');
Route::post('/gudang-inventaris', function () {
    return redirect()
        ->route('decora.inventory')
        ->with('status', 'Modul barang belum tersedia. Data tidak disimpan.');
})->name('decora.inventory.store');
