<?php

namespace App\Http\Controllers\Priv;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Função utilizada para recuperar o objeto usuário
     */
    public function user()
    {
        $user = optional(auth())->user()->load('plan');

        if (empty($user) || !optional($user)->exists) {
            return response()->json(['error' => ['message' => 'Unauthenticated.']], 401);
        }

        return response()->json(['data' => ['user' => $user]]);
    }
}
