<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class Permission extends Model
{
    use GenerateUuid;

    protected $table = 'tb_permissions';
    protected $tableColumnPrefix = 'pe';
    protected $primaryKey = 'pe_uuid';
    public $timestamps = false;

    protected $appends = [
        'uuid',
        'name',
        'status',
    ];

    protected $fillable = [
        'pe_uuid',
        'pe_name',
        'pe_status'
    ];

    protected $hidden = [
        'pe_uuid',
        'pe_name',
        'pe_status'
    ];

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
        return $this->belongsToMany(Role::class, 'tb_permission_role', 'pe_uuid', 'ro_uuid');
    }

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
