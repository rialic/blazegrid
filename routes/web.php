<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlazeProxyController;

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

Route::group(['namespace' => 'Pub', 'as' => 'public'], function () {

    // Home Controller
    Route::get('/', [HomeController::class, 'index'])->name('.init');


    Route::get('/blaze-proxy', [BlazeProxyController::class, 'index']);
});

