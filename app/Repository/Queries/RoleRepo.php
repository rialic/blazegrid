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
}