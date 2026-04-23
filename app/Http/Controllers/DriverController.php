<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PickupRequest;

class DriverController extends Controller
{
    public function home()
    {
        return view('drivers.home');
    }

    public function route()
    {
        $pickups = Auth::user()->pickuprequest_driver()->with(['user', 'address'])->whereDate('scheduled_date', now())->get();
        
        return view('drivers.route', compact('pickups'));
    }

    public function profile()
    {
        return view('drivers.profile');
    }
}
