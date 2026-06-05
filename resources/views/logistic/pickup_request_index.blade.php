@extends('templates.logistic')
@section('title', 'Mis solicitudes - Recoils')

@section('logistic_content')
  <h1 class="fw-bold">Mis solicitudes</h1>
  <p class="mb-4">Aquí podrás ver las solicitudes de recolección realizadas.</p>

  <!-- FILTRO POR ESTADO -->
  <div class="row mb-3">
    <div class="col-md-4">
      <form method="GET" action="{{ route('logistic_pickup_request_index') }}" class="d-flex">
        <select name="status" class="form-select me-2" onchange="this.form.submit()">
          <option value="" {{ request('status') == '' ? 'selected' : '' }}>Todas</option>
          <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
          <option value="asignada" {{ request('status') == 'asignada' ? 'selected' : '' }}>Asignada</option>
          <option value="en ruta" {{ request('status') == 'en ruta' ? 'selected' : '' }}>En ruta</option>
          <option value="completada" {{ request('status') == 'completada' ? 'selected' : '' }}>Completada</option>
          <option value="cancelada" {{ request('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>

        <a href="{{ route('logistic_pickup_request_index') }}" class="btn btn-outline-secondary">Limpiar</a>
      </form>
    </div>
  </div>

<table class="table align-middle" style="width:100%;table-layout:auto;">
  <thead>
    <tr style="color:#000;">
      <th style="font-weight: normal;">Id</th>
      <th style="font-weight: normal;">Cliente</th>
      <th style="font-weight: normal;">Dirección</th>
      <th style="font-weight: normal;">Municipio</th>
      <th style="font-weight: normal;">Estado</th>
      <th style="font-weight: normal;">Detalle</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($pickupRequests as $p)
    <tr>
      <td style="border:none;">{{ $p->id }}</td>
      <td style="border:none;">{{ $p->user->name ?? ' — ' }}</td>
      <td style="border:none;">{{ $p->address->address ?? ' — ' }}</td>
      <td style="border:none;">{{ $p->address->town->name }}, {{ $p->address->town->department->name  }}</td>
      <td style="border:none;">
        <span class="badge 
          @if ($p->status === 'pendiente') bg-warning text-dark
          @elseif ($p->status === 'asignada') bg-info text-dark
          @elseif ($p->status === 'en ruta') bg-primary
          @elseif ($p->status === 'completada') bg-success
          @elseif ($p->status === 'cancelada') bg-danger
          @endif">
          {{ ucfirst($p->status) }}
        </span>
      </td>
      <td style="border:none;padding:1rem;">
        <a class="color_gray" href="{{ route('logistic_pickup_request_show', $p->id) }}"><i class="fa-solid fa-eye"></i></a>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4" class="text-center text-muted" style="border:none;">No tienes solicitudes registradas.</td>
    </tr>
    @endforelse
  </tbody>
</table>
  <!-- Controles de paginación -->
  <div class="d-flex justify-content-center mt-3">
    {{ $pickupRequests->links('pagination::bootstrap-5') }}
  </div><style>.small.text-muted{display:none!important;}</style>

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
