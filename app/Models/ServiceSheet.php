<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceSheet extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;


    protected $table = 'service_sheets';

    protected $fillable = [
        'date',
        'department_id',
        'user_id',
        'technician_id',
        'ticket_id',
        'hardware_id',
        'supplies_data',
        'description',
        'observations',
    ];

    protected $casts = [
        'supplies_data' => 'array',
        'date' => 'date',
    ];

    protected static $logAttributes = [
        'date',
        'department_id',
        'user_id',
        'technician_id',
        'ticket_id',
        'hardware_id',
        'supplies_data',
        'description',
        'observations',
    ];

    protected static $logName = 'Hoja de servicio';


    // Relaciones

    public function department()
    {
        return $this->belongsTo(departament::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id')->withTrashed();
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withTrashed();
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }
}
