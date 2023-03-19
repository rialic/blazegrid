<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class Crash extends Model
{
    use GenerateUuid;

    protected $table = 'tb_crash';
    protected $tableColumnPrefix = 'cr';
    protected $primaryKey = 'cr_id';

    protected $appends = [
        'uuid',
        'id_server',
        'point',
        'created_at_server',
    ];

    protected $fillable  = [
        'cr_point',
        'cr_id_server',
        'cr_created_at_server',
    ];

    protected $hidden = [
        'cr_id',
        'cr_uuid',
        'cr_id_server',
        'cr_point',
        'cr_created_at_server',
        'created_at',
        'updated_at'
    ];

    // SETTERS
    public function setPointAttribute($value)
    {
        $this->cr_point = $value;
    }

    public function setIdServerAttribute($value)
    {
        $this->cr_id_server = $value;
    }

    public function setCreatedAtServerAttribute($value)
    {
        $this->cr_created_at_server = Carbon::parse($value);
    }

    // GETTERS
    public function getUuidAttribute()
    {
        return $this->cr_uuid;
    }

    public function getPointAttribute()
    {
        return $this->cr_point;
    }

    public function getIdServerAttribute()
    {
        return $this->cr_id_server;
    }

    public function getCreatedAtServerAttribute()
    {
        return $this->cr_created_at_server;
    }

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
