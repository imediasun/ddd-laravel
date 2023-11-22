<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompaniesController;
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


    Route::middleware(['auth:api','cors'])->group(function () {

    Route::get('/user', function (Request $request) {
            return $request->user();
    });

    Route::get('/google-addresses', [\App\Http\Controllers\GoogleAddressesController::class, 'get'])->name('addresses-google');
    Route::get('/google-addresses/{place_id}', [\App\Http\Controllers\GoogleAddressesController::class, 'find'])->name('addresses-google-find');


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.api');
    Route::get('/Companies', [CompaniesController::class, 'list']);
    Route::post('/Companies', [CompaniesController::class, 'create']);
    Route::patch('/Companies/{Company}', [CompaniesController::class, 'update']);
    Route::delete('/Companies/{Company}', [CompaniesController::class, 'delete']);
    Route::post('/Companies-import', [CompaniesController::class, 'postCompaniesData']);
});

    Route::middleware(['cors'])->group(function () {
    Route::get('/token', function () {
        return csrf_token();
    });
    Route::get('/Companies-list', [CompaniesController::class, 'CompaniesList']);
    Route::get('/Companies-by-country/{country}', [CompaniesController::class, 'getCompaniesByRelatedCountry']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


});
