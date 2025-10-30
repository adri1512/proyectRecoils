<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function loginCreate()
    {
        return view('auth.login');
    }

    public function loginAuth(Request $request)
    {
        $validated = $request->validate([
            'document_user' => 'required|digits_between:6,15',
            'password_user' => 'required|string|min:8|max:50',
        ]);

        // Normalización después de la validación
        $validated['document_user'] = preg_replace('/\D/', '', $validated['document_user']); 

        $user = User::where('document_user', $validated['document_user'] )->first();

        // El usuario no existe
        if (!$user) {
            return redirect()->back()->with('error', 'El usuario no existe.');
        }

        // El usuario no está verificado
        if (!$user->email_verified_at) {
            return redirect()->back()->with('error', 'Tu cuenta aún no ha sido verificada.');
        }

        // Comparación en la base de datos
        $credentials = [
            'document_user' => $validated['document_user'],
            'password' => $validated['password_user'],
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->route('client_home');
        }

        return redirect()->back()->with('error', 'Las credenciales no coinciden con nuestros registros.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user_login_create');
    }
}
