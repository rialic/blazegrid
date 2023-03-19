<?php

use Illuminate\Support\Facades\Route;

/*********************************************************
 * GUEST CONTROLLERS
 *********************************************************/

use App\Http\Controllers\Guest\{HomeController, LoginController, SocialiteController, PrivacyPolicyController};

/*********************************************************
 * PRIVATE CONTROLLERS
 *********************************************************/

use App\Http\Controllers\Priv\{UserController, CrashController, WSController};

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

// Login Controller
Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');

Route::group(['as' => 'guest.'], function () {
    // Home Controller
    Route::get('/', [HomeController::class, 'index'])->name('init');
    Route::get('/politica-privacidade', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');

    Route::group(['middleware' => 'guest'], function () {

        // Socialite Controller
        Route::get('/social-login', [SocialiteController::class, 'redirectToProvider'])->name('socialite.login');
        Route::get('/auth/callback', [SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');
    });
});

Route::group(['middleware' => ['auth'], 'as' => 'priv.'], function () {
    // User Controller
    Route::get('/user', [UserController::class, 'user'])->name('user');

    // Crash Controller
    Route::get('/crash', [CrashController::class, 'index'])->name('crash');
    Route::get('/crash/default-history', [CrashController::class, 'defaultHistory'])->name('crash.default-history');
    Route::get('/crash/advanced-history', [CrashController::class, 'advancedHistory'])->name('crash.advanced-history');

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});