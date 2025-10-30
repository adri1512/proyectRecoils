@extends('templates.html')
@section('title', 'Forgot')

@section('content')
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center p-4 w-75">
            <h2 class="text-center mb-4">Restablecer Contraseña</h2>
            <i class="fa-solid fa-lock fa-7x" style="animation: fa-beat 1.5s ease-in-out 1;"></i>
            <p class="h6 text-muted mt-4">Ingrese su correo electrónico a continuación para restablecer su contraseña</p>
    
            <form method="POST" action="/your-form-handler">
                <div class="container mt-4" style="width: 450px;">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Email" required>
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn_green w-100 py-2 mt-3">Restablecer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-body">
                <i class="fa-regular fa-circle-check fa-7x"></i>
                <h2 class="text-center mb-4">Correo Enviado</h2>
                <p>se envío un mensaje a su dirección de correo electrónico para confirmar el restablecimiento de contraseña.</p>
            </div>
          </div>
        </div>
      </div>

@endsection
