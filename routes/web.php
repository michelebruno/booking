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

Route::get('/test', function ()
{
    return view('test');
});

Route::get('/api/v1/auth', function ()
{
    $user = request()->user();
    $user->jwt = 'tokennnnn';
    return response()->json($user);
})->middleware('auth');

Route::get('/', function () {
    if ( Auth::id() ) return view('app');
    return redirect('/login');
});

Route::middleware('auth')->group(function() {


    Route::get('/esercenti/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/ordini/{a?}/{b?}', function() {
        return view('app');
    });
    
    Route::get('/dashboard', function() {
        return view('app');
    });
    
    Route::get('/deals/{a?}/{b?}', function() {
        return view('app');
    });

});

Auth::routes();

Route::get('/logout', 'Auth\LoginController@logout');
 
