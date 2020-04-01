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

Route::middleware(['auth', 'verified'])->prefix('app')->group(function () {

    Route::get('/{a?}/{b?}/{c?}/{d?}', 'GeneralController@returApp');
});

Route::get('/', 'GeneralController@returnApp');

Route::get('cart', 'CartController@index')->name('cart');
Route::post('cart/checkout', 'CartController@chiudi')->name('cart.checkout');
Route::get('cart/checkout/{ordine}', 'CartController@checkout')->name('cart.payment')->middleware(['auth', 'verified']);
Route::post('cart', 'CartController@store')->name('cart.store');
Route::delete('cart', 'CartController@destroy')->name('cart.destroy');
Route::delete('cart/{index}', 'CartController@deleteIndex')->name('cart.delete');

Auth::routes(['verify' => true]);

Route::get('/logout', 'Auth\LoginController@logout');
