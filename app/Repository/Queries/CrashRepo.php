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
}
