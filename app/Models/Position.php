<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = ['shelf_id', 'estado'];
    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function hardware()
    {
        return $this->belongsTo(Hardware::class);
    }
}
