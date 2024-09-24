<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

// Home routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Login & Logout routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::match(['get','post'],'/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->middleware('auth');

// Search bar routes
Route::get('/search', [DashboardController::class, 'showDashboard'])->name('search');