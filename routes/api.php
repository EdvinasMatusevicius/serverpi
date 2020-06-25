<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('API\Auth')->prefix('auth')->group(function (){
    
    Route::middleware('guest:api')->group(function (){
        Route::post('register', 'AuthenticationController@register');
        Route::post('login', 'AuthenticationController@login');
        Route::get('users', function () {
            return ['api'=>'route'];
        });
    });


    Route::middleware('auth:api')->group(function (){
        Route::post('logout', 'AuthenticationController@logout');

    });
});
