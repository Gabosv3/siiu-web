<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class HardwareFile extends Model
{
    use HasFactory,LogsActivity,SoftDeletes;
    protected $fillable = [
        'hardware_id',
        'name',
        'description',
        'location',
    ];

    protected $logsAttributes = ['hardware_id', 'name', 'description', 'location'];

    protected $logName = 'hardware_files';

    

    /**
     * Relación con el hardware (Hardware).
     * Un archivo de hardware, pertenece a un hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hardware()
    {
        return $this->belongsTo(Hardware::class)->withTrashed();
    }
}
