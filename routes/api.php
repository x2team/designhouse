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

// Route::get('/', function(){
//     return response()->json(['message' => 'Hello word'], 200);
// });


// Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function(){

});



// Routes for guest only
Route::group(['middleware' => ['guest:api']], function(){

    Route::post('register', 'App\Http\Controllers\Auth\RegisterController@register');
    Route::post('verification/verify', 'App\Http\Controllers\Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'App\Http\Controllers\Auth\VerificationController@resend');

});
