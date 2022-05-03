<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Permission extends Model
{
    protected $table = 'tb_permissions';
    protected $primaryKey = 'pe_id';
    public $timestamps = false;

    protected $appends = [
        'uuid',
        'name',
        'status',
    ];

    protected $guarded = ['*'];

    protected $hidden = [
        'pe_id',
        'pe_uuid',
        'pe_name',
        'pe_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->pe_uuid)) {
                $model->pe_uuid = Str::uuid();
            }
        });
    }

    // GETTERS
    public function getUuidAttribute()
    {
        return $this->pe_uuid;
    }

    public function getNameAttribute()
    {
        return $this->pe_name;
    }

    public function getStatusAttribute()
    {
        return $this->pe_status;
    }

    // RELATIONSHIPS
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tb_permission_role', 'pe_id', 'ro_id');
    }

    // TRANSIENTS METHODS
    public function getPrimaryKeyAttribute()
    {
        return $this->primaryKey;
    }
}
