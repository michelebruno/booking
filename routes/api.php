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

Route::post('/webhooks/paypal', 'PaypalController@store');

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('account', function() { 
        
        $user = request()->user();
    
        if ( $user->ruolo == 'esercente' ) {
            return response( Esercente::findOrFail($user->id) );
        }
    
        return response($user);
    
    });
    
    Route::apiResource('users', 'API\UserController');

    Route::apiResource('clienti', 'API\ClienteController' , [ 'parameters' => [ 'clienti' => 'cliente' ] ] );
    Route::delete('clienti/{cliente}/forceDelete', 'API\ClienteController@forceDelete');

    Route::apiResource('deals', 'API\DealController');
    Route::patch('/deals/{deal}/restore', 'API\DealController@restore');
    
    Route::apiResource('deals.tariffe', 'API\DealTariffeController' , [ 'parameters' => [ 'tariffe' => 'tariffa' ] ] );

    Route::apiResource('deals.servizi', 'API\DealServizioController' , [ 'parameters' => [ 'servizi' => 'servizio' ] ] );
    
    Route::apiResource('ordini', 'API\OrdineController', [ 'parameters' => [ 'ordini' => 'ordine' ] ]);
    
    Route::apiResource('ordini.voci', 'API\VoceOrdineController', [ 'parameters' => [ 'ordini' => 'ordine', 'voci' => 'voce' ] ]);

    Route::post('/ordini/{ordine}/transazioni/paypal', 'API\OrdineTransazioneController@storePaypal' );

    Route::apiResource('ordini.transazioni', 'API\OrdineTransazioneController', [ 'parameters' => [ 'ordini' => 'ordine', 'transazioni' => 'transazione' ] ])
        ->only([ 'post' ]);

    Route::apiResource('settings', 'API\SettingController');
    
    Route::apiResource('servizi', 'API\ServizioController', [ 'parameters' => [ 'servizi' => 'servizio' ]])
        ->only([ 'get' , 'head' ]);
    
    Route::apiResource( 'esercenti.servizi' , 'API\EsercenteServizioController' , [ 'parameters' => [ 'servizi' => 'servizio' , 'esercenti' => 'esercente' ] ] );
    
    Route::get('/servizi', 'API\ServizioController@index');

    Route::patch('/esercenti/{esercente}/servizi/{servizio}/restore', 'API\EsercenteServizioController@restore');
    Route::post('/esercenti/{esercente}/servizi/{servizio}/tariffe', 'API\EsercenteServizioController@aggiungiTariffa');
    Route::patch('/esercenti/{esercente}/servizi/{servizio}/tariffe/{tariffa}', 'API\EsercenteServizioController@editTariffa');
    Route::delete('/esercenti/{esercente}/servizi/{servizio}/tariffe/{tariffa}', 'API\EsercenteServizioController@deleteTariffa'); // TODO una risorsa esercente.servizi.tariffe
    
    Route::apiResource('esercenti', 'API\EsercenteController', [ 'parameters' => [ 'esercenti' => 'esercente' ]]);
    
    Route::patch('/esercenti/{esercente}/restore', 'API\EsercenteController@restore');
    Route::patch('/esercenti/{esercente}/note', 'API\EsercenteController@setNote');


    
});
