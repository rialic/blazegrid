<?php


namespace App\Repository\Base;

class DBRepositoryImpl implements DBRepositoryInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->getEntity();
    }

    public function findAll()
    {
        return $this->entity::all();
    }

    public function findById($id)
    {
        return $this->entity::where($this->entity->primaryKey, $id)->first();
    }

    public function findByIdOrNew($id, $array = [])
    {
        return $this->entity::firstOrNew([$this->entity->primaryKey => $id], $array);
    }

    public function findFirstOrNew($search, $array = [])
    {
        return $this->entity::firstOrNew($search, $array);
    }

    public function findFirstOrCreate($search, $array = [])
    {
        return $this->entity::firstOrCreate($search, $array);
    }

    public function findFirstOrFail($field)
    {
        return $this->entity::firstOrFail($field)->firstOrFail();
    }

    public function getEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new NotEntityDefined();
        }
        return app($this->entity());
    }
}