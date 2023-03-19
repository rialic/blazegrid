<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
class HomeController extends Controller
{
    public function index()
    {
        $user = optional(auth())->user();

        return view('pages.guest.home', ['user' => $user]);
    }
}
