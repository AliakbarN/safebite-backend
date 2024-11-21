<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::view('/{any}', 'app')->where('any', '.*');

