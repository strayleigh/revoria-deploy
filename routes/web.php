<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisiController;
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

// Pengurus dan Pembina
Route::middleware(['auth', 'role:pengurus,pembina'])->group(function () {
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{member}', [MemberController::class, 'show'])
        ->name('members.show')
        ->whereNumber('member');
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/reports', [LaporanController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/{jenis}', [LaporanController::class, 'export'])->name('reports.export');
});

// Hanya pengurus
Route::middleware(['auth', 'role:pengurus'])->group(function () {
    Route::patch('/members/{member}/assign', [MemberController::class, 'assignUser'])->name('members.assign');
    Route::resource('members', MemberController::class)->except(['index', 'show']);
    Route::resource('divisi', DivisiController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('kegiatan', KegiatanController::class)->except(['index', 'show']);
    Route::resource('keuangan', KeuanganController::class)->parameters(['keuangan' => 'keuangan'])->except(['index', 'show']);
    Route::get('/absensi/kegiatan/{kodeKegiatan}', [AbsensiController::class, 'getAbsensiByKegiatan'])->name('absensi.kegiatan');
});

require __DIR__.'/auth.php';
