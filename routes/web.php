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

Route::get('/api/v1/auth', function ()
{
    $user = request()->user();
    return response()->json($user->makeVisible(['api_token']));
})->middleware('auth');


Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/', function () { 
        return view('app');
    });

    Route::get('/esercenti/{a?}/{b?}/{c?}/{d?}', function() {
        return view('app');
    });

    Route::get('/utenti/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/clienti/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/ordini/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/dashboard', function() {
        return view('app');
    });
    
    Route::get('/account/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/deals/{a?}/{b?}', function() {
        return view('app');
    });

    Route::get('/settings/{a?}/{b?}', function() {
        return view('app');
    });

});

Route::get('/', function () { 
    return redirect('/login');
});

Auth::routes(['verify' => true , 'register' => false]);

Route::get('/logout', 'Auth\LoginController@logout');
