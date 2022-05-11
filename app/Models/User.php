<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'us_id';

    protected $appends = [
        'uuid',
        'name',
        'cpf',
        'email',
        'phone',
        'whatsapp',
        'plan',
        'status',
    ];

    protected $fillable = [
        'us_socialite_id',
        'us_name',
        'us_cpf',
        'us_email',
        'us_phone',
        'us_whatsapp',
        'us_status',
        'us_password',
        'us_terms_conditions'
    ];

    protected $hidden = [
        'us_id',
        'us_uuid',
        'us_socialite_id',
        'us_name',
        'us_cpf',
        'us_email',
        'us_phone',
        'us_whatsapp',
        'us_status',
        'email_verified_at',
        'us_password',
        'us_terms_conditions',
        'us_last_date_visit',
        'us_expiration_plan_date',
        'us_ip',
        'us_inactivation_date',
        'pl_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->us_uuid)) {
                $model->us_uuid = Str::uuid();
            }
        });
    }

    // SETTERS
    public function setSocialiteIdAttribute($value)
    {
        $this->us_socialite_id = $value;
    }

    public function setNameAttribute($value)
    {
        $this->us_name = $value;
    }

    public function setCpfAttribute($value)
    {
        $this->us_cpf = $value;
    }

    public function setEmailAttribute($value)
    {
        $this->us_email = $value;
    }

    public function setPhoneAttribute($value)
    {
        $this->us_phone = $value;
    }

    public function setWhatsappAttribute($value)
    {
        $this->us_whatsapp = $value;
    }

    public function setStatusAttribute($value)
    {
        $this->us_status = $value;
    }

    public function setPasswordAttribute($value)
    {
        $this->us_password = Hash::make($value);
    }

    public function setTermsConditionsAttribute($value)
    {
        $this->us_terms_conditions = $value;
    }

    // GETTERS
    public function getUuidAttribute()
    {
        return $this->us_uuid;
    }

    public function getSocialiteIdAttribute()
    {
        return $this->us_socialite_id;
    }

    public function getNameAttribute()
    {
        return $this->us_name;
    }

    public function getCpfAttribute()
    {
        return $this->us_cpf;
    }

    public function getEmailAttribute()
    {
        return $this->us_email;
    }

    public function getPhoneAttribute()
    {
        return $this->us_phone;
    }

    public function getWhatsappAttribute()
    {
        return $this->us_whatsapp;
    }

    public function getPlanAttribute()
    {
        return lcfirst($this->plan()->first()->name);
    }

    public function getStatusAttribute()
    {
        return $this->us_status;
    }

    // RELATIONSHIPS
    public function plan()
    {
        return $this->belongsTo(Plans::class, 'pl_id', 'pl_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'tb_role_user', 'us_id', 'ro_id');
    }

    // TRANSIENTS METHODS
    public function isAdmin()
    {
        return $this->hasAnyRoles('ADMIN');
    }


    public function hasPermission(Permission $permission)
    {
        return $this->hasAnyRoles($permission->roles);
    }

    public function hasAnyRoles($roles)
    {
        if (is_array($roles) || is_object($roles)) {
            return !! $roles->intersect($this->roles)->count();
        }

        return $this->roles->contains('name', $roles);
    }

    public function getPrimaryKeyAttribute()
    {
        return $this->primaryKey;
    }
}
