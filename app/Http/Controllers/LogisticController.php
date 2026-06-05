<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PickupRequest;

class LogisticController extends Controller
{
    public function home()
    {
        return view('logistic.home');
    }

     public function pickupRequestIndex(Request $request)
    {

        $query = PickupRequest::with(['user', 'address']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Trae los registros de las solicitudes
        $pickupRequests = $query->paginate(4)->appends($request->query());

        // Retorna a la vista donde se enlistan las direcciones
        return view('logistic.pickup_request_index', compact('pickupRequests'));
    }

    public function pickupRequestShow($id)
    {
        // Buscar la solicitud de recogida que pertenece al usuario autenticado
        $pickupRequests = PickupRequest::findOrFail($id);

        // Retorna a la vista donde se enlistan las direcciones
        return view('logistic.pickup_request_show', compact('pickupRequests'));
    }

    public function routeCreate()
    {
        $pickups = PickupRequest::with(['user', 'address'])->where('status', 'pendiente')->get();

        return view('logistic.route', compact('pickups'));
    }

    public function routeStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
        ]);
    }

    public function route_stop($id)
    {
        $pickup = PickupRequest::with(['user', 'address'])->findOrFail($id);
        
        return view('drivers.route_stop', compact('pickup'));
    }

    public function profile()
    {
        return view('drivers.profile');
    }
}
