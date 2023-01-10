<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class Plans extends Model
{
    use GenerateUuid;

    protected $table = 'tb_plans';
    protected $tableColumnPrefix = 'pl';
    protected $primaryKey = 'pl_uuid';
    public $timestamps = false;

    protected $appends = [
        'uuid',
        'name',
    ];

    protected $fillable  = [
        'pl_uuid',
        'pl_plan_name',
        'pl_status'
    ];

    protected $hidden = [
        'pl_uuid',
        'pl_plan_name',
        'pl_status'
    ];

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
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
