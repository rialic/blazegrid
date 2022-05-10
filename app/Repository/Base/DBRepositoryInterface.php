<?php

namespace App\Repository\Base;

interface DBRepositoryInterface
{
    public function findAll();
    public function findById($id);
    public function findByIdOrNew($id, $array = []);
    public function findFirstOrNew($search, $array = null);
    public function findFirstOrCreate($search, $array = null);
    public function query($params);
    public function getData($params);
}