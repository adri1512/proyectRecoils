<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


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

    public function forgotCreate()
    {
        return view('auth.forgot_password');
    }

    public function forgotStore(Request $request)
    {
        // Validar los datos de entrada
        $validated = $request->validate([
            'email_forgot' => 'required|email|max:120|exists:users,email',
        ], [
            'email_forgot.exists' => 'Correo no valido',
        ]);

        // Normalización después de la validación 
        $validated['email_forgot'] = strtolower(trim($validated['email_forgot']));

        // Generar token de verificación único
        do {
            $token = Str::random(64);
        } while (DB::table('password_reset_tokens')->where('token', $token)->exists());

        // Guardar en la base de datos
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $validated['email_forgot']],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Enviar el código por correo
        Mail::to($validated['email_forgot'])->send(new ResetPasswordMail($validated['email_forgot'], $token));

        return redirect()->route('user_login_create')->with('success', 'Te enviamos un correo para restablecer tu contraseña.');
    }

    public function passwordReset($email, $token)
    {
        // Buscar el registro del correo en la tabla de los tokens de restablecer contraseña
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        // El email no existe en la tabla de los tokens de restablecer contraseña
        if (!$record) {
            return redirect()->route('user_login_create')->with('error', 'El enlace es inválido');
        }

        // Validar tiempo del token (una hora de duración)
        $createdAt = \Carbon\Carbon::parse($record->created_at);

        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return redirect()->route('user_login_create')->with('error', 'El enlace ha expirado');
        }

        // Validar token con el guardado en la base de datos
        if ($token !== $record->token) {
            return redirect()->route('user_login_create')->with('error', 'Token de contraseña inválido');
        }

        return view('auth.reset_password', ['email' => $email, 'token' => $token]);
    }

    public function passwordUpdate(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'new_password_user' => 'required|string|min:8|max:50|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:\'",.<>\/?]).+$/',
            'confirm_password_user' => 'required|same:new_password_user',
            'email' => 'required|email',
            'token' => 'required'
        ]);

        // Buscar el registro del correo en la tabla de los tokens de restablecer contraseña
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // El email no existe en la tabla de los tokens de restablecer contraseña
        if (!$record) {
            return redirect()->route('user_login_create')->with('error', 'El enlace es inválido');
        }

        // Validar tiempo del token (una hora de duración)
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        
        if ($createdAt->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('user_login_create')->with('error', 'El enlace ha expirado');
        }

        // Validar token con el guardado en la base de datos
        if ($request->token !== $record->token) {
            return redirect()->route('user_login_create')->with('error', 'Token de contraseña inválido');
        }

        // Buscar al usuario con el email
        $user = User::where('email', $request->email)->first();

        // Usuario no encontrado
        if (!$user) {
            return redirect()->route('user_login_create')->with('error', 'Usuario no encontrado');
        }

        // Validar que la contraseña nueva no sea la misma que la anterior
        if (Hash::check($request->new_password_user, $user->password)) {
            return redirect()->route('user_login_create')->withErrors(['new_password_user' => 'La nueva contraseña no puede ser igual a la anterior']);
        }

        DB::transaction(function () use ($request, $user) {
            // Actualizar el registro con la contraseña nueva
            $user->update([
                'password' => Hash::make($request->new_password_user)
            ]);

            // Eliminar registro del token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        });

        // return view('auth.reset_password');
        return redirect()->route('user_login_create')->with('success', 'Contraseña actualizada correctamente');
    }
}
