<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\GenerateUuid;

class RolePermission extends Model
{
    use GenerateUuid;

    protected $table = 'tb_role_permission';
    protected $tableColumnPrefix = 'rp';
    protected $primaryKey = 'rp_uuid';
    public $timestamps = false;

    protected $fillable = [
        'rp_uuid',
        'ro_uuid',
        'pe_uuid'
    ];

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
