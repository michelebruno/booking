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
Route::group(['middleware' => ['auth:api']], function () {

    Route::get('account', function() { 
        
        $user = request()->user();
    
        if ( $user->ruolo == 'esercente' ) {
            return response( Esercente::findOrFail($user->id) );
        }
    
        return response($user);
    
    });
    
    Route::apiResource('users', 'API\UserController');
    
    Route::apiResource('settings', 'API\SettingController');
    
    Route::apiResource('servizi', 'API\ServizioController', [ 'parameters' => [ 'servizi' => 'servizio' ]])
        ->only([ 'get' , 'head' ]);
    
    Route::apiResource( 'esercenti.servizi' , 'API\EsercenteServizioController' , [ 'parameters' => [ 'servizi' => 'servizio' , 'esercenti' => 'esercente' ] ] );
    
    Route::apiResource('esercenti', 'API\EsercenteController', [ 'parameters' => [ 'esercenti' => 'esercente' ]]);
    
    Route::patch('/esercenti/{esercente}/restore', 'API\EsercenteController@restore');
    Route::patch('/esercenti/{esercente}/note', 'API\EsercenteController@setNote');
    
});
