<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;

    protected $table = 'tb_role_user';
    protected $tableColumnPrefix = 'ru';
    protected $primaryKey = 'ru_id';

    protected $fillable = [
        'ro_id',
        'us_id'
    ];

    // TRANSIENTS METHODS
    public function getTableColumnPrefixAttribute()
    {
        return $this->tableColumnPrefix;
    }
}
