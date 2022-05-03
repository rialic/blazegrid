<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function redirectToProvider(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('public.login');
        }

        $providerUser = Socialite::driver('google')->user();
        $user = $this->user::updateOrCreate(
            [
                'us_socialite_id' => $providerUser->id
            ],
            [
                'us_name' => $providerUser->name,
                'us_email' => $providerUser->email,
                'us_password' => Str::random(8),
                'us_terms_conditions' => true
            ]);

        dd($user);
    }
}
