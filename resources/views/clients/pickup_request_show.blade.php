@extends('templates.client')
@section('title', 'Mis solicitudes - Recoils')

@section('client_content')
  <div class="d-flex align-items-center mb-3">
    <a href="{{ route('client_pickup_request_index') }}" class="btn btn-link text-muted me-2">
      <i class="fa-solid fa-arrow-left-long"></i>
    </a>
    <h1 class="fw-bold mb-0">Mi solicitud</h1>
  </div>
  <p class="mb-4">Detalle de la solicitud de recolección ({{ $pickupRequests->id }})</p>
  <span class="badge 
            @if($pickupRequests->status == 'pendiente') bg-warning text-dark
            @elseif($pickupRequests->status == 'completada') bg-success
            @elseif($pickupRequests->status == 'cancelada') bg-danger
            @else bg-secondary @endif">
            {{ ucfirst($pickupRequests->status) }}
          </span>

  <!-- <div class="card shadow-sm">
    <div class="card-body">
      <p><strong>Dirección:</strong> {{ $pickupRequests->address->address ?? '—' }}</p>
      <p><strong>Municipio:</strong> {{ $pickupRequests->address->town->name ?? '—' }}</p>
      <p><strong>Celular:</strong> {{ $pickupRequests->phone ?? '—' }}</p>
      <p><strong>Numero de pimpinas:</strong> {{ $pickupRequests->container_quantify ?? '—' }}</p>
      <p><strong>Indicaciones adicionales:</strong> {{ $pickupRequests->additional_details ?? '—' }}</p>
      <p><strong>Estado:</strong> {{ ucfirst($pickupRequests->status) }}</p>
      <p><strong>Fecha sugerida:</strong> {{ $pickupRequests->requested_date }}</p>
      <p><strong>Fecha programada:</strong> {{ $pickupRequests->scheduled_date }}</p>
    </div>
  </div> -->
   <!-- Contenido principal -->
  <div class="row g-4">
    <!-- Información general -->
    <div class="col-md-6">
      <h5 class="fw-semibold text-primary mb-3">Información general</h5>
      <ul class="list-unstyled lh-lg">
        <li><strong class="text-muted">Dirección:</strong> {{ $pickupRequests->address->address ?? '—' }}</li>
        <li><strong class="text-muted">Municipio:</strong> {{ $pickupRequests->address->town->name ?? '—' }}</li>
        <li><strong class="text-muted">Celular:</strong> {{ $pickupRequests->phone ?? '—' }}</li>
        <li><strong class="text-muted">N° de pimpinas:</strong> {{ $pickupRequests->container_quantify ?? '—' }}</li>
        <li><strong class="text-muted">Indicaciones:</strong> {{ $pickupRequests->additional_details ?? '—' }}</li>
      </ul>
    </div>

    <!-- Estado y Fechas -->
    <div class="col-md-6">
      <h5 class="fw-semibold text-primary mb-3">Seguimiento</h5>
      <ul class="list-unstyled lh-lg">
        <li>
          <strong class="text-muted">Estado:</strong>
          
        </li>
        <li><strong class="text-muted">Fecha sugerida:</strong> {{ $pickupRequests->requested_date ?? '—' }}</li>
        <li><strong class="text-muted">Fecha programada:</strong> {{ $pickupRequests->scheduled_date ?? '—' }}</li>
      </ul>
    </div>
  </div>

  <!-- Separador -->
  <hr class="my-5">

  <!-- Botón -->
  <div class="text-center">
    <a href="{{ route('client_address_index') }}" class="btn btn_green px-4">
      Volver a mis solicitudes
    </a>
  </div>
</div>
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
