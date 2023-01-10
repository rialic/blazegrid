<?php

namespace App\Listeners;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $password = ('xr2_' . substr($user->email, '0', strpos($user->email, '@')));

        auth()->logoutOtherDevices($password, 'us_password');
    }
}
