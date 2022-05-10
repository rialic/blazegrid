<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Crash extends Model
{
    protected $table = 'tb_crash';
    protected $primaryKey = 'cr_id';

    protected $appends = [
        'point',
        'created_at_server',
    ];

    protected $guarded  = [
        'cr_id',
        'cr_created_at',
        'cr_update_at'
    ];

    protected $hidden = [
        'cr_id',
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
        $this->cr_created_at_server = Carbon::parse($value)->setTimezone('America/Sao_Paulo');
    }

    // GETTERS
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
    public function getPrimaryKeyAttribute()
    {
        return $this->primaryKey;
    }
}
