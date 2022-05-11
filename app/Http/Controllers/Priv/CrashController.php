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

    public function index(Request $request)
    {
        // $params = ['limit' => 60];
        // $crashDefaultHistory = $this->crashRepo->getData($params);

        // $params = ['limit' => 200];
        // $crashAdvancedHistory = $this->crashRepo->getData($params);

        return view('pages.priv.crash');
    }

    public function defaultHistory()
    {
        $user = optional(auth())->user();
        $params = ['limit' => ($user->plan === 'basic') ? 60 : 100];

        if (empty($user) || !optional($user)->exists) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        $crashDefaultHistory = $this->crashRepo->getData($params)->all();

        return response()->json(['data' => ['default_history' => $crashDefaultHistory, 'user' => $user]]);
    }

    public function advancedHistory(Request $request, $limit)
    {
        $user = optional(auth())->user();

        if (empty($user) || !optional($user)->exists) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        if($user->plan === 'premium') {
            $params = ['limit' => $limit];
            $crashAdvancedHistory = $this->crashRepo->getData($params)->all();
            return response()->json(['data' => ['advanced_history' => $crashAdvancedHistory, 'user' => $user]]);
        }
    }

    // private function getLocalTime(){

    //     $ip = file_get_contents("http://ipecho.net/plain");
    //     $url = 'http://ip-api.com/json/'.$ip;
    //     $tz = file_get_contents($url);
    //     $tz = json_decode($tz, true)['timezone'];

    //     return $tz;
    // }
}
