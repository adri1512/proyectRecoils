@extends('templates.driver')
@section('title', 'Mi Recolección - Recoils')

@section('driver_content')
  <!-- HEADER -->
  <div class="mb-3">
    <h5 class="fw-bold mb-1">Recolección</h5>
    <small class="text-muted">Registra la entrega del cliente</small>
  </div>

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
  <form action="#" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- CANTIDAD -->
    <div class="card_route p-3 mb-3">
      <label class="form-label fw-semibold">Cantidad recolectada</label>
      <input type="number" class="form-control" name="quantity" placeholder="Ej: 3" required>
    </div>

    <!-- FOTO -->
    <div class="card_route p-3 mb-3">
      <label class="form-label fw-semibold">Foto de evidencia</label>
      <input type="file" class="form-control" name="photo" accept="image/*">

      <!-- preview -->
      <img id="preview" class="img-fluid mt-2 d-none" style="border-radius:8px;">
    </div>

    <!-- FIRMA -->
    <div class="card_route p-3 mb-3">
      <label class="form-label fw-semibold">Firma del cliente</label>

      <canvas id="signature-pad"
        style="border:1px solid #ddd; width:100%; height:180px; border-radius:8px;">
      </canvas>

      <input type="hidden" name="signature" id="signature">

      <div class="text-end mt-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearSignature()">
          Limpiar
        </button>
      </div>
    </div>

    <!-- BOTÓN FINAL -->
    <button class="btn btn-dark w-100 py-2">
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

