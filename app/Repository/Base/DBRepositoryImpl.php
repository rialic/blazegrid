<?php


namespace App\Repository\Base;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use stdClass;

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

    public function getData($params = [])
    {
        $isPaginable = Arr::get($params, 'page');
        $limit = Arr::get($params, 'limit') ?: -1;
        $query = $this->query($params);

        if ($isPaginable) {
            return ($limit !== -1) ? $query->paginate($limit) : $query->paginate();
        }

        return ($limit !== -1) ? $query->limit($limit)->get() : $query->get();
    }

    public function query($params = [])
    {
        $query = $this->entity;
        $query = $this->filter($query, $params);
        $query = $this->sort($query, $params);

        return $query;
    }

    private function filter($query, $params)
    {
        $params = collect($params);
        $isFilterable = $params->contains(fn ($value, $key) => Str::startsWith($key, 'filter:'));

        if ($isFilterable) {
            // Collect all parameters that begin with "filter:"
            $filters = $params->flatMap(function ($value, $key) {
                $isStringFilter = Str::startsWith($key, 'filter:');

                if ($isStringFilter) {
                    $field = Str::replace('filter:', '', $key);

                    if ($value !== '') {
                        return array($field => $value);
                    }
                }
            });

            $query = $filters->reduce(function ($accQuery, $value, $field) use ($query) {
                $accQuery = $accQuery ?? $query;
                $method = 'filterBy' . Str::studly($field);

                // Check for a custom filterByField method
                if (method_exists($this, $method)) {
                    $accQuery = $this->{$method}($accQuery, $value);

                    return $accQuery;
                }

                $accQuery = $accQuery->where($field, 'LIKE', "%$value%");

                return $accQuery;
            }, null);

            return $query;
        }

        return $query;
    }

    private function sort($query, $params)
    {
        $isSortable = Arr::get($params, 'orderBy');

        if ($isSortable) {
            $orderBy = Arr::get($params, 'orderBy');
            $direction = Arr::get($params, 'direction');
            $method = 'orderBy' . Str::studly($orderBy);

            // Check for a custom orderByField method
            if (method_exists($this, $method)) {
                $query = $this->{$method}($query, $direction);
            } else {
                $query = $query->orderBy($orderBy, $direction);
            }

            return $query;
        }

        return $query->orderBy('created_at', 'desc');
    }
}
