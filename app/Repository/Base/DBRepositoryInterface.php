<?php

namespace App\Repository\Base;

interface DBRepositoryInterface
{
    public function count();
    public function findAll();
    public function findByUuid($id);
    public function findByUuidOrNew($id);
    public function getFirstData($params);
    public function getData($params);
    public function query($params);
}