@extends('templates.html')
@section('title', 'Verificación Correo - Recoils')

@section('content')
  <div class="col-12 d-flex align-items-center justify-content-center">
    <div class="text-center p-4 w-75">
      <div class="text-center mb-0" style="user-select: none;">
        <img class="img-fluid mb-0" src="/img/isotipo.png" alt="recoils" style="max-height: 100px;">
        <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span></h2>
      </div>
      <div class="mt-4 px-5">
        <p class="text-muted text-start" style="text-align: justify !important;"> Te hemos enviado un correo de verificación a {{ session('email') }}. Por favor, revisa tu bandeja de entrada y haz clic en el enlace para activar tu cuenta.</p>
        <form id="form_resend" method="POST" action="{{ route('user_verification_resend') }}">
          @csrf
          <button class="btn btn_green_border w-100 py-2 mt-3 mb-3" type="submit" id="btn_resend_email">Reenviar correo</button>
        </form>
        <form id="form_update" method="POST" action="{{ route('user_verification_update') }}">
          @csrf
          <p class="text-muted">¿Te equivocaste al ingresar tu correo?</p>
          <button class="btn btn_green w-100 py-2" type="button" data-bs-toggle="modal" data-bs-target="#updateEmailModal">Cambiar correo</button>
          <div class="modal fade" id="updateEmailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-body text-center">
                  <i class="fa-regular fa-envelope fa-7x"></i>
                  <h2 class="text-center mb-4">Actualizar email</h2>
                  <input class="form-control mb-2" type="email" name="new_email" placeholder="Nuevo correo" required>
                  <button class="btn btn_green w-100 py-2" type="submit">Actualizar</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if(session('success') || session('error') || $errors->any())
    <div class="modal fade" id="modal_feedback" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
          @if(session('success'))
            <i class="fa-solid fa-circle-check fa-6x" style="color: #28a745;"></i>
            <h3 class="fw-bold mt-3">¡Éxito!</h3>
            <p>{{ session('success') }}</p>
          @elseif(session('error'))
            <i class="fa-solid fa-circle-xmark fa-6x" style="color: #CD1818;"></i>
            <h3 class="fw-bold mt-3">Problema detectado</h3>
            <p>{{ session('error') }}</p>
          @elseif($errors->any())
            <i class="fa-solid fa-circle-xmark fa-6x" style="color: #CD1818;"></i>
            <h3 class="fw-bold mt-3">Errores de validación</h3>
            <ul class="text-start mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>
    </div>
  @endif

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // Desactivar el boton de reenviar en 60s -----------------------------------------
      const buttonResend = document.getElementById("btn_resend_email");
      let timeResend = {{ session('time', 0)  }}; // Obtener tiempo desde PHP

      if (timeResend > 0) {
        buttonResend.disabled = true;
        buttonResend.classList.add("btn_gray");
        const interval = setInterval(() => {
          buttonResend.innerText = `Reenviar en ${timeResend}s`;
          timeResend--;

          if (timeResend <= 0) {
            clearInterval(interval);
            buttonResend.disabled = false;
            buttonResend.classList.remove("btn_gray");
            buttonResend.innerText = `Reenviar correo`;
          }
        }, 1000);
      }

      // Evitar multiples envios del form  ----------------------------------------------
      const form = document.getElementById('form_update');
      form.addEventListener('submit', function () {
        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
      });

      // Mostrar modal para feedback y errores de validacion ----------------------------
      showFeedbackModal();
      function showFeedbackModal() {
        const modalElement = document.getElementById('modal_feedback');
        if (modalElement) {
          const modal = new bootstrap.Modal(modalElement);
          modal.show();

          // Ocultar el modal automáticamente después de unos segundos
          setTimeout(() => {
            modal.hide();
          }, 7000); 
        }
      }
    });
  </script>
@endsection