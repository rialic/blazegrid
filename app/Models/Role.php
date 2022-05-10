<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    protected $table = 'tb_roles';
    protected $primaryKey = 'ro_id';
    public $timestamps = false;

    protected $appends = [
        'role_id',
        'uuid',
        'name',
        'status',
    ];

    protected $guarded = ['*'];

    protected $hidden = [
        'ro_id',
        'ro_uuid',
        'ro_name',
        'ro_status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ro_uuid)) {
                $model->ro_uuid = Str::uuid();
            }
        });
    }

    // GETTERS
    public function getRoleIdAttribute()
    {
        return $this->ro_id;
    }

    public function getUuidAttribute()
    {
        return $this->ro_uuid;
    }

    public function getNameAttribute()
    {
        return $this->ro_name;
    }

    public function getStatusAttribute()
    {
        return $this->ro_status;
    }

    // RELATIONSHIPS
    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_role_user', 'ro_id', 'us_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'tb_permission_role', 'ro_id', 'pe_id');
    }

    // TRANSIENTS METHODS
    public function getPrimaryKeyAttribute()
    {
        return $this->primaryKey;
    }
}
