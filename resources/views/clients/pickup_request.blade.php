@extends('templates.client')
@section('title', 'Solicitar recolección - Recoils')

@section('client_content')
  <h1 class="fw-bold">Solicitar recolección</h1>
  <p class="mb-4">Aquí podrás hacer solicitudes de recolección.</p>
  <form class="ps-5 pe-5" id="form_pickup_request" method="POST" action="{{ route('client_pickup_request_store') }}">
    @csrf
    <div class="row g-3 mb-3">
      <!-- SELECT DIRECCIONES -->
      <div class="col-md-6">
        <label class="form-label" for="address_pickup">Dirección de recolección</label>
        <select class="form-select" id="address_pickup" name="address_pickup" required>
          <option value="">Seleccione una dirección</option>
          @foreach ($addresses as $address)
            <option value="{{ $address->id }}">📍 {{ $address->address }} — {{ $address->town->name }}, {{ $address->town->department->name  }}</option>
          @endforeach
        </select>
      </div>

      <!-- NÚMERO DE CONTACTO -->
      <div class="col-md-6">
        <label class="form-label" for="phone_pickup">Número de contacto</label>
        <input class="form-control" type="text" id="phone_pickup" name="phone_pickup" pattern="[0-9]*" maxlength="15" title="Has alcanzado el máximo de 15 caracteres" required>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <!-- FECHA SUGERIDA DE RECOLECCIÓN -->
      <div class="col-md-6">
        <label class="form-label" for="requested_date_pickup">Fecha sugerida de recolección</label>
        <input class="form-control" type="date" id="requested_date_pickup" name="requested_date_pickup" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" max="{{ \Carbon\Carbon::now()->addDays(30)->format('Y-m-d') }}" required>
      </div>

      <!-- NUMERO DE PIMPINAS -->
      <div class="col-md-6">
        <label class="form-label" for="container_quantify_pickup">Número de Pimpinas</label>
        <input class="form-control" type="text" id="container_quantify_pickup" name="container_quantify_pickup" pattern="[0-9]*" maxlength="3" title="Has alcanzado el máximo de 3 caracteres"  required>
      </div>
    </div>

    <!-- INDICACIONES ADICIONALES-->
    <div class="mb-3">
      <label class="form-label" for="notes_pickup">Indicaciones adicionales <small class="text-muted">(opcional)</small></label>
      <textarea class="form-control" id="notes_pickup" name="notes_pickup" maxlength="100" rows="2" title="Has alcanzado el máximo de 100 caracteres"></textarea>
    </div>

    <!-- BOTON DE AGREGAR DIRECCIÓN -->
    <div class="text-center mt-4 mb-3">
      <button class="btn btn_green" type="submit">
        <i class="fa-regular fa-paper-plane me-2"></i>Enviar solicitud
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
      // Inputs numericos solo reciben numeros ------------------------------------------
      document.querySelectorAll("#phone_pickup, #container_quantify_pickup").forEach(input => 
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

      addLimitTooltip("phone_pickup");
      addLimitTooltip("container_quantify_pickup");
      addLimitTooltip("notes_pickup");

      // Evitar multiples envios del form  ----------------------------------------------
      const form = document.getElementById('form_pickup_request');
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

