<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    //
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'id_client',
        'sort_order',
        'is_main',
        'status',
        'previous_id',
        'name',
        'id_town',
        'neighborhood',
        'address',
        'reference',
    ];

    // Estados posibles de la dirección
    const STATUS_ACTIVA = 'activa'; // La dirección está disponible y se puede usar.
    const STATUS_INACTIVA = 'inactiva'; // La dirección ya no se usa para nuevas solicitudes, pero sigue en el historial porque tiene solicitudes asociadas.
    const STATUS_ELIMINADA = 'eliminada'; // El usuario la borró, pero se mantienes porque tiene solicitudes asociadas.
    
    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    // Relación con el modelo Town
    public function town()
    {
        return $this->belongsTo(Town::class, 'id_town');
    }

    // Relación con el modelo PickupRequest
    public function pickuprequest()
    {
    return $this->hasMany(PickupRequest::class, 'id_address');
    }
}
