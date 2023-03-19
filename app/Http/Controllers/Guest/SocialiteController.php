<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Repository\Interfaces\PlansInterface as PlansRepo;
use App\Repository\Interfaces\UserInterface as UserRepo;
use App\Repository\Interfaces\RoleInterface as RoleRepo;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Essa classe utiliza o service Socialite do Laravel framework
 * A estrutura padrão do Laravel Socialite devem ter os métodos redirectToProvider() e handleProviderCallback()
 * Para mais informações sobre o Laravel Socialite deve ser consultado o link a seguir
 * https://laravel.com/docs/8.x/socialite
 *
 * As variáveis GOOGLE_CLIENT_ID e GOOGLE_CLIENT_SECRET no arquivo .ENV devem ser preenchidas com o ID e o Secret do Google login, para mais informações acessar o link a seguir
 * https://console.cloud.google.com/
 */
class SocialiteController extends Controller
{
    private $userRepo;
    private $roleRepo;
    private $plansRepo;

    public function __construct(PlansRepo $plansRepo, UserRepo $userRepo, RoleRepo $roleRepo)
    {
        $this->plansRepo = $plansRepo;
        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }

    /**
     * Método que faz um request para o serviço do google e na qual o Google pede login e senha do google para o usuário visitante
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Recupera o retorno da resposta do Google em caso de sucesso ou falha no Login do Usuário
     */
    public function handleProviderCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('guest.login');
        }

        // Recuperar os dados do usuário logado no Google
        $providerUser = Socialite::driver('google')->user();

        // Seta parametros de filtro, Plano Basic, Papel Default Punter, Socialite ID do Google
        $planParams = ['filter:plan_name' => 'Basic'];
        $roleParams = ['filter:name' => 'DEFAULT_PUNTER'];
        $userParams = ['filter:socialite_id' => $providerUser->id];

        // Busca o usuário
        $user = $this->userRepo->getFirstData($userParams);

        $isUserActve = !!optional($user)->status;
        $isBasicPlan = lcfirst(optional(optional($user)->plan)->name) === 'basic';

        // Seta um password no sistema aleatório já que o usuário acessa apenas pelo login do google
        $password = ('xr2_' . substr($providerUser->email, '0', strpos($providerUser->email, '@')));

        // Verifica se o usuário não existe no sistema, e cadastra um novo com configurações básicas
        if (!$user->exists) {
            $plan = $this->plansRepo->getFirstData($planParams);
            $punterRole = $this->roleRepo->getFirstData($roleParams);

            $user->forceFill([
                'socialite_id' => $providerUser->id,
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'ip' => $request->ip(),
                'last_date_visit' => now(),
                'password' => $password,
                'terms_conditions' => true
            ]);

            $user->plan()->associate($plan->pl_id);
            $user->save();
            $user->roles()->attach($punterRole->role_id);

            // Faz login do usuário após o cadastro no banco de dados
            auth()->login($user);

            // Envia o usuário logado para a tela de históricos do crash
            return redirect()->route('priv.crash');
        }

        // Verifica se usuário não é ativo e devolve o mesmo para a tela de login
        if (!$isUserActve) {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login');
        }

        // Verifica se o usuário é do plano premium ou deluxe e verifica a validade do plano deste, caso o plano do usuário tenha expirado, esse volta a ser usuário do plano básico
        if (!$isBasicPlan) {
            $isDeluxePlan = lcfirst(optional(optional($user)->plan)->name) === 'deluxe';

            if (!$isDeluxePlan) {
                $hasPlanExpired = Carbon::parse($user->expiration_plan_date)->lte(now());

                if ($hasPlanExpired) {
                    $plan = $this->plansRepo->getFirstData($planParams);
                    $user->plan()->associate($plan->pl_id);
                    $user->expiration_plan_date = null;
                }
            }
        }

        // As verificações acima deram negativa, o usuário ainda é premium ou deluxe e então terá acesso a tudo
        $user->ip = $request->ip();
        $user->last_date_visit = now();
        $user->save();
        auth()->login($user);

        return redirect()->route('priv.crash');
    }
}
