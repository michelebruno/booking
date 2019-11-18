<?php

use App\Models\Esercente;
use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('account', function() { 
    
    $user = request()->user();

    if ( $user->ruolo == 'esercente' ) {
        return response( Esercente::findOrFail($user->id) );
    }

    return response($user);

})->middleware('auth:api');

Route::apiResource('users', 'API\UserController')
    ->middleware('auth:api');

Route::apiResource('settings', 'API\SettingController')
    ->middleware('auth:api');

Route::apiResource('esercenti', 'API\EsercenteController', [ 'parameters' => [ 'esercenti' => 'esercente' ]])
    ->middleware('auth:api');

Route::patch('/esercenti/{esercente}/restore', 'API\EsercenteController@restore')->middleware('auth:api');