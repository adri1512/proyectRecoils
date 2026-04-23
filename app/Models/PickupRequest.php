<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupRequest extends Model
{
    protected $table = 'pickup_requests';

    protected $fillable = [
        'id_client',
        'id_address',
        'phone',
        'requested_date',
        'scheduled_date',
        'container_quantify',
        'additional_details',
        'status',
        'id_driver',
    ];

    // Estados posibles de la solicitud
    const STATUS_PENDIENTE = 'pendiente'; // El usuario realizo la solicitud, se encuentra pendiente de revisión del logistico.
    const STATUS_ASIGNADA = 'asignada'; // La solicitud se encuentra asignada a una ruta por el logistico.
    const STATUS_EN_RUTA = 'en ruta'; // La solicitud de recolección esta en proceso.
    const STATUS_COMPLETADA = 'completada'; // la solicitud ya fue completada.
    const STATUS_CANCELADA = 'cancelada'; // la solicitud fue cancelada

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_client');
    }

    // Relación con el modelo Address
    public function address()
    {
        return $this->belongsTo(Address::class, 'id_address');
    }

    // Relación con el modelo User(Conductor)
    public function driver()
    {
        return $this->belongsTo(User::class, 'id_driver');
    }
}
