@extends('templates.html')
@section('title', 'Reset')

@section('content')
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="text-center p-4 w-75">
            <img src="/img/isotipo.png" alt="Image" class="img-fluid" style="max-height: 122px;">
            <h2 class="fw-bold color_green">REC<span class="color_gold">OILS</span><span class="color_gray">GO</span></h2>
            <h2 class="text-center mb-4">Restablecer Contraseña</h2>
            <p class="h6 text-muted mt-4">Ingrese su nueva contraseña</p>
    
            <form method="POST" action="/your-form-handler">
                <div class="container mt-4" style="width: 450px;">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Nueva Contraseña" required>
                    <input type="password" class="form-control mt-3" id="password_confirmation" name="password_confirmation" placeholder="Confirmar Contraseña" required>
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn_green w-100 py-2 mt-3">Restablecer</button>
                </div>
            </form>
        </div>
    </div>
@endsection
