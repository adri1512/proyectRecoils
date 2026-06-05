@extends('templates.logistic')
@section('title', 'Detalle solicitud - Recoils')

@section('logistic_content')
  <div class="d-flex align-items-center mb-3">
    <a href="{{ route('logistic_pickup_request_index') }}" class="btn btn-link text-muted me-2">
      <i class="fa-solid fa-arrow-left-long"></i>
    </a>
    <h1 class="fw-bold mb-0">Mi solicitud</h1>
  </div>
  <p class="mb-4">Detalle de la solicitud de recolección ({{ $pickupRequests->id }})</p>
 
  <div class="row g-4"> 
    <!-- INFORMACIÓN GENERAL --> 
    <div class="col-lg-6"> 
      <div class="card card_pickup_request border-0 shadow-sm h-100"> 
        <div class="card-body p-0"> 
          <h5 class="fw-bold mb-3"> <i class="fa-solid fa-circle-info me-2 text-primary"></i> Información general </h5> 
          <div class="mb-2"> 
            <small class="text-muted d-block">Cliente</small> 
            <strong>{{ $pickupRequests->user->name ?? '—' }}</strong> 
          </div>
          <div class="row">
            <div class="col-lg-6 mb-2"> 
            <small class="text-muted d-block">Dirección</small> 
            <strong>{{ $pickupRequests->address->address ?? '—' }}</strong> 
          </div> 
          <div class="col-lg-6 mb-2"> 
            <small class="text-muted d-block">Municipio</small> 
            <strong> {{ $pickupRequests->address->town->name ?? '—' }} </strong> 
          </div>
          </div> 
          <div class="mb-2"> 
            <small class="text-muted d-block">Celular</small> 
            <strong>{{ $pickupRequests->phone ?? '—' }}</strong> 
          </div> <div class="mb-2"> 
            <small class="text-muted d-block"> Número de pimpinas </small> 
            <strong> {{ $pickupRequests->container_quantify ?? '—' }} </strong> 
          </div> 
          <div> 
            <small class="text-muted d-block"> Indicaciones adicionales </small> 
            <div class="bg-light rounded"> {{ $pickupRequests->additional_details ?? 'Sin indicaciones.' }} </div> 
          </div> 
        </div>
      </div> 
    </div> 
    <!-- SEGUIMIENTO --> 
    <div class="col-lg-6"> 
      <div class="card card_pickup_request border-0 shadow-sm h-100"> 
        <div class="card-body p-0"> 
          <h5 class="fw-bold mb-3"> <i class="fa-solid fa-route me-2 text-success"></i> Seguimiento </h5>
          <div class="mb-2"> <small class="text-muted d-block mb-1"> Estado actual </small> <span class="badge px-3 py-2 fs-6 @if($pickupRequests->status == 'pendiente') bg-warning text-dark @elseif($pickupRequests->status == 'asignada') bg-info text-dark @elseif($pickupRequests->status == 'en ruta') bg-primary @elseif($pickupRequests->status == 'completada') bg-success @elseif($pickupRequests->status == 'cancelada') bg-danger @else bg-secondary @endif"> {{ ucfirst($pickupRequests->status) }} </span> </div> 
          <div class="mb-2"> 
            <small class="text-muted d-block"> Fecha programada </small> 
            <strong> {{ $pickupRequests->scheduled_date ?? 'Sin asignar' }} </strong> 
          </div> 
          <!-- PRECIO --> 
          <div class="mb-2"> 
            <label class="form-label fw-semibold"> Precio por kilo <small class="text-muted">(opcional)</small> </label> 
            <div class="input-group"> 
              <span class="input-group-text"> $ </span> 
              <input type="text" class="form-control" id="oil_price" name="oil_price" value="{{ $pickupRequests->oil_price ?? '' }}" > 
            </div> 
          </div> 
        </div> 
      </div> 
    </div> 
  </div> 
  <!-- BOTONES --> 
  <div class="d-flex justify-content-end gap-2 mt-4"> 
    <a href="{{ route('logistic_pickup_request_index') }}" class="btn btn-outline-secondary"> Volver </a> 
    <button class="btn btn_green"> Guardar cambios </button> 
  </div>
@endsection



@section('logistic_complement')
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
