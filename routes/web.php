<?php

use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Volt::route('/', 'portfolio')->name('portfolio');
Volt::route('/portfolio', 'portfolio')->name('portfolio.home');
Volt::route('/portfolio/contact-form', 'contact-form')->name('contact.form');

Route::get('/login', Login::class)->name('login.web')->middleware('guest.jwt');

Route::middleware('jwt.session')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name(name: 'logout');
});