<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Address;
use App\Models\Department;
use App\Models\Town;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    // MÉTODOS DE LA LÓGICA DE REGISTRO -----------------------------------------------------
    public function signupCreate()
    {
        $departments = Department::all();
        $towns = Town::all();

        return view('auth.signup', compact('departments', 'towns'));
    }

    public function signupStore(Request $request)
    { 
        // Validar los datos de entrada
        $validated = $request->validate([
            'name_user' => 'required|string|max:50',
            'document_user' => 'required|digits_between:6,15|unique:users,document_user',
            'email_user' => 'required|email|max:120|unique:users,email',
            'phone_user' => 'required|digits_between:6,15',
            'department_user' => 'required|integer|exists:departments,id',
            'town_user' => 'required|integer|exists:towns,id',
            'address_user' => 'required|string|max:80',
            'person_type_user' => 'required|string',
            'password_user' => 'required|string|min:8|max:50|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?]).+$/',
        ], [
            'document_user.unique' => 'No se pudo completar el registro.',
            'email_user.unique' => 'No se pudo completar el registro.',
        ]);

        // Validar que el municipio pertenezca al departamento
        $town = Town::findOrFail($validated['town_user']);
        if ($town->id_department != $validated['department_user']) {
            return redirect()->back()->with('error', 'El municipio no corresponde al departamento seleccionado.');
        }

        // Normalización después de la validación
        $validated['name_user'] = strtolower(trim($validated['name_user']));
        $validated['document_user'] = preg_replace('/\D/', '', $validated['document_user']); 
        $validated['email_user'] = strtolower(trim($validated['email_user']));
        $validated['phone_user'] = preg_replace('/\D/', '', $validated['phone_user']); 
        $validated['address_user'] = strtolower(str_replace(' ', '', $validated['address_user']));
        $role_default = "cliente";
        $name_default = "principal";

        // Generar token de verificación único
        do {
            $token = Str::random(64);
        } while (User::where('verification_token', $token)->exists());

        DB::transaction(function () use ($role_default, $token, $name_default, $validated) {
            // Guardar usuario en la BD con email no verificado
            $user = User::create([
                'role' => $role_default,
                'name' => $validated['name_user'],
                'document_user' => $validated['document_user'],
                'email' => $validated['email_user'],
                'verification_token' => $token,
                'phone' => $validated['phone_user'],
                'person_type' => $validated['person_type_user'],
                'password' => Hash::make($validated['password_user']),
            ]);

            $user->addresses()->create([
                'sort_order' => 1,
                'is_main' => true,
                'status' => Address::STATUS_ACTIVA,
                'name' => $name_default,
                'id_town' => $validated['town_user'],
                'address' => $validated['address_user'],
            ]);
        });

        // Enviar el código por correo
        Mail::to($validated['email_user'])->send(new VerificationCodeMail($validated['email_user'], $token));

        // Redirigir a página de verificación
        session(['email' => $validated['email_user']]);
        return redirect()->route('user_verification_show');
    }

    // MÉTODOS DE LA LÓGICA DE VERIFICACIÓN -------------------------------------------------
    public function verificationShow()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('user_login_create')->with('error', 'No se pudo verificar el correo.');
        }

        $user = User::where('email', $email)->firstOrFail();

        // Verificar si el usuario ya está verificado
        if ($user->email_verified_at) {
            return redirect()->route('user_login_create')->with('error', 'Este enlace no está disponible.');
        }

        // Mostrar vista de verificación
        return view('auth.verification_email');
    }

    public function verificationResend()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('user_login_create')->with('error', 'No se pudo verificar el correo.');
        }

        $user = User::where('email', $email)->firstOrFail();

        // Validar si el usuario ya fue verificado
        if ($user->email_verified_at) {
            return redirect()->route('user_login_create')->with('error', 'Este enlace no está disponible.');
        }

        // Verificar límite de 60 segundos
        $last_sent = session('last_sent');
        if ($last_sent) {
            $diff = time() - $last_sent;
            if ($diff < 60) {
                $time = 60 - $diff; 
                return redirect()->back()->with(['error' => "Tienes que esperar {$time} segundos", 'time' => $time]);
            }
        }

        // Generar nuevo token de verificación
        do {
            $token = Str::random(64);
        } while (User::where('verification_token', $token)->exists());

        $user->verification_token = $token;
        $user->save();

        session(['email' => $user->email, 'last_sent' => time(),]);
        
        // Enviar el correo de verificación
        Mail::to($user->email)->send(new VerificationCodeMail($user->email, $user->verification_token));

        return redirect()->back()->with(['time' => 60]);
    }

    public function verificationUpdate(Request $request)
    {
        $validated = $request->validate([
            'new_email' => 'required|email|max:120|unique:users,email',
        ], [
            'new_email.unique' => 'No se pudo actualizar el correo.',
        ]);

        // Normalización después de la validación
        $validated['new_email'] = strtolower(trim($validated['new_email']));

        // Recuperar email de la sesión
        $old_email = session('email');
        
        $user = User::where('email', $old_email)->firstOrFail();

        // Validar si el usuario ya fue verificado
        if ($user->email_verified_at) {
            return redirect()->route('user_login_create')->with('error', 'Este enlace no está disponible.');
        }
        
        // Actualizar correo y token de verificación
        $user->email = $validated['new_email'];
        
        do {
            $token = Str::random(64);
        } while (User::where('verification_token', $token)->exists());

        $user->verification_token = $token;
        $user->save();

        // Actualizar sesión con el nuevo correo
        session(['email' => $user->email]);
        
        // Enviar nuevo correo de verificación
        Mail::to($user->email)->send(new VerificationCodeMail($user->email, $user->verification_token));
        return redirect()->back();
    }

    public function verificationConfirm($email, $verification_token)
    {
        // Buscar al usuario por email y token
        $user = User::where('email', $email)->where('verification_token', $verification_token)->firstOrFail();

        // Validar si ya está verificado
        if ($user->email_verified_at) {
            return redirect()->route('user_login_create')->with('error', 'Este enlace no está disponible.');
        }

        // Marcar el correo como verificado
        $user->verification_token = null; // Eliminar el token para evitar reuso
        $user->email_verified_at = now(); // Marca la fecha y hora de verificación
        $user->save();

        session()->forget('email');
        session()->forget('last_sent');

        // Redirección a la ruta de inicio
        return redirect()->route('user_login_create')->with('success','Se confirmo el correo electronico.');
    }
}
