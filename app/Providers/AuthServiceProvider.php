<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // CHECK IF USER IS ADMIN, IF SO, THEN RETURN TRUE TO RELEASE ALL ACCESS IN SYSTEM
        Gate::before(function ($user, $abiliity) {
            // CHECK IF ABILITY IS A POLICY
            $isPolicy = Str::startsWith($abiliity, 'policy');

            if (!$isPolicy) {
                if ($user->hasAnyRoles('ADMIN')) {
                    return true;
                }
            }
        });

        // DEFINE PERMISSION USER
        $permissionList = Permission::with('roles')->get();

        foreach ($permissionList as $permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasAnyRoles($permission->roles);
            });
        }
    }
}
