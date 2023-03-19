<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plans;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $planPremium = Plans::where('pl_plan_name', 'Deluxe')->first();
        $roleAdmin = Role::where('ro_name', 'ADMIN')->first();
        $rolePremium = Role::where('ro_name', 'PREMIUM_PUNTER')->first();

        $user = User::firstOrCreate(['us_socialite_id' => '103043060823393028641'],
            [
                'us_name' => 'Rhiali Cândido',
                'us_email' => 'rhiali.candido@gmail.com',
                'us_password' => Hash::make('xr2_' . substr('rhiali.candido@gmail.com', '0', strpos('rhiali.candido@gmail.com', '@'))),
                'us_terms_conditions' => true,
                'pl_id' => $planPremium->pl_id
            ]);

        $user->roles()->attach([$roleAdmin->ro_id, $rolePremium->ro_id]);
    }
}
