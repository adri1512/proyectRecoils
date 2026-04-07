@extends('templates.html')
@section('title', 'Inicio de sesión - Recoils')

@section('content')
  <!-- Columna izquierda -->
  <div class="col-lg-4 d-flex flex-column align-items-center justify-content-center p-4 px-5" style="user-select: none;">
    <div class="text-center mb-4" style="user-select: none;">
      <img class="img-fluid mb-0" src="/img/isotipo.png" alt="recoils" style="max-height: 100px;">
      <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span></h2>
    </div>
    <p class="text-muted text-start" style="text-align: justify !important;">¡Bienvenido! Inicia sesión para gestionar tus recolecciones.</p>
    <form class="w-100" id="form_login" method="POST" action="{{ route('user_login_auth') }}">
      @csrf
      <!-- NUMERO DE IDENTIFICACION -->
      <div class="mb-3">
        <input class="form-control" type="text" id="document_user" name="document_user" placeholder="Número Identificación" pattern="[0-9]*" maxlength="15" title="Has alcanzado el máximo de 15 caracteres" required>
      </div>
      <div class="mb-3">
        <!-- CONTRASEÑA DEL USUARIO  -->
        <div class="position-relative">
          <input class="form-control" type="password" id="password_user" name="password_user" placeholder="Contraseña" required>
          <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="password_visibility" style="cursor: pointer;"></i>
        </div>
      </div>
      <button class="btn btn_green w-100" type="submit">Iniciar Sesión</button>
    </form>
    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
      <p class="text-muted mb-0"> ¿No estás registrado? <a class="color_green" href="{{ route('user_signup_create') }}">Regístrate</a></p>
      <a class="color_green" href="{{ route('user_forgot_create') }}">¿Olvidaste tu contraseña?</a>
    </div>
  </div>

  <!-- Columna derecha -->
  <div class="col-lg-8 d-lg-block d-none pe-4 py-4">
    <div class="h-100 w-100 rounded d-flex flex-lg-row flex-column align-items-center justify-content-center" style="background: #e4e2e288;">
      <!-- Imagen a la izquierda -->
      <div class="flex-shrink-1 mb-3 mb-lg-0 me-lg-4 text-center px-2">
        <img class="img-fluid" src="/img/imagen_login.png" alt="impacto_ambiental" style="width: 700px; min-width:300px;  height: auto;">
      </div>
      <!-- Información a la derecha -->
      <div class="text-center text-lg-start pe-5">
        <h4 class="mt-3 mt-lg-0">Impacto Ambiental del Aceite Usado</h4>
        <p class="text-muted">El aceite de cocina usado contamina gravemente las fuentes de agua. Cada litro de aceite puede afectar más de 1.000 litros de agua.</p>
        <p class="text-muted mb-5">Recoils ha creado una red de "Puntos Limpios" en la Costa Atlántica para facilitar el reciclaje doméstico del aceite.</p>
        <div class="mt-2 mt-lg-auto d-flex justify-content-center justify-content-lg-end">
          <a class="btn btn_green" href="https://recoils.com.co/puntos-limpios/" target="_blank">Saber Más<i class="fa-solid fa-arrow-right-long fa-ms ms-2"></i></a>
        </div>
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
    document.addEventListener('DOMContentLoaded', function () {
      // Inputs numericos solo reciben numeros ------------------------------------------
      document.querySelectorAll("#document_user").forEach(input => 
        input.addEventListener("input", () => input.value = input.value.replace(/\D/g, ''))
      );

      // Tooltips para límite de caracteres en los inputs -------------------------------

      function addLimitTooltip(idInput) {
        const input = document.getElementById(idInput);
        if (!input) return;

        // Inicializar tooltip de Bootstrap
        const tooltip = new bootstrap.Tooltip(input, { trigger: 'manual', placement: 'top'});
        
        let timeOut; // para controlar el tiempo del tooltip

        input.addEventListener('input', () => {
          if (input.value.length >= input.maxLength) {
            tooltip.show();

            // Si ya había un timeout, lo limpiamos
            clearTimeout(timeOut);
            
            // Ocultar después de 3 segundos
            timeOut = setTimeout(() => { tooltip.hide(); }, 3000);
          }
        });
      }

      addLimitTooltip("document_user");

      // Visibilidad del input de la contraseña -----------------------------------------
      const password = document.getElementById("password_user");

      document.getElementById("password_visibility").addEventListener("click", () => {
        const icon = document.getElementById('password_visibility');

        password.type = password.type === "password" ? "text" : "password";
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      });

      // Evitar multiples envios del form  ----------------------------------------------
      const form = document.getElementById('form_login');
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

