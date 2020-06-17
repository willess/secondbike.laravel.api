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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
// Route::apiResource('/advertisements', 'API\AdvertisementController')->middleware('auth:api');

Route::middleware('auth:api')->group( function () {
    Route::apiResource('/advertisements', 'API\AdvertisementController', ['except' => 'show', 'update']);
    Route::get('/advertisements/{id}', 'API\AdvertisementController@show');
    Route::put('/advertisements/{id}', 'API\AdvertisementController@update');
});
