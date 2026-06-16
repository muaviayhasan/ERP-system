<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Admin panel (layout/theme shell — pages are wired per module as they are built).
Route::view('/dashboard', 'dashboard')->name('dashboard');
