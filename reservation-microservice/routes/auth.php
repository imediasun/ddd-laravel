<?php


use Illuminate\Support\Facades\Route;

    Route::group(['prefix'=>'auth','middleware' => 'cors'], function () {
        Route::post('token', 'App\Http\Controllers\AuthController@authenticate');
        Route::post('refresh', 'App\Http\Controllers\AuthController@refreshToken');

        Route::post('password/forgot-password', 'App\Http\Controllers\AuthController@forgotPassword');
        Route::post('password/reset', 'App\Http\Controllers\AuthController@passwordReset')->name('password.reset');
    });

