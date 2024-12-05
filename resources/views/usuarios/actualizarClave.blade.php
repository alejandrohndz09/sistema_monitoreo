<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('../assets/img/logo-ct.png')}}">
    <title>
        NormaSegura
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ url('https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.css') }}" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script> --}}
    {{-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> --}}
    <link href="{{ asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- CSS Files -->
    @yield('styles')
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-dark"style="padding: 3%; height:100vh">

    <div class="row" style="height:80vh;justify-content: center; align-content: center">



        <div class="col-xl-4">
            <div class="card bg-white">
                <div class="card-header pb-0 pt-3 text-left">
                    <div class="mt-0  mb-2 d-flex align-items-center justify-content-start text-dark font-weight-bold">
                        <img src="{{asset('../assets/img/logo-ct.png')}}" height="60px;" style="padding-bottom: 3px">
                        <h2 class="ms-1 text-sm">NormaSegura</h2>
                    </div>

                    <div style="display: flex; flex-direction: column">
                        <h4 style="padding: -5px 0px !important; margin-bottom:0px ">
                            Bienvenido {{ $usuario->usuario }}
                        </h4>
                        @if ($opcion==2)
                            <p class="text-sm mb-0">Debes cambiar tu contraseña temporal.
                            </p>
                        @else
                            <p class="text-sm mb-0">Debes ingresar una nueva contraseña.
                            </p>
                        @endif

                    </div>
                </div>
                <div class="card-body">
                    <form role="form text-left" method="POST" action="{{ url('/verificar-cambio') }}">
                        @csrf
                        <div class="row mb-1">

                            <label>Nueva contraseña:</label>
                            <div class="input-group mb-1">
                                <input type="text" name="clave1" value="{{ old('clave1') }}" id="clave1"
                                    class="form-control" placeholder="Ingrese su contraseña." autocomplete="off">
                            </div>
                            @error('clave1')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror

                            <label class="mt-1">Confirmar contraseña:</label>
                            <div class="input-group mb-1">
                                <input type="text" name="clave2" value="{{ old('clave2') }}" id="clave2"
                                    class="form-control" placeholder="Confirme su contraseña." autocomplete="off">
                            </div>
                            @error('clave2')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror

                            <input type="hidden" class="inputField" name="token" id="token"
                                value="{{ $token }}">
                            @error('token')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror


                            <button type="submit" class="btn btn-icon bg-gradient-dark mt-4 mb-0 w-100">
                                <i class="svg-icon fas fa-check" style="color:#fff"></i>&nbsp;
                                Efectuar cambio e ingresar</button>
                            <div class="d-flex mt-3 justify-content-center align-items-center">

                                <p style="text-align: end; margin-bottom: 0;"><a href="/login"
                                        style="text-decoration: none; ">Volver al inicio</a>
                                </p>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2" style="text-align: center">
        <p>©️2024, NormaSegura - Seguridad Informática</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="{{ url('https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js') }}"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>

    <script src="{{ url('https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.js') }}"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            // iconColor: 'white',
        });
    </script>
    <script src="{{ asset('/js/validaciones/jsLogin.js') }}"></script>

    @if (session('alert'))
        <script>
            Toast.fire({
                icon: "{{ session('alert')['type'] }}",
                title: "{{ session('alert')['message'] }}",
            });
        </script>
        @php
            session()->forget('alert');
        @endphp
    @endif

</body>

</html>
