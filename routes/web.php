<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminDiscountCodeController;
use App\Http\Controllers\AdminSpecialistController;
use App\Http\Controllers\AdminSpecialistTimesController;
use App\Http\Controllers\AdminMeetController;
use App\Http\Controllers\Calendar;
use App\Http\Controllers\AdminMeetHistoryController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserMeetController;
use App\Http\Controllers\SpecialistTimeSheetController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::middleware(['auth'])->group(function () {

    Route::post('logout',[AuthController::class, 'logout'])->name('logout');

    Route::resource('calendars',Calendar::class);

    Route::resource('home', HomeController::class);

    Route::middleware('role:' . User::ROLE_USER . ',' . User::ROLE_SPECIALIST )->group(function () {
        Route::resource('user-meets', UserMeetController::class);
    });

    Route::middleware('role:' .  User::ROLE_SPECIALIST )->group(function () {
        Route::resource('specialist-specialiststimes', SpecialistTimeSheetController::class);
    });

    Route::middleware('role:' . User::ROLE_USER . ',' . User::ROLE_SPECIALIST . ',' . User::ROLE_MODERATOR )->group(function () {
        Route::resource('profile-user', UserProfileController::class);
    });

    Route::middleware('role:' . User::ROLE_MODERATOR . ',' . User::ROLE_ADMIN)->group(function () {
        Route::resource('services', AdminServiceController::class);
        Route::resource('discountcodes', AdminDiscountCodeController::class);
        Route::resource('specialists', AdminSpecialistController::class);
        Route::resource('specialiststimes', AdminSpecialistTimesController::class);
        Route::resource('meets',AdminMeetController::class);
        Route::resource('meethistories',AdminMeetHistoryController::class);
        Route::resource('zooms',ZoomController::class);
        Route::get('/search-users',[UserController::class,'searchByName']);
        Route::get('/search-services',[AdminServiceController::class,'searchByName']);
        Route::get('/search-services-specialist',[AdminServiceController::class,'searchBySpecialist']);
        Route::get('/search-specialists',[AdminSpecialistController::class,'searchByName']);
        Route::get('/search-discounts',[AdminDiscountCodeController::class,'searchByName']);
        Route::get('/meet-csv', [AdminMeetController::class,'GetMeetsData'])->name('meet.csv');
        Route::get('/download-bill/{id}',[AdminMeetHistoryController::class,'downloadBill'])->name('downloadBill');
        Route::post('/validate-payment/{id}', [AdminMeetController::class,'GetPaymentStatus'])->name('getPaymentStatus');
    });

    Route::middleware('role:' . User::ROLE_ADMIN)->group(function () {
        Route::resource('users', UserController::class);
    });


});

Route::get('validate-zoom-token', [ZoomController::class, 'callBackZoomUri']);
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('recover-password', [AuthController::class, 'showRecoverPasswordForm'])->name('recover-password');
Route::post('recover-password', [AuthController::class, 'recoverPassword']);

