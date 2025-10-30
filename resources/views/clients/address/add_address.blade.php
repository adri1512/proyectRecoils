@extends('templates.client')
@section('title', 'Mis direcciones - Recoils')

@section('client_content')
  <div class="d-flex align-items-center mb-3">
    <a href="{{ route('client_address_index') }}" class="btn btn-link text-muted me-2">
      <i class="fa-solid fa-arrow-left-long"></i>
    </a>
    <h1 class="fw-bold mb-0">Agregar Dirección</h1>
  </div>
  <p class="mb-4">Completa el formulario para registrar una nueva dirección de recolección</p>

  <form class="ps-5 pe-5" id="form_address" method="POST" action="{{ route('client_address_store') }}">
    @csrf
    <!-- NOMBRE DE LA DIRECCIÓN -->
    <div class="mb-3 position-relative">
      <label for="name_address" class="form-label">Nombre dirección</label>
      <input type="text" class="form-control" id="name_address" name="name_address" maxlength="25" title="Has alcanzado el máximo de 25 caracteres" required>
    </div>

    <div class="row g-3 mb-3">
      <!-- SELECT DEPARTAMENTOS -->
      <div class="col-md-6">
        <label for="department_address" class="form-label">Departamento</label>
        <select class="form-select" id="department_address" name="department_address" required>
          <option value="">Seleccione un departamento</option>
          @foreach($departments as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- SELECT MUNICIPIOS -->
      <div class="col-md-6">
        <label for="town_address" class="form-label">Municipio</label>
        <select class="form-select" id="town_address" name="town_address" required>
          <option value="">Seleccione un municipio</option>
        </select>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <!-- BARRIO OPCIONAL -->
      <div class="col-md-6">
        <label for="neighborhood_address" class="form-label">Barrio <small class="text-muted">(opcional)</small></label>
        <input type="text" class="form-control" id="neighborhood_address" name="neighborhood_address" maxlength="25" title="Has alcanzado el máximo de 25 caracteres">
      </div>

      <!-- DIRECCIÓN -->
      <div class="col-md-6">
        <label for="street_address" class="form-label">Dirección</label>
        <input type="text" class="form-control" id="street_address" name="street_address" maxlength="80" title="Has alcanzado el máximo de 80 caracteres" required>
      </div>
    </div>

    <!-- REFERENCIA OPCIONAL -->
    <div class="mb-3">
      <label for="reference_address" class="form-label">Referencia <small class="text-muted">(opcional)</small></label>
      <textarea class="form-control" id="reference_address" name="reference_address" maxlength="100" rows="2" title="Has alcanzado el máximo de 100 caracteres"></textarea>
    </div>

    <!-- BOTON DE AGREGAR DIRECCIÓN -->
    <div class="text-center mt-4 mb-3">
      <button type="submit" class="btn btn_green">
        <i class="fa-regular fa-paper-plane me-2"></i>Agregar Dirección
      </button>
    </div>
  </form>
@endsection

@section('client_complement')
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

      let towns = @json($towns); // Pasamos los municipios que vienen del controller en PHP a JS

      document.getElementById('department_address').addEventListener('change', function() {
        // Escuchamos el id del select del departamento
        let idDepartament = this.value;
        let selectTwon = document.getElementById('town_address');

        // Limpiamos el select de municipios
        selectTwon.innerHTML = '<option value="">Seleccione un municipio</option>';

        // Creamos las opciones que correspondan al departamento selecionado
        towns.forEach(function(m) {
          if (m.id_department == idDepartament) {
            let option = document.createElement('option');
            option.value = m.id;
            option.textContent = m.name;
            selectTwon.appendChild(option);
          }
        });
      });

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

      addLimitTooltip("name_address");
      addLimitTooltip("neighborhood_address");
      addLimitTooltip("street_address");
      addLimitTooltip("reference_address");

      // Evitar multiples envios del form  ----------------------------------------------
      const form = document.getElementById('form_address');
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
