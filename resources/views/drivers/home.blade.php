@extends('templates.driver')
@section('title', 'Mi espacio - Recoils')

@section('client_content')
  <h1 class="fw-bold">Bienvenido, {{ ucwords(Auth::user()->name) }} 👋</h1>
  <h6 class="text-muted mb-4">{{ \Carbon\Carbon::now()->translatedFormat('l d \d\e F') }}</h6>

  <div class="card_home text-start mb-4">
    <div class="card-body">
      <h5 class="fw-bold mb-2"> Ruta de Recolección</h5>
      {{-- CASO CON RUTA --}}
      <p class="text-muted mb-3">
        Tienes una ruta asignada con <strong>5 paradas</strong>.
      </p>

      <a href="" class="btn btn_green">
        <i class="fa-solid fa-route me-2"></i>Ir a mi ruta
      </a>

      {{-- CASO SIN RUTA --}}
      {{--
      <p class="text-muted mb-0">
        No tienes ruta asignada para hoy 🚫
      </p>
      --}}

    </div>
  </div>

  <!-- 🚚 Estadísticas (DEBAJO) -->
  <div class="row g-3">

    <!-- Total -->
    <div class="col-md-4">
      <div class="card_home">
        <div class="card-body">
          <i class="fa-solid fa-truck fa-2x text-primary mb-2"></i>
          <h5 class="card-title mb-1">5</h5>
          <p class="card-text text-muted mb-0">Total de paradas</p>
        </div>
      </div>
    </div>

    <!-- Completadas -->
    <div class="col-md-4">
      <div class="card_home">
        <div class="card-body">
          <i class="fa-solid fa-check-circle fa-2x text-success mb-2"></i>
          <h5 class="card-title mb-1">2</h5>
          <p class="card-text text-muted mb-0">Completadas</p>
        </div>
      </div>
    </div>

    <!-- Pendientes -->
    <div class="col-md-4">
      <div class="card_home">
        <div class="card-body">
          <i class="fa-solid fa-clock fa-2x text-warning mb-2"></i>
          <h5 class="card-title mb-1">3</h5>
          <p class="card-text text-muted mb-0">Pendientes</p>
        </div>
      </div>
    </div>
  </div>
@endsection
