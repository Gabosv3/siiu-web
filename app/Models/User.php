<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'departamento_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación uno a uno con la clase InformacionPersonal.
     */
    public function informacionPersonal()
    {
        return $this->hasOne(InformacionPersonal::class);
    }

    /**
     * Relación muchos a uno con la clase Departamento.
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class)->withTrashed();
    }

    /**
     * Relación uno a uno con la clase LoginSecurity.
     */
    public function loginSecurity()
    {
        return $this->hasOne(LoginSecurity::class);
    }

    public function hardwareAsignado()
    {
        return $this->hasMany(Hardware::class, 'dueño_id');
    }
}
