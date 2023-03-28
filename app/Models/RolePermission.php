<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'tb_role_permission';
    protected $tableColumnPrefix = 'rp';
    protected $primaryKey = 'rp_id';

    protected $fillable = [
        'ro_id',
        'pe_id'
    ];

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
