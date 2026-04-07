@extends('templates.html')
@section('title', 'Restablecer contraseña - Recoils')

@section('content')
    <div class="col-12 d-flex align-items-center justify-content-center">
      <div class="text-center p-4 w-75">
      <div class="text-center mb-0" style="user-select: none;">
        <img class="img-fluid mb-0" src="/img/isotipo.png" alt="recoils" style="max-height: 100px;">
        <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span></h2>
      </div>
        
      <p class="h6 text-muted mt-3">Ingrese su correo electrónico a continuación para restablecer su contraseña.</p>
      <form id="form_forgot" method="POST" action="{{ route('user_forgot_store') }}">
        @csrf
          <div class="container mt-4" style="width: 450px;">
            <input type="text" class="form-control" id="email_forgot" name="email_forgot" placeholder="Email" required>
            <button type="submit" class="btn btn_green w-100 py-2 mt-3">Enviar</button>
          </div>
      </form>
      
      <p class="mt-3 text-muted">¿Ya tienes una cuenta? <a class="color_green" href="{{ route('user_login_create') }}">Ingresa Aquí</a></p>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Evitar multiple envios y contraseña invalida -----------------------------------
      const form = document.getElementById('form_forgot');

      form.addEventListener("submit", function (e) {

        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
      });
    });
  </script>
@endsection
