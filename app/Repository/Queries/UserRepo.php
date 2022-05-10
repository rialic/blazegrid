<?php

namespace App\Repository\Queries;

use App\Models\User;
use App\Repository\Base\DBRepositoryImpl;
use App\Repository\Interfaces\UserInterface;

class UserRepo extends DBRepositoryImpl implements UserInterface
{
    public function entity()
    {
        return User::class;
    }
}