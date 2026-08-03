<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DatadiriController;
use App\Http\Controllers\Auth\GoogleController;

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
});
