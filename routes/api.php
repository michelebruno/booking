<?php

use App\Http\Resources\User;
use App\User as AppUser;
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->prefix('users')->group(function() {
    Route::get('/', function (Request $request) {
        return User::collection( AppUser::all() );
    });

    Route::get('{id}', function($id) {
        try {
            return new User( AppUser::findOrFail($id));
        } catch (\Throwable $th) {
            // TODO 
            echo 'non trovato';
        }
    });
});