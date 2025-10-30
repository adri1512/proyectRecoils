@extends('templates.html')
@section('title', 'Registro - Recoils')

@section('content')
  <div class="col-12 d-flex align-items-center justify-content-center">
    <div class="text-center p-4 w-75">
      <div class="text-center mb-0" style="user-select: none;">
        <img class="img-fluid mb-0" src="/img/isotipo.png" alt="recoils" style="max-height: 100px;">
        <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span></h2>
      </div>
        
      <p class="h6 text-muted text-start mt-3">Regístrate para realizar tu primera recolección.</p>
        
      <form id="form_signup" method="POST" action="{{ route('user_signup_store') }}">
        @csrf
        <div class="row g-3">
          <!-- NOMBRE DEL USUARIO -->
          <div class="col-md-6">
            <input class="form-control" type="text" id="name_user" name="name_user" placeholder="Nombre Usuario" maxlength="50" title="Has alcanzado el máximo de 50 caracteres" required>
          </div>

          <!-- NUMERO DE IDENTIFICACION -->
          <div class="col-md-6">
            <input class="form-control" type="text" id="document_user" name="document_user" pattern="[0-9]*" placeholder="Número Identificación" maxlength="15" title="Has alcanzado el máximo de 15 caracteres" required>
          </div>

          <!-- EMAIL DEL USUARIO -->
          <div class="col-md-6">
            <input class="form-control" type="email" id="email_user" name="email_user" placeholder="Correo Electrónico" maxlength="120" title="Has alcanzado el máximo de 120 caracteres" required>
          </div>

          <!-- CELULAR DEL USUARIO -->
          <div class="col-md-6">
            <input class="form-control" type="text" class="form-control" id="phone_user" name="phone_user"  pattern="[0-9]*" placeholder="Número Celular" maxlength="15" title="Has alcanzado el máximo de 15 caracteres" required>
          </div>

          <!-- SELECT DE DEPARTAMENTOS -->
          <div class="col-md-6">
            <select class="form-select" id="department_user" name="department_user" required>
              <option value="">Seleccione un departamento</option>
              @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
              @endforeach
            </select>
          </div>

          <!-- SELECT DE MUNICIPIOS -->
          <div class="col-md-6">
            <select class="form-select" id="town_user" name="town_user" required>
              <option value="">Seleccione un municipio</option>
            </select>
          </div>

          <!-- DIRECCIÓN DEL USUARIO -->
          <div class="col-md-6">
            <input class="form-control" type="text" id="address_user" name="address_user" placeholder="Dirección" maxlength="80" title="Has alcanzado el máximo de 80 caracteres" required>
          </div>

          <!-- SELECT TIPO DE PERSONA -->
          <div class="col-md-6">
            <select class="form-select" id="person_type_user" name="person_type_user" required>
              <option value="">Tipo de Cliente</option>
              <option value="natural">Natural</option>
              <option value="juridico">Jurídico</option>
            </select>
          </div>

          <!-- CONTRASEÑA DEL USUARIO  -->
          <div class="col-md-6">
            <div class="position-relative">
              <input class="form-control" type="password" id="password_user" name="password_user" placeholder="Contraseña" data-bs-toggle="tooltip" data-bs-placement="bottom" required>
              <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="password_visibility" style="cursor: pointer;"></i>
            </div>
          </div>
        </div>
        
        <button type="submit" class="btn btn_green w-50 py-2 mt-3">Registrarse</button>
      </form>
      <p class="mt-3 text-muted">¿Ya tienes una cuenta? <a class="color_green" href="{{ route('user_login_create') }}">Ingresa Aquí</a></p>
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
      // Municipios dinámicos según departamento ----------------------------------------
      const towns = @json($towns); // Pasamos los municipios que vienen del controller en PHP a JS

      document.getElementById('department_user').addEventListener('change', function() {
        // Escuchamos el id del select del departamento
        const idDepartment = this.value;
        const selectTown = document.getElementById('town_user');

        // Limpiamos el select de municipios
        selectTown.innerHTML = '<option value="">Seleccione un municipio</option>';

        // Creamos las opciones que correspondan al departamento selecionado
        towns.forEach(function(m) {
          if (m.id_department == idDepartment) {
            const option = document.createElement('option');
            option.value = m.id;
            option.textContent = m.name;
            selectTown.appendChild(option);
          }
        });
      });

      // Inputs numericos solo reciben numeros ------------------------------------------
      document.querySelectorAll("#document_user, #phone_user").forEach(input => 
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

      addLimitTooltip("name_user");
      addLimitTooltip("document_user");
      addLimitTooltip("email_user");
      addLimitTooltip("phone_user");
      addLimitTooltip("address_user");

      // Visibilidad del input de la contraseña -----------------------------------------
      const password = document.getElementById("password_user");

      document.getElementById("password_visibility").addEventListener("click", () => {
        const icon = document.getElementById('password_visibility');

        password.type = password.type === "password" ? "text" : "password";
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      });

      // Tooltip para validacion de la contraseña  --------------------------------------

      let tooltip = new bootstrap.Tooltip(password, { trigger: 'manual', html: true, placement: 'top' });

      function validatePassword(value) {

        const validLength = value.length >= 8;
        const hasUppercase = /[A-Z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecial = /[!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?]/.test(value);

        const messages = [];

        messages.push(`<i class="fa-solid ${validLength ? 'fa-check fa-lg text-success' : 'fa-xmark fa-xl text-danger'}"></i> Mínimo 8 caracteres`);
        messages.push(`<i class="fa-solid ${hasUppercase ? 'fa-check fa-lg text-success' : 'fa-xmark fa-xl text-danger'}"></i> Mínimo una mayúscula`);
        messages.push(`<i class="fa-solid ${hasNumber ? 'fa-check fa-lg text-success' : 'fa-xmark fa-xl text-danger'}"></i> Al menos un número`);
        messages.push(`<i class="fa-solid ${hasSpecial ? 'fa-check fa-lg text-success' : 'fa-xmark fa-xl text-danger'}"></i> Mínimo un caracter especial`);

        password.setAttribute("data-bs-original-title", messages.join("<br>"));
        tooltip.dispose(); // Destruye el tooltip anterior para actualizar el contenido
        tooltip = new bootstrap.Tooltip(password, { trigger: 'manual', html: true, placement: 'top' });

        const isValid = validLength && hasUppercase && hasNumber && hasSpecial;
        if (!isValid) {
          tooltip.show();
        } else {
          tooltip.hide();
        }
        return isValid;
      }

      password.addEventListener("input", function () {
        validatePassword(password.value);
      });

      // Evitar multiple envios y contraseña invalida -----------------------------------
      const form = document.getElementById('form_signup');

      form.addEventListener("submit", function (e) {
        if (!validatePassword(password.value)) {
          e.preventDefault();
          return;
        }

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