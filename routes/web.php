<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;


Volt::route('/', 'portfolio')->name('portfolio');
Volt::route('/portfolio', 'portfolio')->name('portfolio.home');
Volt::route('/portfolio/contact-form', 'contact-form')->name('contact.form');
// Route::get('/portfolio1', function () {
//     return view('livewire.portfolio');
// })->name('portfolio');

// Route::get('/portfolio', function () {
//     return view('livewire.portfolio');
// })->name('portfolio.home');