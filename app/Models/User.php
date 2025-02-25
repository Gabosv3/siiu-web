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
     * Relación uno a uno con el modelo personal_information.
     *
     * Esta función establece una relación 'hasOne' con el modelo personal_information,
     * indicando que un usuario tiene un registro de información personal asociado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function personalInformation()
    {
        return $this->hasOne(personal_information::class)->withTrashed();
    }


    /**
     * Relación con el modelo Departament.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Departament,
     * indicando que el usuario pertenece a un departamento específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function department()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    /**
     * Relación con el modelo Departament.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Departament,
     * indicando que el usuario pertenece a un departamento específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departament()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }


    /**
     * Relación uno a uno con el modelo LoginSecurity.
     *
     * Esta función establece una relación 'hasOne' con el modelo LoginSecurity,
     * indicando que un usuario tiene un registro de seguridad de inicio de sesión asociado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function loginSecurity()
    {
        return $this->hasOne(LoginSecurity::class)->withTrashed();
    }

    /**
     * Relación uno a uno con el modelo HardwareAssignment.
     *
     * Esta función devuelve una relación 'hasOne' con el modelo HardwareAssignment,
     * indicando que un usuario tiene una asignación de hardware asociada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function harwareAssigned()
    {
        return $this->hasOne(HardwareAssignment::class)->withTrashed();
    }

    /**
     * Relación uno a uno con el modelo Technician.
     *
     * Esta función devuelve una relación 'hasOne' con el modelo Technician,
     * indicando que un usuario tiene un técnico asociado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function technician()
    {
        return $this->hasOne(Technician::class)->withTrashed();
    }

    /**
     * Relación uno a muchos con el modelo Ticket.
     *
     * Esta función devuelve una relación 'hasMany' con el modelo Ticket,
     * indicando que un usuario tiene varios tickets asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->withTrashed();
    }

    /**
     * Relación muchos a muchos con el modelo Hardware.
     *
     * Esta función devuelve una relación 'belongsToMany' con el modelo Hardware,
     * indicando que cada usuario puede tener varios hardwares asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hardware()
    {
        return $this->belongsToMany(Hardware::class, 'hardware_user', 'user_id', 'hardware_id')->withTrashed();
    }
}
