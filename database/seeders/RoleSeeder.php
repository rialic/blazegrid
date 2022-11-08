<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*********************************************************
        * ADMIN ROLE
        *********************************************************/
        $role[Str::random(5)] = Role::firstOrCreate([
            'ro_name' => 'ADMIN',
        ]);

        /*********************************************************
        * PUNTER ROLE
        *********************************************************/
        $role[Str::random(5)] = Role::firstOrCreate([
            'ro_name' => 'DEFAULT_PUNTER',
        ]);

        $role[Str::random(5)] = Role::firstOrCreate([
            'ro_uuid' => (string) Str::uuid()->toString(),
            'ro_name' => 'ADVANCED_PUNTER',
        ]);

        $role[Str::random(5)] = Role::firstOrCreate([
            'ro_name' => 'PREMIUM_PUNTER',
        ]);
    }
}
