<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
class HomeController extends Controller
{
    public function index()
    {
        $user = optional(auth())->user();

        return view('pages.guest.home', ['user' => $user]);
    }

    public function teste()
    {
        $blazeProxy = app('App\Proxy\Blaze\BlazeProxy');
        $blazeProxy->fetch();
    }
}
