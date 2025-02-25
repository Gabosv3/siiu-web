<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Supply extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'quantity',
        'unit',
        'description',
        'status',
    ];

    protected static $logAttributes = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'quantity',
        'unit',
        'description',
        'status',
    ];

    protected static $logName = 'supply';

    
    /**
     * Relación con la categoría del insumo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    
    /**
     * Relación con el fabricante del insumo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed();
    }

    
    /**
     * Relación con el modelo del insumo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model()
    {
        return $this->belongsTo(Models::class)->withTrashed();
    }



}
