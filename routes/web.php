<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Rute Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Rute untuk Simulasi Fitur
Route::get('/chat-ai', [DashboardController::class, 'chatAi'])->name('chat.ai');
Route::get('/smart-search', [DashboardController::class, 'smartSearch'])->name('smart.search');