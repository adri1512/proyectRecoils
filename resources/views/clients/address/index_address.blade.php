@extends('templates.client')
@section('title', 'Mis direcciones - Recoils')

@section('client_content')
  <h1 class="fw-bold">Mis Direcciones</h1>
  <p class="mb-4">Administra tus direcciones de recolección</p>
  <a class="btn btn_green mb-4" href="{{ route('client_address_create') }}">
    <i class="fas fa-plus me-2"></i> Agregar Dirección
  </a>

  <!-- Tarjeta de las direcciones -->
  <div class="row">
    @foreach ($addresses as $address)
      <div class="col-lg-6 px-4 mb-4">
        <div class="card_address p-3 h-100 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-start">
            <h5 class="fw-bold mb-0 text-truncate">@if ($address->is_main)<i class="fa-solid fa-star" style="color: #d4ac04"></i>@endif{{ ucfirst($address->name) }}</h5>
            <div class="d-flex gap-0">
              <a class="btn btn_address" href="{{ route('client_address_edit', $address->id) }}"><i class="fas fa-edit"></i></a>
              @if (!$address->is_main)
                <button class="btn btn_address" type="button" data-bs-toggle="modal" data-bs-target="#modal_delete_address" data-id="{{ $address->id }}"> <i class="fas fa-trash"></i> </button>
              @endif
            </div>
          </div>
          <div class="card_address_body">
            <p class="mb-1 text-break"> <i class="fas fa-map-marker-alt fa-lg ms-1 me-2" style="color: #44ac04"></i> {{ $address->address }}</p>
            <p class="mb-0">{{ $address->town->department->name  . " - " . $address->town->name }}</p>
          </div>
          <small class="text-muted mt-auto">Agregada el {{ $address->created_at->format('d M Y') }}</small>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Modal eliminar dirección -->
  <div class="modal fade" id="modal_delete_address" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="POST" id="form_delete_address">
          @csrf
          @method('DELETE')
          <div class="modal-body text-start">
            <h3 class="fw-bold mt-3 mb-3">Eliminar registro</h3>
            <p>¿Estás seguro que deseas eliminar esta dirección?</p>
            <div class="d-flex justify-content-end gap-2 mt-3 mb-3">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-danger">Eliminar</button>
            </div>
          </div>
        </form>
      </div>
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
    document.addEventListener("DOMContentLoaded", function () {
      // Colocar el id de la dirección a eliminar en el form ----------------------------
      const modalDelete = document.getElementById("modal_delete_address");
      const form = document.getElementById("form_delete_address");

      modalDelete.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget; // El botón que abrió el modal
        const id = button.getAttribute("data-id"); // El ID que pasaste en el botón

        // Cambiar la acción del form dinámicamente
        // form.action = `/cliente/direcciones/eliminar/${id}`;

        // URL base con un "id" falso
        let action = "{{ route('client_address_delete', 'id') }}";

        // Reemplazamos el "id" por el id real
        action = action.replace('id', id);

        // Asignamos la acción al form
        form.action = action;
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



