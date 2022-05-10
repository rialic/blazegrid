<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.guest.login');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();

        return redirect()->route('guest.init');
    }
}
