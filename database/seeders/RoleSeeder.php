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
        Role::firstOrCreate(['ro_name' => 'ADMIN']);

        /*********************************************************
        * PUNTER ROLE
        *********************************************************/
        Role::firstOrCreate(['ro_name' => 'DEFAULT_PUNTER']);

        Role::firstOrCreate(['ro_name' => 'ADVANCED_PUNTER']);

        Role::firstOrCreate(['ro_name' => 'PREMIUM_PUNTER']);
    }
}
