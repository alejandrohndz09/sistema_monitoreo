<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        NormaSegura
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link rel="stylesheet"
        href="{{ url('https://cdn.jsdelivr.net/npm/sweetalert2@10.3.5/dist/sweetalert2.min.css') }}" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    {{-- <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script> --}}
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
</head>

<body class="bg-gradient-dark"style="padding: 3%; height:100vh">

    <div class="row" style="height:80vh;justify-content: center; align-content: center">



        <div class="col-xl-4">
            <div class="card bg-white">
                <div class="card-header pb-0 pt-3 text-left">
                    <div class="mt-0 d-flex align-items-center justify-content-start text-dark font-weight-bold">
                        <img src="../assets/img/logo-ct.png" height="60px;" style="padding-bottom: 3px">
                        <h2 class="ms-1 text-sm">NormaSegura</h2>
                    </div>
                    
                    <h4 class="mt-2 text-dark text-gradient mb-0" id="titulo">Bienvenido.</h4>
                    <p class="text-sm mb-0">Por favor, ingrese sus credenciales.</p>
                </div>
                <div class="card-body">
                    <form role="form text-left" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="POST"> <!-- Para edición -->
                        <div class="row mb-1">
                            <label>Usuario:</label>
                            <div class="input-group mb-1">
                                <input type="text" name="usuario" value="{{ old('usuario') }}" id="usuario"
                                    class="form-control" placeholder="Usuario" autocomplete="off">
                            </div>
                            @error('usuario')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror


                            <label>Contraseña:</label>
                            <div class="input-group mb-1">
                                <input type="password" name="contraseña" id="contraseña" class="form-control"
                                    placeholder="Contraseña" autocomplete="off">
                            </div>
                            @error('contraseña')
                                <span class="text-danger  text-xs mb-3">{{ $message }}</span>
                            @enderror

                            <div class="form-check form-check-info ms-3">
                                <input class="form-check-input" type="checkbox" id="mostrarClave">
                                <label class="form-check-label" for="mostrarClave">
                                    Mostrar contraseña
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-icon bg-gradient-dark mt-4 mb-0 w-100">Iniciar
                            sesión</button>
                        <div class="d-flex mt-3 justify-content-center">
                            <p style="text-align: end; margin-bottom: 0;"><a class=""
                                    style="text-decoration: none; " data-bs-toggle="modal" data-bs-target="#recuperar"
                                    href="">¿Olvidó su
                                    contraseña?</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="text-align: center">
        <p>©️2024, NormaSegura - Seguridad Informática</p>
    </div>
    <div class="modal fade" id="recuperar" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/recuperarClaveMail" id="formRecuperar" method="post">
                    @csrf
                    <div class="modal-body px-3">
                        <h5 class="mt-1 mb-1">Recuperar clave</h5>
                        <div class="mb-1 d-flex align-items-center text-secondary text-xs">
                            <i class="text-xxs fas fa-circle-info"></i>&nbsp;
                            Se enviará un código de seguridad al correo de la empresa.
                        </div>
                        <hr>
                        <label>Ingrese el nombre de usuario asociado a la empresa:</label>
                        <div class="input-group mb-0">
                            <input type="text" name="usuarioR" id="usuarioR" class="form-control"
                                placeholder="Ej. nombre.user1" autocomplete="off">
                        </div>
                        <span id="error-usuarioR"class="text-danger text-xs ms-1 mb-0"></span>
                        <div class="text-end">
                            <button type="reset" data-bs-dismiss="modal" style="border-color:transparent"
                                class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                <i class="fas fa-undo text-xs"></i>&nbsp;&nbsp;Cancelar</button>
                            <button type="submit" class="btn btn-icon bg-gradient-dark btn-sm mt-4 mb-0">
                                <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Confirmar</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
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
