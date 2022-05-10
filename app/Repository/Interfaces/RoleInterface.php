<?php

namespace App\Repository\Interfaces;

interface RoleInterface
{
    public function findByName($name);
}