<?php

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
Route::group(['namespace' => 'Api'], function () {
    // Route::post('/login', [UserController::class, 'login']);
    Route::post('/login', 'UserController@login');

    //authentification middleware
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::any('/courseListe', 'CourseController@courseListe');
    });
});
// Route::post('auth/login', [UserController::class, 'loginUser']);