<?php

use App\Http\Resources\UserResource;
use App\User as AppUser;
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
    if(Gate::allows('read-users', true)){ 
        return response()->json(Auth::user());
    } else return ['non autorizzato'];
});
Route::apiResource('users', 'API\UserController')->middleware('auth:api');