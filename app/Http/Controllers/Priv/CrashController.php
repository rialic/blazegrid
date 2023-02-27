<?php

namespace App\Http\Controllers\Priv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repository\Interfaces\CrashInterface as CrashRepo;

/**
 * Controller que apresenta as informações de histórico básico e do histórico avançado do crash
 */
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

    /**
     * Retorna informações do histórico básico do crash
     * O limite máximo é de 60 registros para o plano básico e 100 para o plano avançado
     */
    public function defaultHistory(Request $request)
    {
        $user = auth()->user();
        $params = ['limit' => (lcfirst(optional(optional($user)->plan)->name) === 'basic') ? 60 : 100];
        $params = array_merge($params, ['orderBy' => 'cr_created_at_server', 'direction' => 'desc']);
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

    /**
     * Retorna informações do histórico avançado do crash
     * Usuário de plano básico não tem acesso ao histórico avançado
     * O limite máximo que é recebido no request é de 3500 registros
     */
    public function advancedHistory(Request $request)
    {
        $params = $request->only(['limit', 'page']);
        $params = array_merge($params, ['orderBy' => 'cr_created_at_server', 'direction' => 'desc']);
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
