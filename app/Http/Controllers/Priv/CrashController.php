<?php

namespace App\Http\Controllers\Priv;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CrashController extends Controller
{
    public function index()
    {
        return view('pages.priv.crash');
    }
}
