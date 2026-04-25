<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DriverController;

Route::get('/login', function () { return view('auth.login');})->name('login');
Route::get('/', [AuthController::class, 'loginCreate'])->name('user_login_create');
Route::post('/usuario/inicio/sesion', [AuthController::class, 'loginAuth'])->name('user_login_auth');
Route::post('/usuario/cerrar/sesion', [AuthController::class, 'logout'])->name('user_logout');
Route::get('/usuario/contraseña/restablecer', [AuthController::class, 'forgotCreate'])->name('user_forgot_create');
Route::post('/usuario/contraseña/restablecer/correo', [AuthController::class, 'forgotStore'])->name('user_forgot_store');
Route::get('/usuario/contraseña/restablecer/{email}/{token}', [AuthController::class, 'passwordReset'])->name('user_password_reset');
Route::post('/usuario/contraseña/restablecer/actualizar', [AuthController::class, 'passwordUpdate'])->name('user_password_update');


Route::get('/usuario/registro', [SignUpController::class, 'signupCreate'])->name('user_signup_create');
Route::post('/usuario/registro/guardar', [SignUpController::class, 'signupStore'])->name('user_signup_store');
Route::get('/usuario/verificacion/correo', [SignUpController::class, 'verificationShow'])->name('user_verification_show');
Route::post('/usuario/verificacion/correo/reenviar', [SignUpController::class, 'verificationResend'])->name('user_verification_resend');
Route::post('/usuario/verificacion/correo/actualizar', [SignUpController::class, 'verificationUpdate'])->name('user_verification_update');
Route::get('/usuario/verificacion/correo/{email}/{verification_token}', [SignUpController::class, 'verificationConfirm'])->name('user_verification_confirm');

// Route::get('/signup', function () { return view('signup');})->name('signup');

// RUTAS PARA EL ROL DE CLIENTE
Route::middleware(['auth', 'role:cliente'])->group(function () { 
  Route::get('/cliente',  [ClientController::class, 'home'])->name('client_home');

  Route::get('/cliente/direcciones', [ClientController::class, 'addressIndex'])->name('client_address_index');
  Route::get('/cliente/direcciones/agregar', [ClientController::class, 'addressCreate'])->name('client_address_create');
  Route::post('/cliente/direcciones/guardar', [ClientController::class, 'addressStore'])->name('client_address_store');
  Route::get('/cliente/direcciones/editar/{id}', [ClientController::class, 'addressEdit'])->name('client_address_edit');
  Route::put('/cliente/direcciones/actualizar/{id}', [ClientController::class, 'addressUpdate'])->name('client_address_update');
  Route::delete('/cliente/direcciones/eliminar/{id}', [ClientController::class, 'addressDestroy'])->name('client_address_delete');

  Route::get('/cliente/solicitud', [ClientController::class, 'pickupRequestCreate'])->name('client_pickup_request_create');
  Route::post('/cliente/solicitud/guardar', [ClientController::class, 'pickupRequestStore'])->name('client_pickup_request_store');
  Route::get('/cliente/solicitudes', [ClientController::class, 'pickupRequestIndex'])->name('client_pickup_request_index');
  Route::get('/cliente/solicitudes/ver/{id}', [ClientController::class, 'pickupRequestShow'])->name('client_pickup_request_show');

    Route::get('/cliente/perfil', [ClientController::class, 'profile'])->name('client_profile');
});

// RUTAS PARA EL ROL DE CONDUCTOR
Route::middleware(['auth', 'role:conductor'])->group(function () { 
  Route::get('/conductor',  [DriverController::class, 'home'])->name('driver_home');
  Route::get('/conductor/miruta/recoleccion',  [DriverController::class, 'route'])->name('driver_route');
  Route::get('/conductor/miruta/mirecoleccion/{id}',  [DriverController::class, 'route_stop'])->name('driver_route_stop');
  Route::get('/conductor/perfil', [DriverController::class, 'profile'])->name('driver_profile');
});
