<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Route untuk Dashboard Utama
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Route untuk Smart Search
Route::get('/smart-search', [DashboardController::class, 'smartSearch'])->name('smart.search');

// Route untuk AI Assistant
Route::get('/chat-ai', [DashboardController::class, 'chatAi'])->name('chat.ai');

// 👇 TAMBAHKAN BARIS INI UNTUK MEMPERBAIKI ERROR 👇
Route::get('/preview', [DashboardController::class, 'previewDocument'])->name('document.preview');