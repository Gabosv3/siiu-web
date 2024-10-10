<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'departament_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Definir qué atributos deben ser registrados
    protected static $logAttributes = ['name', 'email']; // ajusta según tus atributos

    // Opcional: Define si quieres que se registre el antiguo valor
    protected static $logOldAttributes = true;

    // Opcional: Personaliza el nombre del log
    protected static $logName = 'user';

    // Opcional: Descripción del evento


    /**
     * Relación uno a uno con la clase InformacionPersonal.
     */
    public function personalInformation()
    {
        return $this->hasOne(personal_information::class);
    }

    /**
     * Relación muchos a uno con la clase Departamento.
     */
    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    /**
     * Relación uno a uno con la clase LoginSecurity.
     */
    public function loginSecurity()
    {
        return $this->hasOne(LoginSecurity::class);
    }

    public function assignedHardware()
    {
        return $this->hasMany(Hardware::class, 'owner_id');
    }

    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
