@extends('templates.logistic')
@section('title', 'Mi Ruta - Recoils')

@section('logistic_content')
  <h1 class="fw-bold">Crear Ruta</h1>
  <p>Selecciona y organiza las recolecciones</p>

  <!-- CONFIGURACIÓN DE LA RUTA -->
  <div class="card_route p-3 mt-2">
    <h6 class="fw-bold mb-2">Configuración de la ruta</h6>

    <div class="row g-3">
      <!-- CONDUCTOR -->
      <div class="col-md-6">
        <select class="form-select">
          <option selected disabled>Seleccionar conductor</option>
          <option>Carlos Pérez</option>
          <option>Juan Gómez</option>
        </select>
      </div>

      <!-- FECHA -->
      <div class="col-md-6">
        <input type="date" class="form-control" min="{{ date('Y-m-d') }}">
      </div>
    </div>
  </div>

  <div class="row g-3 mt-2">

    <!-- 🟡 SOLICITUDES DISPONIBLES -->
    <div class="col-lg-6">
  <div class="card_route p-3 h-100" style="max-height: 250px;">

    <h6 class="fw-bold mb-3">Solicitudes disponibles</h6>

    <div class="card_scroll" id="available-list">

      @forelse($pickups as $pickup)
        <div class="pickup_item mb-2 p-2 border rounded d-flex justify-content-between align-items-center">

          <div>
            <strong>{{ $pickup->user->name }}</strong><br>
            <small class="text-muted">
              {{ $pickup->address->address ?? 'Sin dirección' }}
            </small>
          </div>

          <button class="btn btn-sm btn-outline-dark add-btn" data-id="{{ $pickup->id }}">
            Agregar
          </button>

        </div>
      @empty
        <p class="text-muted">No hay solicitudes disponibles</p>
      @endforelse

    </div>

  </div>
</div>

    <!-- 🔵 RUTA (ORDEN) -->
    <div class="col-lg-6">
  <div class="card_route p-3 h-100" style="max-height: 250px;">

    <h6 class="fw-bold mb-3">Ruta seleccionada</h6>

    <div id="selected-list" class="card_scroll"></div>

    <small class="text-muted">
      * Luego podrás ordenar esto automáticamente
    </small>

  </div>
</div>

    <!-- BOTÓN -->
    <div class="mt-4">
      <button class="btn btn-dark w-100">
        <i class="fa-solid fa-route me-2"></i>
        Crear ruta
      </button>
    </div>
  </div>

@endsection

@section('logistic_complement')
<script>
document.addEventListener('DOMContentLoaded', function () {

  const availableList = document.getElementById('available-list');
  const selectedList = document.getElementById('selected-list');

  document.addEventListener('click', function (e) {

    const addBtn = e.target.closest('.add-btn');
    const removeBtn = e.target.closest('.remove-btn');

    // 👉 AGREGAR
    if (addBtn) {

      const item = addBtn.closest('.pickup_item');

      addBtn.textContent = 'Quitar';
      addBtn.classList.remove('btn-outline-dark');
      addBtn.classList.add('btn-outline-danger');
      addBtn.classList.remove('add-btn');
      addBtn.classList.add('remove-btn');

      selectedList.appendChild(item);

      updateOrder();
    }

    // 👉 QUITAR
    if (removeBtn) {

      const item = removeBtn.closest('.pickup_item');

      removeBtn.textContent = 'Agregar';
      removeBtn.classList.remove('btn-outline-danger');
      removeBtn.classList.add('btn-outline-dark');
      removeBtn.classList.remove('remove-btn');
      removeBtn.classList.add('add-btn');

      availableList.appendChild(item);

      updateOrder();
    }

  });

  function updateOrder() {
    const items = selectedList.querySelectorAll('.pickup_item');

    items.forEach((item, index) => {
      const title = item.querySelector('strong');
      title.innerHTML = (index + 1) + '. ' + title.innerText.replace(/^\d+\.\s/, '');
    });
  }

});
</script>
@endsection