@extends('templates.html')
@section('title', 'Nueva contraseña - Recoils')

@section('content')
    <div class="col-12 d-flex align-items-center justify-content-center">
      <div class="text-center p-4 w-75">
      <div class="text-center mb-0" style="user-select: none;">
        <img class="img-fluid mb-0" src="/img/isotipo.png" alt="recoils" style="max-height: 100px;">
        <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span></h2>
      </div>
        
      <p class="h6 text-muted mt-3">Ingrese su nueva contraseña de su cuenta.</p>
      <form method="POST"  id="form_password" action="{{ route('user_password_update') }}">
        @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
          <div class="container mt-4" style="width: 450px;">
            <div class="position-relative">
              <input class="form-control" type="password" id="new_password_user" name="new_password_user" placeholder="Nueva contraseña" data-bs-toggle="tooltip" data-bs-placement="bottom" required>
              <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="new_password_visibility" style="cursor: pointer;"></i>
            </div>
            <div class="position-relative mt-3">
              <input class="form-control" type="password" id="confirm_password_user" name="confirm_password_user" placeholder="Confirma nueva contraseña" data-bs-toggle="tooltip" data-bs-placement="bottom" required>
              <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3" id="confirm_password_visibility" style="cursor: pointer;"></i>
            </div>
            <button type="submit" class="btn btn_green w-100 py-2 mt-3">Enviar</button>
          </div>
      </form>
      
      <p class="mt-3 text-muted">¿Ya tienes una cuenta? <a class="color_green" href="{{ route('user_login_create') }}">Ingresa Aquí</a></p>
    </div>
  </div>


  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // Visibilidad del input de la contraseña -----------------------------------------
      const password = document.getElementById("new_password_user");
      const confirm_password = document.getElementById("confirm_password_user");

      document.getElementById("new_password_visibility").addEventListener("click", () => {
        const icon = document.getElementById('new_password_visibility');

        password.type = password.type === "password" ? "text" : "password";
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      });

      document.getElementById("confirm_password_visibility").addEventListener("click", () => {
        const confirm_icon = document.getElementById('confirm_password_visibility');

        confirm_password.type = confirm_password.type === "password" ? "text" : "password";
        confirm_icon.classList.toggle('fa-eye');
        confirm_icon.classList.toggle('fa-eye-slash');
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
      const form = document.getElementById('form_password');

      form.addEventListener("submit", function (e) {
        if (!validatePassword(password.value)) {
            e.preventDefault();
            return;
        }

        // Validar que coincidan
        if (password.value !== confirm_password.value) {
            e.preventDefault();
            confirm_password.setCustomValidity("Las contraseñas no coinciden");
            confirm_password.reportValidity();
            return;
        } else {
            confirm_password.setCustomValidity("");
        }

        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }
      });
    });
  </script>
@endsection