<?php

namespace App\Repository\Interfaces;

interface PlansInterface
{
    public function findByName($name);
}