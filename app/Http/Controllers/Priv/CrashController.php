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

    public function defaultHistory()
    {
        $user = optional(auth())->user();
        $params = ['limit' => (lcfirst($user->plan->name) === 'basic') ? 60 : 100];

        if (empty($user) || !optional($user)->exists) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        $crashDefaultHistory = $this->crashRepo->getData($params);

        return response()->json(['data' => ['default_history' => $crashDefaultHistory]]);
    }

    public function advancedHistory(Request $request, $limit)
    {
        $user = optional(auth())->user();
        $isBasicPlan = lcfirst($user->plan->name) === 'basic';

        if ((empty($user) || !optional($user)->exists) || $isBasicPlan) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        $params = ['limit' => $limit];
        $crashAdvancedHistory = $this->crashRepo->getData($params);
        return response()->json(['data' => ['advanced_history' => $crashAdvancedHistory, 'user' => $user]]);
    }
}
