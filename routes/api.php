<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');


Route::middleware('auth:sanctum')->group(function(){
    Route::get('data', 'AuthController@data');
    Route::post('logout', 'AuthController@logout');

    Route::prefix('posts')->group(function() {
        Route::get('/', 'PostController@fetch');
        Route::match(['post', 'put'], 'submit', 'PostController@submit');
        Route::delete('delete/{id}', 'PostController@delete');
    });
});