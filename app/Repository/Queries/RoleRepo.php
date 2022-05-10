<?php

namespace App\Repository\Queries;

use App\Models\Role;
use App\Repository\Base\DBRepositoryImpl;
use App\Repository\Interfaces\RoleInterface;

class RoleRepo extends DBRepositoryImpl implements RoleInterface
{
    public function entity()
    {
        return Role::class;
    }

    public function findByName($name)
    {
        $args = func_get_arg(0);

        if (is_array($args)) {
            $nameList = $args;

            return $this->entity::whereIn('ro_name', $nameList)->get();
        }

        if (is_string($args)) {
            $name = $args;

            return $this->entity::where('ro_name', $name)->first();
        }

        return null;
    }
}