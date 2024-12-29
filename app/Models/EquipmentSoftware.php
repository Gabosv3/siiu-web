<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentSoftware extends Model
{
    use HasFactory;

    protected $fillable = [
        'hardware_id',
        'software_id',
        'license_id',
    ];

    public function equipment()
    {
        return $this->belongsTo(Hardware::class);
    }

    public function software()
    {
        return $this->belongsTo(Software::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
