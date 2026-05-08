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
