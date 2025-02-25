<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Title extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected static $logAttributes = ['name'];
     
    /**
     * Get all of the tickets for the Title
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class)->withTrashed();
    }
}
