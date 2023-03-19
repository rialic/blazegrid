<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        return view('pages.guest.privacy-policy');
    }
}
