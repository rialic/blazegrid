<?php

namespace App\Http\Controllers\Priv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $user = optional(auth())->user();

        return view('pages.priv.crash', ['user' => $user]);
    }

    public function defaultHistory(Request $request)
    {
        $user = auth()->user();
        $params = ['limit' => (lcfirst(optional(optional($user)->plan)->name) === 'basic') ? 60 : 100];
        $isAjaxRequest = $request->ajax();
        $isUserAthenticated = auth()->check();

        if (!$isAjaxRequest) {
            abort(403);
        }

        if (!$isUserAthenticated) {
            return response()->json(['error' => ['message' => 'Unauthorized.']], 401);
        }

        $crashDefaultHistory = $this->crashRepo->getData($params);

        return response()->json(['data' => ['default_history' => $crashDefaultHistory]]);
    }

    public function advancedHistory(Request $request)
    {
        $params = $request->only(['limit', 'page']);
        $user = auth()->user();
        $isBasicPlan = lcfirst(optional(optional($user)->plan)->name) === 'basic';
        $isAjaxRequest = $request->ajax();
        $isUserAthenticated = auth()->check();

        if (!$isAjaxRequest) {
            abort(403);
        }

        if (!$isUserAthenticated) {
            return response()->json(['error' => ['message' => 'Unauthorized.']], 401);
        }

        if ($isBasicPlan) {
            return response()->json(['error' => ['message' => 'Forbidden.']], 403);
        }

        $crashAdvancedHistory = $this->crashRepo->getData($params);
        return response()->json(['data' => ['advanced_history' => $crashAdvancedHistory, 'user' => $user]]);
    }
}
