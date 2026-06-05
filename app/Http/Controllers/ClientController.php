<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Town;
use App\Models\Address;
use App\Models\PickupRequest;

class ClientController extends Controller
{
    public function home()
    {
       //Contar la cantidad de solicitudes en cada estado del proceso.
        $query = Auth::user();

        $pickup_counts = [
            'pendientes' => $query->pickuprequest()->where('status', 'pendiente')->count(),
            'asignadas' => $query->pickuprequest()->where('status', 'asignada')->count(),
            'en_ruta' => $query->pickuprequest()->where('status', 'en ruta')->count(),
            'completadas' => $query->pickuprequest()->where('status', 'completada')->count(),
            'canceladas' => $query->pickuprequest()->where('status', 'cancelada')->count(),
        ];

        $next_pickups = $query->pickuprequest()
        ->whereIn('status', ['asignada', 'en ruta'])
        ->whereDate('scheduled_date', '>=', now())
        ->orderBy('scheduled_date', 'asc')
        ->take(5)
        ->get();

    $recent_pickups = $query->pickuprequest()
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        return view('clients.home', compact('pickup_counts', 'next_pickups', 'recent_pickups'));
    } 

    // MÉTODOS DE LA LÓGICA DE LAS DIRECCIONES ----------------------------------------------
    public function addressIndex()
    {
        // Trae los registros de las direcciones activas
        $addresses = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->orderBy('sort_order', 'asc')->get();

        // Retorna a la vista donde se enlistan las direcciones
        return view('clients.address.index_address', compact('addresses'));
    }

    public function addressCreate()
    {
        // Trae los registros de los departamentos y de los municipios
        $departments = Department::all();
        $towns = Town::all();

        // Retorna a la vista de agregar con los registros de los departamentos y de los municipios
        return view('clients.address.add_address', compact('departments', 'towns'));
    }

    public function addressStore(Request $request)
    {
        // Valida los datos que vienen de la vista
        $validated = $request->validate([
            'name_address' => 'required|string|max:25',
            'department_address' => 'required|integer|exists:departments,id',
            'town_address' => 'required|integer|exists:towns,id',
            'neighborhood_address' => 'nullable|string|max:25',
            'street_address' => 'required|string|max:80',
            'reference_address' => 'nullable|string|max:100',
        ]);

        // Normalización después de la validación
        $validated['name_address'] = strtolower(trim($validated['name_address']));
        $validated['street_address'] = strtolower(str_replace(' ', '', $validated['street_address']));
        $validated['neighborhood_address'] = $validated['neighborhood_address'] ? strtolower(trim($validated['neighborhood_address'])) : null;
        $validated['reference_address'] = $validated['reference_address'] ? strtolower(trim($validated['reference_address'])) : null;

        // Validar que el municipio pertenezca al departamento escogido
        $town = Town::findOrFail($validated['town_address']);
        if ($town->id_department != $validated['department_address']) {
            return redirect()->back()->with('error', 'El municipio no corresponde al departamento seleccionado.');
        }

        // Verificar duplicados activos
        $addressExists = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->where('id_town', $validated['town_address'])->where('address', $validated['street_address'])->exists();
        if ($addressExists) {
            return redirect()->back()->with('error', 'Ya tienes registrada esta dirección.');
        }

        // Crea el registro de la dirección en la bd
        Auth::user()->addresses()->create([
            'sort_order' => (Auth::user()->addresses()->max('sort_order') ?? 0) + 1,
            'is_main' => false,
            'name' => $validated['name_address'], 
            'id_town' => $validated['town_address'],
            'status'=> Address::STATUS_ACTIVA,
            'neighborhood' => $validated['neighborhood_address'],
            'address' => $validated['street_address'],
            'reference' => $validated['reference_address'],
        ]);

        // Redirige a la vista donde salen la lista de direcciones
        return redirect()->route('client_address_index')->with('success', 'Dirección agregada correctamente.');
    }

    public function addressEdit($id)
    {
        // Trae los registros de los departamentos y de los municipios
        $departments = Department::all();
        $towns = Town::all();

        // Trae el registro de la dirección que escogió el usuario
        $address = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->findOrFail($id);

        // Retorna a la vista de editar con los registros de los departamentos, municipios y la dirección que escogió el usuario
        return view('clients.address.edit_address', compact('address', 'departments', 'towns'));
    }

    public function addressUpdate(Request $request, $id)
    {
        // Trae el registro de la dirección que escogió el usuario
        $address = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->findOrFail($id);

        // Valida los datos que vienen de la vista
        $validated = $request->validate([
            'name_address' => 'required|string|max:25',
            'department_address' => 'required|integer|exists:departments,id',
            'town_address' => 'required|integer|exists:towns,id',
            'neighborhood_address' => 'nullable|string|max:25',
            'street_address' => 'required|string|max:80',
            'reference_address' => 'nullable|string|max:100',
        ]);

        // Normalización después de la validación
        $validated['name_address'] = strtolower(trim($validated['name_address']));
        $validated['street_address'] = strtolower(str_replace(' ', '', $validated['street_address']));
        $validated['neighborhood_address'] = $validated['neighborhood_address'] ? strtolower(trim($validated['neighborhood_address'])) : null;
        $validated['reference_address'] = $validated['reference_address'] ? strtolower(trim($validated['reference_address'])) : null;

        // Validar si hubo cambios
        $data = [ 'name' => $validated['name_address'], 'id_town' => $validated['town_address'], 'neighborhood' => $validated['neighborhood_address'], 'address' => $validated['street_address'], 'reference' => $validated['reference_address'], ];
        if ($address->only(array_keys($data)) == $data) {
            return redirect()->route('client_address_index')->with('error', 'No se detectaron cambios en la dirección.');
        }

        // Validar que el municipio pertenezca al departamento escogido
        $town = Town::findOrFail($validated['town_address']);
        if ($town->id_department != $validated['department_address']) {
            return redirect()->back()->with('error', 'El municipio no corresponde al departamento seleccionado.');
        }

        // Verificar duplicados activos
        $addressExists = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->where('id', '!=', $address->id)->where('id_town', $validated['town_address'])->where('address', $validated['street_address'])->exists();
        if ($addressExists) {
            return redirect()->back()->with('error', 'Ya tienes registrada esta dirección.');
        }

        // Determinar si la dirección ya se ha usado en solicitudes de recolección
        if($address->pickuprequest()->exists() && ($address->id_town != $validated['town_address'] || $address->address != $validated['street_address'])) {

            DB::transaction(function () use ($address, $validated) {
                // Crear nuevo registro de dirección para no perder trazabilidad
                Auth::user()->addresses()->create([
                    'sort_order' => $address->sort_order,
                    'is_main' => $address->is_main,
                    'status' => Address::STATUS_ACTIVA,
                    'previous_id' => $address->id,
                    'name' => $validated['name_address'],
                    'id_town' => $validated['town_address'],
                    'neighborhood' => $validated['neighborhood_address'],
                    'address' => $validated['street_address'],
                    'reference' => $validated['reference_address'],
                ]);

                // Marcar la anterior como inactiva
                $address->update([
                    'is_main' => false,
                    'status' => Address::STATUS_INACTIVA,
                ]);
            });

        } else {
            // Solo cambios secundarios (nombre, barrio, referencia) o no tiene solicitudes
            $address->update([
                'name' => $validated['name_address'],
                'id_town' => $validated['town_address'],
                'neighborhood' => $validated['neighborhood_address'],
                'address' => $validated['street_address'],
                'reference' => $validated['reference_address'],
            ]);
        };

        // Redirige a la vista donde salen la lista de direcciones
        return redirect()->route('client_address_index')->with('success', 'Dirección actualizada correctamente.');
    }

    public function addressDestroy($id)
    {
        // Trae el registro de la dirección que escogió el usuario
        $address = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->findOrFail($id);

        // Asegura que la dirección principal no se pueda borrar
        if ($address->is_main) {
            return redirect()->back()->with('error', 'La dirección principal no se puede eliminar.');
        }

        if($address->pickuprequest()->exists()) {
            // Marcar la dirección como eliminada
            $address->update(['status' => Address::STATUS_ELIMINADA]);
        } else {
            $address->delete();
        };

        return redirect()->back()->with('success', 'Dirección eliminada correctamente');
    }

    // MÉTODOS DE LA LÓGICA DE LAS SOLICITUDES ----------------------------------------------
    public function pickupRequestCreate()
    {
        // Trae los registros de las direcciones activas
        $addresses = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->orderBy('sort_order', 'asc')->get();

        // Retorna a la vista donde se enlistan las direcciones
        return view('clients.pickup_request', compact('addresses'));
    }

    public function pickupRequestStore(Request $request)
    {
        // Valida los datos que vienen de la vista
        $validated = $request->validate([
            'address_pickup' => 'required|exists:addresses,id',
            'phone_pickup' => 'required|string|regex:/^[0-9]+$/|min:7|max:15',
            'container_quantify_pickup' => 'required|string|regex:/^[0-9]+$/|max:3',
            'notes_pickup' => 'nullable|string|max:100',
        ]);

        // Normalización después de la validación
        $validated['phone_pickup'] = preg_replace('/\D/', '', $validated['phone_pickup']);
        $validated['container_quantify_pickup'] = (int) preg_replace('/\D/', '', $validated['container_quantify_pickup']);
        $validated['notes_pickup'] = $validated['notes_pickup'] ? strtolower(trim($validated['notes_pickup'])) : null;

        
        // Trae el registro de la dirección que escogió el usuario
        $id = $validated['address_pickup'];
        $address = Auth::user()->addresses()->where('status', Address::STATUS_ACTIVA)->findOrFail($id);

        // Verificar duplicados
        $pickupRequestExists = Auth::user()->pickuprequest()->where('id_address', $validated['address_pickup'])->whereIn('status', ['pendiente', 'asignada'])->exists();
        if ($pickupRequestExists) {
            return redirect()->back()->with('error', 'Ya tienes una solicitud pendiente o asignada para esta fecha y dirección.');
        }

        // Crea el registro de la solicitud en la bd
        Auth::user()->pickuprequest()->create([
            'id_address' => $validated['address_pickup'],
            'phone' => $validated['phone_pickup'],
            'container_quantify' => $validated['container_quantify_pickup'],
            'additional_details' => $validated['notes_pickup'],
            'status'=> PickupRequest::STATUS_PENDIENTE,
        ]);

        // Redirige a la vista donde salen la lista de direcciones
        return redirect()->route('client_pickup_request_create')->with('success', 'Solicitud realizada correctamente.');
    }

    public function pickupRequestIndex(Request $request)
    {
        $query = Auth::user()->pickuprequest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Trae los registros de las solicitudes
        $pickupRequests = $query->paginate(4)->appends($request->query());

        // Retorna a la vista donde se enlistan las direcciones
        return view('clients.pickup_request_index', compact('pickupRequests'));
    }

    public function pickupRequestShow($id)
    {
        // Buscar la solicitud de recogida que pertenece al usuario autenticado
        $pickupRequests = Auth::user()->pickuprequest()->findOrFail($id);

        // Retorna a la vista donde se enlistan las direcciones
        return view('clients.pickup_request_show', compact('pickupRequests'));
    }

    public function profile()
    {
        return view('clients.profile');
    }
}

