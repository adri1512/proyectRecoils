@extends('templates.driver')
@section('title', 'Mi Ruta - Recoils')

@section('driver_content')
  <h1 class="fw-bold">Bienvenido, {{ ucwords(Auth::user()->name) }} 👋</h1>
  <p>Consulta tu ruta del día y registra cada recolección de forma rápida.</p>

  @if($pickups->isEmpty())
  <div class="alert alert-info">
    No tienes recolecciones asignadas para hoy.
  </div>
  @endif

  @foreach($pickups as $pickup)
  <div class="card_route mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">

      <!-- INFO -->
      <div>
        <h6 class="fw-bold mb-1">
          {{ $pickup->user->name }}
        </h6>

        <small class="text-muted d-block">
          <i class="fas fa-map-marker-alt me-2" style="color: #44ac04"></i> {{ $pickup->address->address }}
        </small>

        <small class="text-muted">
          <i class="fas fa-phone me-2 text-muted"></i> {{ $pickup->phone }}
        </small>
      </div>

      <!-- ACCIONES -->
      <div class="text-end">

        <!-- ESTADO -->
        <span class="badge 
          mb-2 w-100
           @if($pickup->status == 'completada') text-bg-success
  @elseif($pickup->status == 'en ruta') text-bg-primary
  @else bg-warning text-light
  @endif
        ">
          {{ ucfirst($pickup->status) }}
        </span>
        <br>

        <!-- BOTÓN -->
        @if($pickup->status == 'asignada')
          <a href="{{ route('driver_route_stop', $pickup->id) }}" class="btn btn-sm btn-outline-secondary w-100" style="">
            Iniciar
          </a>

        @elseif($pickup->status == 'en ruta')
          <a href="{{ route('driver_route_stop', $pickup->id) }}" class="btn btn-sm btn-outline-secondary text-success w-100">
            Continuar
          </a>

        @else
          <button class="d-none btn btn-sm btn-outline-secondary w-100" disabled>
            Finalizado
          </button>
        @endif

      </div>

    </div>
  </div>
@endforeach

@endsection
