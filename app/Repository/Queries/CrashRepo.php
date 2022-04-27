<?php

namespace App\Repository\Queries;

use App\Models\Crash;
use App\Repository\Base\DBRepositoryImpl;
use App\Repository\Interfaces\CrashInterface;

class CrashRepo extends DBRepositoryImpl implements CrashInterface
{
    public function entity()
    {
        return Crash::class;
    }

    public function getAllLimitedBy($limit)
    {
        return $this->entity::orderBy('cr_created_at_server', 'desc')->limit($limit)->get();
    }
}
