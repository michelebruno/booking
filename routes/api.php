<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:api'], 'namespace' => 'API'], function () {

    Route::get('account', 'UserController@showCurrent');

    Route::apiResource('users', 'UserController');

    Route::apiResource('clienti', 'ClienteController', ['parameters' => ['clienti' => 'cliente']]);
    Route::delete('clienti/{cliente}/forceDelete', 'ClienteController@forceDelete');

    Route::apiResource('deals', 'DealController');
    Route::patch('/deals/{deal}/restore', 'DealController@restore');

    Route::apiResource('deals.tariffe', 'DealTariffeController', ['parameters' => ['tariffe' => 'variante']]);

    Route::apiResource('deals.forniture', 'DealFornituraController', ['parameters' => ['forniture' => 'fornitura']]);

    Route::apiResource('ordini', 'OrdineController', ['parameters' => ['ordini' => 'ordine']]);

    Route::apiResource('ordini.voci', 'VoceOrdineController', ['parameters' => ['ordini' => 'ordine', 'voci' => 'voce']]);

    Route::post('/ordini/{ordine}/transazioni/paypal', 'OrdineTransazioneController@storePaypal');

    Route::apiResource('ordini.transazioni', 'OrdineTransazioneController', ['parameters' => ['ordini' => 'ordine', 'transazioni' => 'transazione']])
        ->only(['post']);

    Route::apiResource('settings', 'SettingController');

    Route::apiResource('forniture', 'FornituraController', ['parameters' => ['forniture' => 'fornitura']])
        ->only(['get', 'head']);

    Route::apiResource('fornitori.forniture', 'FornitoreFornituraController', ['parameters' => ['forniture' => 'fornitura', 'fornitori' => 'fornitore']]);

    Route::get('/forniture', 'FornituraController@index');

    Route::patch('/fornitori/{fornitore}/forniture/{fornitura}/restore', 'FornitoreFornituraController@restore');
    Route::post('/fornitori/{fornitore}/forniture/{fornitura}/tariffe', 'FornitoreFornituraController@aggiungiTariffa');
    Route::patch('/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}', 'FornitoreFornituraController@editTariffa');
    Route::delete('/fornitori/{fornitore}/forniture/{fornitura}/tariffe/{tariffa}', 'FornitoreFornituraController@deleteTariffa'); // TODO una risorsa fornitore.forniture.tariffe

    Route::apiResource('fornitori', 'FornitoreController', ['parameters' => ['fornitori' => 'fornitore']]);

    Route::patch('/fornitori/{fornitore}/restore', 'FornitoreController@restore');
    Route::patch('/fornitori/{fornitore}/note', 'FornitoreController@setNote');
});
