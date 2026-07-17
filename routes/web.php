<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Semua user login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('absensi', AbsensiController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/anggota', [ProfileController::class, 'linkAnggota'])->name('profile.link-anggota');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Kegiatan: semua bisa lihat, hanya pengurus bisa CRUD
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/{kegiatan}', [KegiatanController::class, 'show'])
        ->name('kegiatan.show')
        ->whereNumber('kegiatan');
    // Dokumen folder routes — harus SEBELUM resource agar tidak bentrok
    Route::get('/dokumen/folder/{folder}/detail', [DokumenController::class, 'folderDetail'])->name('dokumen.folder-detail');
    Route::patch('/dokumen/folder/{folder}', [DokumenController::class, 'folderUpdate'])->name('dokumen.folder.update');
    Route::delete('/dokumen/folder/{folder}', [DokumenController::class, 'folderDestroy'])->name('dokumen.folder.destroy');
    Route::get('/dokumen/{kodeKegiatan}/folder', [DokumenController::class, 'folder'])->name('dokumen.folder');
    Route::post('/dokumen/{kodeKegiatan}/folder', [DokumenController::class, 'folderStore'])->name('dokumen.folder.store');
    // Dokumen resource — {dokumen} dibatasi hanya angka agar tidak bentrok dengan /folder/*
    Route::resource('dokumen', DokumenController::class)
        ->parameters(['dokumen' => 'dokumen'])
        ->except('show')
        ->where(['dokumen' => '[0-9]+']);
});

// Hanya pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::resource('members', MemberController::class);
    Route::resource('kegiatan', KegiatanController::class)->except(['index', 'show']);
    Route::resource('keuangan', KeuanganController::class)->parameters(['keuangan' => 'keuangan'])->except('show');
    Route::get('/reports', [LaporanController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{jenis}', [LaporanController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
