<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'us_id';

    protected $fillable = [
        'us_name',
        'us_cpf',
        'us_email',
        'us_phone',
        'us_whatsapp',
        'us_password',
    ];

    protected $hidden = [
        'us_id',
        'us_uuid',
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
        'us_ip',
        'us_inactivation_date',
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

    public function setPasswordAttribute($value)
    {
        $this->us_password = Hash::make($value);
    }

    // GETTERS
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

    public function getPasswordAttribute()
    {
        return $this->us_password;
    }
}
