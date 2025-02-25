<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Hardware extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'hardware';

    protected $fillable = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'conflicts',
        'status',
        'inventory_code',
        'serial_number',
        'warranty_expiration_date',
        'barcode_path'


    ];

    // Configurar los atributos que se registrarán
    protected static $logAttributes = [
        'category_id',
        'manufacturer_id',
        'model_id',
        'name',
        'conflicts',
        'status',
        'inventory_code',
        'serial_number',
        'warranty_expiration_date',
    ];

    // Personalizar el nombre del registro de actividad
    protected static $logName = 'hardware';


    /**
     * Relación muchos a uno con el modelo Category.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Category,
     * indicando que cada hardware pertenece a una categoría específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }


    /**
     * Relación muchos a uno con el modelo User.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo User,
     * indicando que cada hardware pertenece a un usuario específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    
    /**
     * Relación muchos a uno con el modelo Departament.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo
     * Departament, indicando que cada hardware está asociado a una
     * ubicación específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Departament::class)->withTrashed();
    }

    
    /**
     * Relación muchos a uno con el modelo Manufacturer.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Manufacturer,
     * indicando que cada hardware pertenece a un fabricante específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class)->withTrashed();
    }

    
    /**
     * Relación muchos a uno con el modelo Model.
     *
     * Esta función devuelve una relación 'belongsTo' con el modelo Model,
     * indicando que cada hardware pertenece a un modelo específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model()
    {
        return $this->belongsTo(Models::class)->withTrashed();
    }

    
    /**
     * Relación uno a uno con el modelo HardwareAssignment.
     *
     * Esta función devuelve una relación 'hasOne' con el modelo HardwareAssignment,
     * indicando que cada hardware tiene una asignación de hardware asociada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hardwareAssigned()
    {
        return $this->hasOne(HardwareAssignment::class)->withTrashed();
    }

    /**
     * Relación uno a muchos con el modelo HardwareAssignment.
     *
     * Esta función devuelve una relación 'hasMany' con el modelo HardwareAssignment,
     * indicando que cada hardware tiene varias asignaciones de hardware asociadas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hardwareAssignments()
    {
        return $this->hasMany(HardwareAssignment::class)->withTrashed();
    }

    /**
     * Relación uno a muchos con el modelo EquipmentHistory.
     *
     * Esta función devuelve una relación 'hasMany' con el modelo EquipmentHistory,
     * indicando que cada hardware tiene varios registros de historial de equipo
     * asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipmentHistories()
    {
        return $this->hasMany(EquipmentHistory::class)->withTrashed();
    }
    
    /**
     * Relación muchos a muchos con el modelo User.
     *
     * Esta función devuelve una relación 'belongsToMany' con el modelo User,
     * indicando que cada hardware puede ser asignado a varios usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'hardware_user', 'hardware_id', 'user_id')->withTrashed();
    }

    /**
     * Relación de muchos a muchos a través de EquipmentSoftware con el modelo License.
     *
     * Esta función devuelve una relación 'hasManyThrough' con el modelo License,
     * indicando que cada hardware puede tener múltiples licencias a través del modelo
     * intermedio EquipmentSoftware. Esto permite acceder a las licencias asociadas a
     * los softwares instalados en el hardware.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */

    public function licencias()
    {
        return $this->hasManyThrough(
            License::class,          // Modelo destino
            EquipmentSoftware::class, // Modelo intermedio
            'hardware_id',           // Llave foránea en la tabla intermedia hacia `hardware`
            'software_id',           // Llave foránea en `licenses` hacia `softwares`
            'id',                    // Llave local en `hardware`
            'software_id'            // Llave local en `equipment_softwares`
        );
    }

    /**
     * Relación de muchos a muchos con el modelo Software.
     *
     * Esta función devuelve una relación 'belongsToMany' con el modelo Software,
     * indicando que cada hardware puede tener varios softwares instalados.
     * La relación se establece a través de la tabla pivot 'equipment_softwares'.
     * La función 'withPivot' se utiliza para incluir el campo extra 'license_id'
     * de la tabla pivot en la relación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'equipment_softwares', 'hardware_id', 'software_id')
            ->withPivot('license_id') // Incluye el campo extra de la tabla pivot
            ->withTimestamps(); // Para que los timestamps de la tabla pivot se incluyan
    }

    /**
     * Relación de uno a muchos con el modelo HardwareFile.
     *
     * Esta función devuelve una relación 'hasMany' con el modelo HardwareFile,
     * indicando que cada hardware puede tener varios archivos relacionados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(HardwareFile::class)->withTrashed();
    }
}
