@extends('templates.html')
@section('title', 'Conductor')

@section('content')
  <div class="col-12 d-flex flex-column flex-md-row px-0">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 offcanvas-lg offcanvas-start p-3" id="sidebar" >
      <div class="d-flex align-items-center my-3">
        <img src="/img/isotipo.png" alt="isotipo" class="img-fluid mb-3" style="max-height: 52px; user-select: none;">
        <h2 class="fw-bold color_green ms-2" style="user-select: none;">REC<span class="color_gold">OILS</span></h2>
      </div>

      <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-2">
          <a class="nav-link {{ Request::routeIs('driver_home') ? 'active btn_green' : 'btn_sidebar' }}" @if(!Request::routeIs('driver_home')) href="{{ route('driver_home') }}" @endif><i class="fa-solid fa-house me-2"></i>Inicio</a>
        </li>
        <li class="nav-item mb-2">
          <a class="nav-link {{ Request::routeIs('driver_route') ? 'active btn_green' : 'btn_sidebar' }}" @if(!Request::routeIs('driver_route')) href="{{ route('driver_route') }}" @endif><i class="fa-solid fa-truck me-2"></i>Mi Ruta de Recolección</a>
        </li>
      </ul>

      <a class="sidebar_profile rounded-pill d-flex align-items-center p-2 mb-4 mt-auto" href="{{ route('driver_profile') }}">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="avatar usuario" class="rounded-circle" width="40">
        <div class="ms-2">
          <strong class="text-muted">{{ Auth::user()->name }}</strong><br>
          <small class="text-muted">{{ Auth::user()->email }}</small>
        </div>
      </a>    
    </div>
    
    <!-- Contenido principal -->
    <div class="bg-white flex-fill">
      <!-- Navbar pantallas medianas y pequeñas -->
      <div class="p-2 d-lg-none d-flex shadow-sm" style="background-color: #f8f9fa !important;">
        <button class="btn btn-no-border text-muted" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div class="d-flex align-items-center">
          <h5 class="fw-bold color_green ms-2" style="user-select: none;">REC<span class="color_gold">OILS</span></h5>
        </div>
      </div>

      <div class="p-4">
        @yield('driver_content')
      </div>  
    </div>
  </div>

  @yield('client_complement')
@endsection