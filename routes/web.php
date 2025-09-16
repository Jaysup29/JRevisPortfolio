<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;


Volt::route('/', 'portfolio')->name('portfolio');
Volt::route('/portfolio', 'portfolio')->name('portfolio.home');
Volt::route('/portfolio/contact-form', 'contact-form')->name('contact.form');

Route::get('/login', \App\Livewire\Login::class)->name('login');