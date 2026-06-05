@extends('templates.driver')
@section('title', 'Mi Recolección - Recoils')

@section('driver_content')
  <!-- HEADER -->
  <div class="d-flex align-items-center mb-2">
    <a href="{{ route('driver_route') }}" class="btn btn-link text-muted me-2">
      <i class="fa-solid fa-arrow-left-long"></i>
    </a>
    <h1 class="fw-bold mb-0">Recolección</h1>
  </div>
  <p class="mb-4">Registra la entrega del cliente</p>

  <!-- INFO DEL CLIENTE -->
  <div class="card_route p-3 mb-3">
    <h6 class="fw-bold mb-1">{{ $pickup->user->name }}</h6>

    <p class="mb-1 text-muted">
      <i class="fas fa-map-marker-alt me-2" style="color:#44ac04;"></i>
      {{ $pickup->address->address }}
    </p>

    <p class="mb-0 text-muted">
      <i class="fas fa-phone me-2"></i>
      {{ $pickup->phone }}
    </p>
  </div>

  <!-- FORMULARIO -->
  <!-- FORMULARIO -->
<form action="#" method="POST" enctype="multipart/form-data">
  @csrf

  <!-- DATOS DE RECOLECCIÓN -->
  <div class="card_route p-3 mb-3">
    <h6 class="fw-bold mb-3">
      <i class="fa-solid fa-oil-can me-2 color_green"></i>
      Datos de recolección
    </h6>

    <div class="row g-3">
      <!-- PIMPINAS LLENAS -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Pimpinas llenas
        </label>

        <input 
          type="number"
          class="form-control"
          name="full_containers"
          id="full_containers"
          placeholder="0"
          min="0"
          required
        >
      </div>

      <!-- KILOS -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Kilos
        </label>

        <input 
          type="number"
          class="form-control"
          name="oil_kilos"
          id="oil_kilos"
          placeholder="0"
          min="0"
          required
        >
      </div>

      <!-- VALOR POR KILO -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Valor por kilo
        </label>

        <div class="input-group">
          <span class="input-group-text">$</span>

          <input 
            type="number"
            class="form-control"
            name="price_per_kilo"
            id="price_per_kilo"
            placeholder="0"
            min="0"
          >
        </div>
      </div>

      <!-- SUBTOTAL -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Subtotal
        </label>

        <div class="input-group">
          <span class="input-group-text">$</span>

          <input 
            type="text"
            class="form-control bg-light"
            id="subtotal"
            readonly
            placeholder="0"
          >
        </div>
      </div>
    </div>
  </div>

  <!-- PIMPINAS VACÍAS -->
  <div class="card_route p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h6 class="fw-bold mb-0">
        <i class="fa-solid fa-box-open me-2 text-secondary"></i>
        Pimpinas vacías
      </h6>

      <div class="form-check m-0">
        <input 
          class="form-check-input"
          type="checkbox"
          id="empty_containers_check"
        >

        <label class="form-check-label small" for="empty_containers_check">
          Registrar
        </label>
      </div>
    </div>

    <div class="row g-3 d-none" id="empty_containers_box">
      <!-- CANTIDAD -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Cantidad
        </label>

        <input 
          type="number"
          class="form-control"
          name="empty_containers"
          id="empty_containers"
          placeholder="0"
          min="0"
        >
      </div>

      <!-- VALOR -->
      <div class="col-6">
        <label class="form-label fw-semibold">
          Valor
        </label>

        <div class="input-group">
          <span class="input-group-text">$</span>

          <input 
            type="number"
            class="form-control"
            name="empty_containers_value"
            id="empty_containers_value"
            placeholder="0"
            min="0"
          >
        </div>
      </div>
    </div>
  </div>

  <!-- TOTAL -->
  <div class="card_route p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <small class="text-muted d-block">
          Total calculado
        </small>

        <h3 class="fw-bold mb-0" id="total_text">
          $0
        </h3>
      </div>

      <i class="fa-solid fa-sack-dollar fa-2x text-success"></i>
    </div>
  </div>

  <!-- OBSERVACIONES -->
  <div class="card_route p-3 mb-3">
    <label class="form-label fw-semibold">
      Observaciones
    </label>

    <textarea 
      class="form-control"
      name="observations"
      rows="3"
      placeholder="Ej: el aceite tenía residuos, se entregaron pimpinas adicionales, etc."
    ></textarea>
  </div>

  <!-- FOTO -->
  <div class="card_route p-3 mb-3">
    <label class="form-label fw-semibold">
      Foto de evidencia
    </label>

    <input 
      type="file"
      class="form-control"
      name="photo"
      accept="image/*"
    >

    <img 
      id="preview"
      class="img-fluid mt-3 d-none"
      style="border-radius:12px;"
    >
  </div>

  <!-- FIRMA -->
  <div class="card_route p-3 mb-4">
    <label class="form-label fw-semibold">
      Firma del cliente
    </label>

    <canvas 
      id="signature-pad"
      style="border:1px solid #ddd;width:100%;height:180px;border-radius:12px;">
    </canvas>

    <input type="hidden" name="signature" id="signature">

    <div class="text-end mt-2">
      <button 
        type="button"
        class="btn btn-sm btn-outline-secondary"
        onclick="clearSignature()"
      >
        Limpiar
      </button>
    </div>
  </div>

  <!-- BOTÓN -->
  <button class="btn btn_green w-100 py-3 fw-semibold">
    Finalizar recolección
  </button>
</form>

  <script>
// =========================
// 📸 PREVIEW DE IMAGEN
// =========================
const photoInput = document.querySelector('input[name="photo"]');
const preview = document.getElementById('preview');

if (photoInput) {
  photoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
    }
  });
}

// =========================
// ✍️ FIRMA DIGITAL
// =========================
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');

// Ajustar tamaño real del canvas
function resizeCanvas() {
  canvas.width = canvas.offsetWidth;
  canvas.height = canvas.offsetHeight;
}
resizeCanvas();

// Variables
let drawing = false;

// =========================
// 🖱️ EVENTOS MOUSE
// =========================
canvas.addEventListener('mousedown', () => drawing = true);

canvas.addEventListener('mouseup', stopDrawing);

canvas.addEventListener('mousemove', draw);

// =========================
// 📱 EVENTOS TOUCH (CELULAR)
// =========================
canvas.addEventListener('touchstart', (e) => {
  e.preventDefault();
  drawing = true;
});

canvas.addEventListener('touchend', (e) => {
  e.preventDefault();
  stopDrawing();
});

canvas.addEventListener('touchmove', (e) => {
  e.preventDefault();
  drawTouch(e);
});

// =========================
// ✍️ DIBUJAR CON MOUSE
// =========================
function draw(e) {
  if (!drawing) return;

  ctx.lineWidth = 2;
  ctx.lineCap = 'round';
  ctx.strokeStyle = '#000';

  ctx.lineTo(e.offsetX, e.offsetY);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(e.offsetX, e.offsetY);
}

// =========================
// ✍️ DIBUJAR CON TOUCH
// =========================
function drawTouch(e) {
  if (!drawing) return;

  const rect = canvas.getBoundingClientRect();
  const touch = e.touches[0];

  const x = touch.clientX - rect.left;
  const y = touch.clientY - rect.top;

  ctx.lineWidth = 2;
  ctx.lineCap = 'round';
  ctx.strokeStyle = '#000';

  ctx.lineTo(x, y);
  ctx.stroke();
  ctx.beginPath();
  ctx.moveTo(x, y);
}

// =========================
// 🛑 DETENER DIBUJO
// =========================
function stopDrawing() {
  drawing = false;
  ctx.beginPath();

  // Guardar firma en input hidden
  document.getElementById('signature').value = canvas.toDataURL();
}

// =========================
// 🧹 LIMPIAR FIRMA
// =========================
function clearSignature() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  document.getElementById('signature').value = '';
}
</script>

@endsection

