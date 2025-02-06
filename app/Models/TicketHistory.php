<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'action', 'description'];

    protected static $logAttributes = ['action', 'description'];

    protected static $logOnlyDirty = true;

    protected static $logName = 'historia de tickets';

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
