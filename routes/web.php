<?php

use Illuminate\Support\Facades\Route;

/*********************************************************
* GUEST CONTROLLERS
*********************************************************/
use App\Http\Controllers\Guest\HomeController;
use App\Http\Controllers\Guest\LoginController;
use App\Http\Controllers\Guest\SocialiteController;

/*********************************************************
* PRIVATE CONTROLLERS
*********************************************************/
use App\Http\Controllers\Priv\CrashController;

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

Route::group(['as' => 'public'], function () {

    // Home Controller
    Route::get('/', [HomeController::class, 'index'])->name('.init');

    // Login Controller
    Route::get('/login', [LoginController::class, 'index'])->name('.login');

    // Socialite Controller
    Route::get('/social-login', [SocialiteController::class, 'redirectToProvider'])->name('.google.login');
    Route::get('/auth/callback', [SocialiteController::class, 'handleProviderCallback']);
});

// Login Controller
Route::get('/crash', [CrashController::class, 'index']);

