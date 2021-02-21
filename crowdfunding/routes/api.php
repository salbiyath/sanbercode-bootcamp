<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\UserController;
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

// Route::post('register', 'Auth\RegisterController');

Route::post('auth/register', RegisterController::class);
Route::post('auth/regenerate-otp', [OtpController::class, 'update']);
Route::post('auth/verification', [OtpController::class, 'verification']);
Route::post('auth/update-password', [UserController::class, 'updatePassword']);

Route::post('auth/login', LoginController::class);
Route::post('logout', LogoutController::class);

Route::middleware(['auth:api'])->group(function () {
    Route::post('create-new-article', [ArticleController::class, 'store']);
    Route::patch('update-article/{article}', [ArticleController::class, 'update']);
    Route::delete('delete-article/{article}', [ArticleController::class, 'destroy']);
    Route::get('profile/get-profile', [UserController::class, 'getUser']);
    Route::post('profile/update-profile', [UserController::class, 'updateUser']);
});


Route::get('article/{article}', [ArticleController::class, 'show']);

Route::get('article', [ArticleController::class, 'index']);

// Route::namespace('Auth')->group(function () {
//     Route::post('register', RegisterController::class);
// });

// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('register', RegisterController::class);
// });
