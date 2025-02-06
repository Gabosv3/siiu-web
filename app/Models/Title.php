<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Title extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
    ];

    

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
