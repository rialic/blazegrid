<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plans extends Model
{
    protected $table = 'tb_plans';
    protected $primaryKey = 'pl_id';
    public $timestamps = false;

    protected $appends = [
        'uuid',
        'name',
        'status',
    ];

    protected $guarded  = [
        'pl_id',
        'pl_uuid',
        'pl_plan_name',
        'pl_status'
    ];

    protected $hidden = [
        'pl_id',
        'pl_uuid',
        'pl_plan_name',
        'pl_status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->pl_uuid)) {
                $model->pl_uuid = Str::uuid();
            }
        });
    }

    // GETTER
    public function getUuidAttribute()
    {
        return $this->pl_uuid;
    }

    public function getNameAttribute()
    {
        return $this->pl_plan_name;
    }

    public function getStatusAttribute()
    {
        return $this->pl_status;
    }

    // TRANSIENTS METHODS
    public function getPrimaryKeyAttribute()
    {
        return $this->primaryKey;
    }
}
