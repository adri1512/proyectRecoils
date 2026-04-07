@extends('templates.client')
@section('title', 'Mi espacio - Recoils')

@section('client_content')
  <h1 class="fw-bold">Bienvenido, {{ ucwords(Auth::user()->name) }} 👋</h1>
  <p>Gestiona tus solicitudes de recolección de manera fácil y rápida.</p>

<div class="container-fluid px-3">
  <div class="row g-3 row-cols-2 row-cols-lg-4">
    <div class="col">
      <div class="card_home text-center p-3" style="">
        <div class="card-body">
          <i class="fa-solid fa-clock fa-2x text-warning mb-2"></i>
          <h5 class="card-title mb-1">{{ $pickup_counts['pendientes'] }}</h5>
          <p class="card-text text-muted mb-0">Pendientes</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 rounded-3 text-center" style="background-color:#f8f9fa; border-left:5px solid #17a2b8;">
        <div class="card-body">
          <i class="fa-solid fa-check fa-2x text-info mb-2"></i>
          <h5 class="card-title mb-1">{{ $pickup_counts['asignadas'] }}</h5>
          <p class="card-text text-muted mb-0">Asignadas</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 rounded-3 text-center" style="background-color:#f8f9fa; border-left:5px solid #007bff;">
        <div class="card-body">
          <i class="fa-solid fa-truck fa-2x text-primary mb-2"></i>
          <h5 class="card-title mb-1">{{ $pickup_counts['en_ruta'] }}</h5>
          <p class="card-text text-muted mb-0">En ruta</p>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card shadow-sm border-0 rounded-3 text-center" style="background-color:#f8f9fa; border-left:5px solid #28a745;">
        <div class="card-body">
          <i class="fa-solid fa-check-circle fa-2x text-success mb-2"></i>
          <h5 class="card-title mb-1">{{ $pickup_counts['completadas'] }}</h5>
          <p class="card-text text-muted mb-0">Completadas</p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mt-3">
  {{-- Card: Próximas --}}
  <div class="col-lg-6">
    <div class="card_address">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Solicitudes próximas</h5>

        @if($next_pickups->isNotEmpty())
          <ul class="list-group list-group-flush">
            @foreach($next_pickups as $pickup)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                  <strong>{{ ucfirst($pickup->status) }}</strong> – {{ $pickup->address->address ?? 'Sin dirección' }}
                </span>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($pickup->scheduled_date)->format('d/m/Y') }}
                </small>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted mb-0">No tienes solicitudes próximas por ahora.</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Card: Recientes --}}
  <div class="col-lg-6">
    <div class="card shadow-sm border-0 rounded-3 h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Solicitudes recientes</h5>

        @if($recent_pickups->isNotEmpty())
          <ul class="list-group list-group-flush">
            @foreach($recent_pickups as $pickup)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                  <strong>{{ ucfirst($pickup->status) }}</strong> – {{ $pickup->address->address ?? 'Sin dirección' }}
                </span>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($pickup->scheduled_date)->format('d/m/Y') }}
                </small>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-muted mb-0">Aún no tienes solicitudes recientes.</p>
        @endif
      </div>
    </div>
  </div>
</div>




@endsection

