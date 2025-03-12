<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    protected $fillable = [
        'user_id',
        'technician_id',
        'title_id',
        'description',
        'status',
        'priority',
    ];

    protected static $logAttributes = [
        'user_id',
        'technician_id',
        'title_id',
        'description',
        'status',
        'priority',
    ];

    protected static $logOnlyDirty = true;

    protected static $logName = 'ticket';


    /**
     * Relación con el modelo Technician.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Technician,
     * indicando que cada ticket pertenece a un técnico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function technician()
    {
        return $this->belongsTo(Technician::class)->withTrashed();
    }


    /**
     * Relación con el modelo User.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo User,
     * indicando que cada ticket pertenece a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function userticket()
{
    return $this->belongsTo(User::class, 'user_id');
}

    /**
     * Relación con el modelo Assignment.
     *
     * Esta función devuelve una relación 'hasMany' con el modelo Assignment,
     * indicando que cada ticket tiene varias asignaciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class)->withTrashed();
    }

    /**
     * Relación con el modelo Title.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Title,
     * indicando que cada ticket pertenece a un título.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function title()
    {
        return $this->belongsTo(Title::class)->withTrashed();
    }
}
