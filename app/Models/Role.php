<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class Role extends Model
{
    use GenerateUuid;

    protected $table = 'tb_roles';
    protected $tableColumnPrefix = 'ro';
    protected $primaryKey = 'ro_uuid';
    public $timestamps = false;

    protected $appends = [
        'uuid',
        'name',
        'status',
    ];

    protected $fillable = [
        'ro_uuid',
        'ro_name',
        'ro_status'
    ];

    protected $hidden = [
        'ro_uuid',
        'ro_name',
        'ro_status',
    ];

    // GETTERS
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
        return $this->belongsToMany(User::class, 'tb_role_user', 'ro_uuid', 'us_uuid');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'tb_permission_role', 'ro_uuid', 'pe_uuid');
    }

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
