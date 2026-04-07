@extends('templates.client')
@section('title', 'Perfil - Recoils')

@section('client_content')
  <!-- <div class="card mx-auto shadow" style="max-width: 700px;">

        <div class="card-body p-5">
          <h3 style="color: #44ac04;">Perfil de Usuario</h3>
          <div class="row mb-3">
            <div class="col-md-6">
              <p class="fw-semibold mb-1">Nombre completo</p> 
              <p class="profile_p">{{ Auth::user()->name }}</p>
            </div>
            <div class="col-md-6">
              <p class="fw-semibold mb-1">Identificación</p> 
              <p class="profile_p">{{ Auth::user()->number_id }}</p>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <p class="fw-semibold mb-1">Correo electrónico</p> 
              <p class="profile_p">{{ Auth::user()->email }}</p>
            </div>
            <div class="col-md-6">
              <p class="fw-semibold mb-1">Rol</p>
              <p class="profile_p">{{ Auth::user()->role }}</p>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <p class="fw-semibold mb-1">Miembro desde</p>
              <p class="profile_p">{{ Auth::user()->created_at->format('d M Y') }}</p>
            </div>
          </div>
         
          
          <form action="{{ route('user_logout') }}" method="POST" class="d-flex justify-content-center mt-5 pb-0">
            @csrf
            <button type="submit" class="btn btn_green">Cerrar Sesión</button>
          </form>
        </div>

        </div> -->

<div class="container py-2">
  <div class="d-flex justify-content-center">
    <div class="card_profile p-5 w-100" style="max-width: 900px;">
      
      <!-- Sección superior: Avatar + nombre/rol -->
      <div class="d-flex flex-column flex-md-row align-items-center mb-5 text-break">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=150&background=44ac04&color=fff" 
             alt="Avatar" 
             class="rounded-circle me-md-4 mb-3 mb-md-0 flex-shrink-0" 
             style="width: 150px; height: 150px; object-fit: cover;">
        
        <div class="text-center text-md-start w-100">
          <h1 class="mb-1 text-truncate" style="max-width: 100%;">{{ ucwords(Auth::user()->name) }}</h1>
          <p class="text-muted mb-0 text-wrap">{{ ucwords(Auth::user()->role) }}</p>
        </div>
      </div>

      <!-- Información adicional -->
      <div class="row g-4">
        <div class="col-md-6">
          <div class="d-flex align-items-center text-break">
            <i class="fa-solid fa-id-card fa-lg text-info me-3"></i>
            <div>
              <p class="fw-semibold mb-0">Identificación</p>
              <p class="text-muted mb-0">{{ Auth::user()->document_user }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center text-break">
            <i class="fa-solid fa-envelope fa-lg text-success me-3"></i>
            <div>
              <p class="fw-semibold mb-0">Correo electrónico</p>
              <p class="text-muted mb-0 text-break">{{ Auth::user()->email }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center text-break">
            <i class="fa-solid fa-user-tag fa-lg text-warning me-3"></i>
            <div>
              <p class="fw-semibold mb-0">Tipo de persona</p>
              <p class="text-muted mb-0">{{ ucwords(Auth::user()->person_type) }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center">
            <i class="fa-solid fa-calendar-alt fa-lg text-danger me-3"></i>
            <div>
              <p class="fw-semibold mb-0">Miembro desde</p>
              <p class="text-muted mb-0">{{ Auth::user()->created_at->format('d M Y') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Botones -->
      <div class="mt-5 pt-4 text-center">
        <button class="btn btn_green_border me-2">Editar Perfil</button>
        <form class="d-inline" method="POST" action="{{ route('user_logout') }}">
          @csrf
          <button type="submit" class="btn btn_green">Cerrar Sesión</button>
        </form>
      </div>

    </div>
  </div>
</div>



@endsection

