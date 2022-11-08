<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Repository\Interfaces\PlansInterface as PlansRepo;
use App\Repository\Interfaces\UserInterface as UserRepo;
use App\Repository\Interfaces\RoleInterface as RoleRepo;
use Carbon\Carbon;

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

    public function redirectToProvider()
    {
        // $func = new \ReflectionMethod(User::class, 'getNameAttribute');
        // $filename = $func->getFileName();
        // $start_line = $func->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
        // $end_line = $func->getEndLine();
        // $length = $end_line - $start_line;

        // $source = file($filename);
        // $body = implode("", array_slice($source, $start_line, $length));

        // dd(str_replace(array("\r","\n"), "", trim($body)));


        // dd('Here', (new \App\Models\Role)::where('ro_name', 'ADMIN')->get());

        // dd((new \App\Models\Role)->where('name', 'PREMIUM_PUNTER')->first());
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('guest.login');
        }

        $providerUser = Socialite::driver('google')->user();

        $planParams = ['filter:plan_name' => 'Basic'];
        $roleParams = ['filter:name' => 'DEFAULT_PUNTER'];
        $userParams = ['filter:socialite_id' => $providerUser->id];

        $user = $this->userRepo->getFirstData($userParams);

        $isUserActve = !!optional($user)->status;
        $isBasicPlan = lcfirst(optional(optional($user)->plan)->name) === 'basic';

        $password = ('xr2_' . substr($providerUser->email, '0', strpos($providerUser->email, '@')));

        if (!$user->exists) {
            $plan = $this->plansRepo->getFirstData($planParams);
            $defaultPunterRole = $this->roleRepo->getFirstData($roleParams);

            $user->forceFill([
                'socialite_id' => $providerUser->id,
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'ip' => $request->ip(),
                'last_date_visit' => now(),
                'password' => $password,
                'terms_conditions' => true
            ]);
            $user->plan()->associate($plan->pl_uuid);
            $user->save();
            $user->roles()->attach($defaultPunterRole->role_uuid);

            auth()->login($user);

            return redirect()->route('priv.crash');
        }

        if (!$isUserActve) {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('login');
        }

        if (!$isBasicPlan) {
            $hasPlanExpired = Carbon::parse($user->expiration_plan_date)->lte(now());

            if ($hasPlanExpired) {
                $plan = $this->plansRepo->getFirstData($planParams);
                $user->plan()->associate($plan->pl_uuid);
                $user->expiration_plan_date = null;
            }
        }

        $user->ip = $request->ip();
        $user->last_date_visit = now();
        $user->save();
        auth()->login($user);

        return redirect()->route('priv.crash');
    }
}
