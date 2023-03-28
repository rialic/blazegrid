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
        Permission::firstOrCreate(['pe_name' => 'OBJ_ADMIN']);

        /*********************************************************
        * PUNTER PERMISSION
        *********************************************************/
        Permission::firstOrCreate(['pe_name' => 'OBJ_PUNTER.VIEW.DEFAULT_GRID']);

        Permission::firstOrCreate(['pe_name' => 'OBJ_PUNTER.VIEW.ADVANCED_GRID']);

        Permission::firstOrCreate(['pe_name' => 'OBJ_PUNTER.VIEW.PREMIUM_GRID']);
    }
}
