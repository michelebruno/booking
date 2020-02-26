<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('app')->group(function() { 
        
    Route::get('/{a?}/{b?}/{c?}/{d?}', function () { 
        return view('app');
    });

});

Route::get('/', function () { 
    return redirect('/login');
});

Route::get('cart', function () {
    return view('cart');
});

Auth::routes(['verify' => true , 'register' => false]);

Route::get('/logout', 'Auth\LoginController@logout');
