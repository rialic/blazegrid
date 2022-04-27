<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crash;
use Illuminate\Support\Str;
use App\Proxy\Blaze\BlazeHeaders;

class BlazeProxyController extends Controller
{
    public function index()
    {
        // $blazeProxy = app('App\Proxy\Blaze\BlazeProxy');
        // $blazeProxy->fetch();
        // $crash = new Crash();

        // $crash->create(['cr_point' => 2.48, 'cr_id_server' => Str::uuid(), 'cr_create_at_serve' => now()]);
    }
}
