<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardwareFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'hardware_id',
        'name',
        'description',
        'location',
    ];

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }
}
