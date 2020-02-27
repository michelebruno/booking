<?php

use Illuminate\Support\Facades\Route;
use App\Esercente;
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

Route::group(['middleware' => ['auth:api'] , 'namespace' => 'API' ], function () {

    Route::get('account', function() { 
        
        $user = request()->user();
    
        if ( $user->ruolo == 'esercente' ) {
            return response( Esercente::findOrFail($user->id) );
        }
    
        return response($user);
    
    });
    
    Route::apiResource('users', 'UserController');

    Route::apiResource('clienti', 'ClienteController' , [ 'parameters' => [ 'clienti' => 'cliente' ] ] );
    Route::delete('clienti/{cliente}/forceDelete', 'ClienteController@forceDelete');

    Route::apiResource('deals', 'DealController');
    Route::patch('/deals/{deal}/restore', 'DealController@restore');
    
    Route::apiResource('deals.tariffe', 'DealTariffeController' , [ 'parameters' => [ 'tariffe' => 'tariffa' ] ] );

    Route::apiResource('deals.servizi', 'DealServizioController' , [ 'parameters' => [ 'servizi' => 'servizio' ] ] );
    
    Route::apiResource('ordini', 'OrdineController', [ 'parameters' => [ 'ordini' => 'ordine' ] ]);
    
    Route::apiResource('ordini.voci', 'VoceOrdineController', [ 'parameters' => [ 'ordini' => 'ordine', 'voci' => 'voce' ] ]);

    Route::post('/ordini/{ordine}/transazioni/paypal', 'OrdineTransazioneController@storePaypal' );

    Route::apiResource('ordini.transazioni', 'OrdineTransazioneController', [ 'parameters' => [ 'ordini' => 'ordine', 'transazioni' => 'transazione' ] ])
        ->only([ 'post' ]);

    Route::apiResource('settings', 'SettingController');
    
    Route::apiResource('servizi', 'ServizioController', [ 'parameters' => [ 'servizi' => 'servizio' ]])
        ->only([ 'get' , 'head' ]);
    
    Route::apiResource( 'esercenti.servizi' , 'EsercenteServizioController' , [ 'parameters' => [ 'servizi' => 'servizio' , 'esercenti' => 'esercente' ] ] );
    
    Route::get('/servizi', 'ServizioController@index');

    Route::patch('/esercenti/{esercente}/servizi/{servizio}/restore', 'EsercenteServizioController@restore');
    Route::post('/esercenti/{esercente}/servizi/{servizio}/tariffe', 'EsercenteServizioController@aggiungiTariffa');
    Route::patch('/esercenti/{esercente}/servizi/{servizio}/tariffe/{tariffa}', 'EsercenteServizioController@editTariffa');
    Route::delete('/esercenti/{esercente}/servizi/{servizio}/tariffe/{tariffa}', 'EsercenteServizioController@deleteTariffa'); // TODO una risorsa esercente.servizi.tariffe
    
    Route::apiResource('esercenti', 'EsercenteController', [ 'parameters' => [ 'esercenti' => 'esercente' ]]);
    
    Route::patch('/esercenti/{esercente}/restore', 'EsercenteController@restore');
    Route::patch('/esercenti/{esercente}/note', 'EsercenteController@setNote');
    
});
