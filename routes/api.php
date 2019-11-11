<?php

use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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


Route::get('/users/me', function() { 
    return response()->json(User::find(Auth::id())); 
})->middleware('auth:api');

Route::apiResource('users', 'API\UserController')
    ->middleware('auth:api');

Route::apiResource('settings', 'API\SettingController')
    ->middleware('auth:api');

Route::apiResource('esercenti', 'API\EsercenteController')
    ->middleware('auth:api');