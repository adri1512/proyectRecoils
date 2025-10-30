<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Titulo de la vista -->
    <title>@yield('title', 'RecoilsGo')</title>
    <!-- Link de css de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link de Fontawesone -->
    <script src="https://kit.fontawesome.com/477bd28e5f.js" crossorigin="anonymous"></script>
    <!-- Link de css propio -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>
    <!-- Contenido de la vista -->
    <div class="container-fluid vh-100" style="user-select: none;">
      <div class="row h-100">
        @yield('content')
      </div>
    </div>

    <!-- Link de js de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  </body>
</html>