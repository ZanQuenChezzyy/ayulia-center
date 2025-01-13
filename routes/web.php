<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/pendaftaran', [LandingPageController::class, 'pendaftaran'])->name('pendaftaran');
Route::post('/pendaftaran/daftar', [LandingPageController::class, 'storePendaftaran'])->name('pendaftaran.store');
