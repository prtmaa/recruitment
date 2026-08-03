<?php

use App\Http\Controllers\Admin\AkunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\LokerController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\Admin\JobApplicationController as AdminJobApplicationController;
use App\Http\Controllers\Admin\SettingController;

// ==== Login pelamar via Google ====
Route::get('/auth/google', [GoogleController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
Route::post('/logout', [GoogleController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('index');
    Route::get('/datadiri', [DatadiriController::class, 'index'])->name('datadiri.index');
    Route::post('/datadiri', [DatadiriController::class, 'store'])->name('datadiri.store');

    Route::get('/loker', [LokerController::class, 'index'])->name('loker.index');

    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'apply'])
        ->name('jobs.apply');

    Route::get('/my-applications', [JobApplicationController::class, 'myApplications'])
        ->name('jobs.my-applications');

    Route::get('/lamaran', [LamaranController::class, 'index'])->name('lamaran.index');
});

// ==== Login admin (route /login, /register dari Breeze) ====
require __DIR__ . '/auth.php';

// ==== Area khusus admin ====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/cari-nik', [AdminDashboardController::class, 'cariNik'])
        ->name('dashboard.cariNik');

    Route::get('/job/data', [JobController::class, 'data'])->name('job.data');
    Route::resource('/job', JobController::class);

    Route::get('/seleksi', [AdminJobApplicationController::class, 'index'])->name('seleksi.index');
    Route::get('/seleksi/{job}', [AdminJobApplicationController::class, 'show'])->name('seleksi.show');
    Route::put('/seleksi/{application}/status', [AdminJobApplicationController::class, 'updateStatus'])->name('seleksi.update-status');

    Route::get('/job/{job}/interview', [JobController::class, 'getInterview'])->name('job.interview.get');
    Route::post('/job/{job}/interview', [JobController::class, 'saveInterview'])->name('job.interview.save');

    Route::get('/akun', [AkunController::class, 'index'])->name('akun.index');
    Route::get('/akun/data', [AkunController::class, 'data'])->name('akun.data');
    Route::delete('/akun/{akun}', [AkunController::class, 'destroy'])->name('akun.destroy');
    Route::get('/akun/{akun}/detail', [AkunController::class, 'detail'])->name('akun.detail');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
});
