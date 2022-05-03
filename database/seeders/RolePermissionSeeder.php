<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*********************************************************
         * ROLE ADMIN
         *********************************************************/
        $rolePermission = $this->hasRolePermission('ADMIN', 'OBJ_ADMIN');
        $this->createRolePermission($rolePermission);

        /*********************************************************
         * ROLE PUNTER
         *********************************************************/
        $rolePermission = $this->hasRolePermission('DEFAULT_PUNTER', 'OBJ_PUNTER.VIEW.DEFAULT_GRID');
        $this->createRolePermission($rolePermission);

        $rolePermission = $this->hasRolePermission('ADVANCED_PUNTER', 'OBJ_PUNTER.VIEW.DEFAULT_GRID');
        $this->createRolePermission($rolePermission);

        $rolePermission = $this->hasRolePermission('ADVANCED_PUNTER', 'OBJ_PUNTER.VIEW.ADVANCED_GRID');
        $this->createRolePermission($rolePermission);

        $rolePermission = $this->hasRolePermission('PREMIUM_PUNTER', 'OBJ_PUNTER.VIEW.DEFAULT_GRID');
        $this->createRolePermission($rolePermission);

        $rolePermission = $this->hasRolePermission('PREMIUM_PUNTER', 'OBJ_PUNTER.VIEW.ADVANCED_GRID');
        $this->createRolePermission($rolePermission);

        $rolePermission = $this->hasRolePermission('PREMIUM_PUNTER', 'OBJ_PUNTER.VIEW.PREMIUM_GRID');
        $this->createRolePermission($rolePermission);
    }

    private function hasRolePermission($role, $permission)
    {
        $roleId = DB::table('tb_roles')->where('ro_name', $role)->value('ro_id');
        $permissionId = DB::table('tb_permissions')->where('pe_name', $permission)->value('pe_id');

        $roleQuery = DB::table('tb_roles')->select('ro_id')->where('ro_name', $role);
        $permissionQuery = DB::table('tb_permissions')->select('pe_id')->where('pe_name', $permission);

        $count = DB::table('tb_permission_role')
        ->joinSub($roleQuery, 'role', function ($join) {
            $join->on('tb_permission_role.ro_id', '=', 'role.ro_id');
        })
        ->joinSub($permissionQuery, 'permission', function ($join) {
            $join->on('tb_permission_role.pe_id', '=', 'permission.pe_id');
        })->count();
        return array('register' => ($count > 0), 'role' => $roleId, 'permission' => $permissionId);
    }

    private function createRolePermission($rolePermission)
    {
        if ($rolePermission['register'] == false) {
            DB::table('tb_permission_role')->insert(['ro_id' => $rolePermission['role'], 'pe_id' => $rolePermission['permission']]);
        }
    }
}
