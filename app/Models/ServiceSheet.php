<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceSheet extends Model
{
    use HasFactory, LogsActivity;


    protected $table = 'service_sheets';


    protected $fillable = [
        'date', 'department', 'user_id', 'technician_id', 'hardware_id', 'inventory_number',
        'serial_number', 'model', 'status', 'description', 'observations', 'use_supply'
    ];

/*************  ✨ Codeium Command ⭐  *************/
    /**
/******  dbbd8c90-e085-4b6d-91a8-731e63b69d4a  *******/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }
}
