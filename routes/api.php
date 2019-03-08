<?php

use App\Models\Signature;
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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::get('signatures', 'SignatureController@index');
Route::get('signatures/{id}', 'SignatureController@show');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('signatures', 'SignatureController@store');
    Route::put('signatures/{id}', 'SignatureController@update');
    Route::delete('signatures/{id}', 'SignatureController@destroy');
});