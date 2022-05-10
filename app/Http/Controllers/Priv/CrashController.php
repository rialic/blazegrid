<?php

namespace App\Http\Controllers\Priv;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repository\Interfaces\CrashInterface as CrashRepo;

class CrashController extends Controller
{
    private $crashRepo;

    public function __construct(CrashRepo $crashRepo)
    {
        $this->crashRepo = $crashRepo;
    }

    public function index()
    {
        $params = ['limit' => 60];
        $crashDefaultHistory = $this->crashRepo->getData($params);

        return view('pages.priv.crash', ['crashDefaultHistory' => $crashDefaultHistory]);
    }

    public function defaultHistory()
    {
        $params = ['limit' => 60];
        $user = optional(auth())->user();

        if (empty($user) || !optional($user)->exists) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        $crashDefaultHistory = $this->crashRepo->getData($params)->all();

        return response()->json(['data' => $crashDefaultHistory]);
    }
}
