<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['nombre'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($categoria) {
            $maxId = self::max('id') + 1;
            $categoria->codigo = 'CAT-' . $maxId;
        });
    }
}
