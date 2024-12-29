<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardwareAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'hardware_id',
        'user_id',
    ];

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
