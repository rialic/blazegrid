<?php


namespace App\Repository\Base;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class DBRepositoryImpl implements DBRepositoryInterface
{
    protected $entity;

    public function __construct()
    {
        $this->entity = $this->getEntity();
    }

    public function getEntity()
    {
        if (!method_exists($this, 'entity')) {
            throw new NotEntityDefined();
        }
        return app($this->entity());
    }

    public function count()
    {
        return $this->entity::count();
    }

    public function findAll()
    {
        return $this->entity::all();
    }

    public function findByUuid($uuid)
    {
        return $this->entity::where($this->entity->getKeyName(), $uuid)->first();
    }

    public function findByUuidOrNew($uuid)
    {
        return $this->entity::firstOrNew([$this->entity->getKeyName() => $uuid]);
    }

    public function getFirstData($params = [])
    {
        return $this->query($params)->first() ?? $this->entity;
    }

    public function getData($params = [])
    {
        $isPaginable = Arr::get($params, 'page');
        $limit = Arr::get($params, 'limit') ?: -1;
        $entity = $this->query($params);

        if ($isPaginable) {
            return ($limit !== -1) ? $entity->paginate($limit) : $entity->paginate();
        }

        return ($limit !== -1) ? $entity->limit($limit)->get() : $entity->get();
    }

    public function query($params = [])
    {
        $entity = $this->entity;
        $entity = $this->filter($entity, $params);
        $entity = $this->sort($entity, $params);

        return $entity;
    }

    private function filter($entity, $params)
    {
        $params = collect($params);
        $isFilterable = $params->contains(fn ($_, $key) => Str::startsWith($key, 'filter:'));

        if ($isFilterable) {
            // Verifica se o parâmetro começa com "filter:"
            $filters = $params->flatMap(function ($value, $key) {
                $isStringFilter = Str::startsWith($key, 'filter:');

                if ($isStringFilter) {
                    $field = Str::replace('filter:', '', $key);

                    if ($value !== '') {
                        return array($field => $value);
                    }
                }
            });

            $entity = $filters->reduce(function ($accEntity, $value, $field) {
                $method = 'filterBy' . Str::studly($field);

                // Verifica por algum método filterBYMethod
                if (method_exists($this, $method)) {
                    $accEntity = $this->{$method}($accEntity, $value);

                    return $accEntity;
                }

                $accEntity = $accEntity->where("{$accEntity->tableColumnPrefix}_{$field}", 'LIKE', "%$value%");

                return $accEntity;
            }, $entity);

            return $entity;
        }

        return $entity;
    }

    private function sort($entity, $params)
    {
        $isSortable = Arr::get($params, 'orderBy');

        if ($isSortable) {
            $orderBy = Arr::get($params, 'orderBy');
            $direction = Arr::get($params, 'direction');
            $method = 'orderBy' . Str::studly($orderBy);

            // Check for a custom orderByField method
            if (method_exists($this, $method)) {
                $entity = $this->{$method}($entity, $direction);
            } else {
                $entity = $entity->orderBy($orderBy, $direction);
            }

            return $entity;
        }

        return $entity->orderBy('created_at', 'desc');
    }
}
