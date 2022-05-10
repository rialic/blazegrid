<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Repository\Interfaces\PlansInterface as PlansRepo;
use App\Repository\Interfaces\UserInterface as UserRepo;
use App\Repository\Interfaces\RoleInterface as RoleRepo;

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
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('guest.login');
        }

        $providerUser = Socialite::driver('google')->user();
        $user = $this->userRepo->findFirstOrNew(['us_socialite_id' => $providerUser->id]);
        $password = ('xr2_' . substr($providerUser->email, '0', strpos($providerUser->email, '@')));

        if (!$user->exists) {
            $plan = $this->plansRepo->findByName('Básico');
            $defaultPunterRole = $this->roleRepo->findByName('DEFAULT_PUNTER');

            $user->forceFill([
                'socialite_id' => $providerUser->id,
                'name' => $providerUser->name,
                'email' => $providerUser->email,
                'password' => $password,
                'terms_conditions' => true
            ]);
            $user->plan()->associate($plan->plan_id);
            $user->save();
            $user->roles()->attach($defaultPunterRole->role_id);
        }

        auth()->login($user);

        return redirect()->route('priv.crash');
    }
}
