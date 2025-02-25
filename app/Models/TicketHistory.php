<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TicketHistory extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    protected $fillable = ['ticket_id', 'action', 'description'];

    protected static $logAttributes = ['action', 'description'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'historia de tickets';

    /**
     * Relación con el ticket al que pertenece.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withTrashed();
    }
}
