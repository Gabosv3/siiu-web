<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class LoginSecurity extends Model
{
    use HasFactory,LogsActivity;

    //especifica la tabla asociada
    protected $table = 'login_securities';
    
    //especifica los campos de la tabla
    protected $fillable = [
        'user_id',  //ID del usuario
        'google2fa_secret', //Clave secreta de Google 2FA
        'google2fa_enable', //Indica si Google 2FA está habilitado
    ];

    
    
    /**
     * Relación muchos a uno con la clase User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
