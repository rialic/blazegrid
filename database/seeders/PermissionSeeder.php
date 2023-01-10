<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*********************************************************
        * ADMIN PERMISSION
        *********************************************************/
        $permission[Str::random(5)] = Permission::firstOrCreate([
            'pe_name' => 'OBJ_ADMIN',
        ]);

        /*********************************************************
        * PUNTER PERMISSION
        *********************************************************/
        $permission[Str::random(5)] = Permission::firstOrCreate([
            'pe_name' => 'OBJ_PUNTER.VIEW.DEFAULT_GRID',
        ]);

        $permission[Str::random(5)] = Permission::firstOrCreate([
            'pe_name' => 'OBJ_PUNTER.VIEW.ADVANCED_GRID',
        ]);

        $permission[Str::random(5)] = Permission::firstOrCreate([
            'pe_name' => 'OBJ_PUNTER.VIEW.PREMIUM_GRID',
        ]);
    }
}
