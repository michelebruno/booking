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
Route::get('/test', function ()
{
    return view('test');
});


Route::get('/', function () {
    return view('app');
});

Route::get('/esercenti/{a?}/{b?}', function() {
    return view('app');
});

Route::get('/ordini/{a?}/{b?}', function() {
    return view('app');
});

Route::get('/prodotti/{a?}/{b?}', function() {
    return view('app');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
