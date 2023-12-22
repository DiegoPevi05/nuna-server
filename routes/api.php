<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiWebController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/user-profile', [ApiAuthController::class, 'userProfile']);
});

Route::group([
    'prefix' => 'web'
], function ($router) {
    Route::get('/timesheets', [ApiWebController::class, 'getTimeSheets']);
    Route::post('/join-us', [ApiWebController::class, 'joinUsForm']);
    Route::post('/discount-code',[ApiWebController::class, 'checkDiscountCode']);
    Route::post('/make-order', [ApiWebController::class, 'makeOrder']);
    Route::get('/confirmationpayment', [ApiWebController::class, 'callBackPayment']);
    Route::get('/sessions', [ApiWebController::class, 'getSessions']);
});



