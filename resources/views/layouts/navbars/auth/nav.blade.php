<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <!-- Primer nivel: página principal -->
                <li class="breadcrumb-item text-sm">
                    <a class="opacity-5 text-dark" href="{{ url('/') }}">Inicio</a>
                </li>

                <!-- Generar los demás niveles dinámicamente -->
                @php
                    $segments = Request::segments(); // Obtener segmentos de la URL
                    $url = ''; // Para construir las URLs de los enlaces del breadcrumb
                @endphp

                @foreach ($segments as $index => $segment)
                    @php
                        // Construir la URL acumulando los segmentos
                        $url .= '/' . $segment;
                    @endphp

                    @if ($index + 1 < count($segments))
                        {{-- Si no es el último segmento --}}
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="{{ url($url) }}">
                                {{ ucwords(str_replace('-', ' ', $segment)) }} {{-- Reemplazar '-' por espacios --}}
                            </a>
                        </li>
                    @else
                        {{-- Último segmento (el actual, sin enlace) --}}
                        <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">
                            {{ ucwords(str_replace('-', ' ', $segment)) }}
                        </li>
                    @endif
                @endforeach
            </ol>

            <!-- Mostrar el título de la página basado en el último segmento con un botón de retroceso -->
            <div class="d-flex align-items-center">
                @if (!Request::is('inicio'))
                    @php
                        // Construir la URL del penúltimo segmento
                        $previousUrl = url('/' . implode('/', array_slice($segments, 0, -1)));
                    @endphp
                    <a href="{{ $previousUrl }}"
                        class="btn btn-icon-only btn-rounded bg-transparent btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                @endif
                <h4 class="font-weight-bolder  text-capitalize">
                    {{ ucwords(str_replace('-', ' ', end($segments))) }}
                </h4>
            </div>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <ul class="navbar-nav  justify-content-end d-flex flex-column">
                <div class="d-flex mb-0 align-items-center text-sm font-weight-bold text-dark">
                    <i class="fa fa-user me-sm-1"></i>&nbsp;{{ Auth::user()->usuario }}
                </div>
                <li class="nav-item d-flex align-items-center text-sm">
                    <a href="javascript:;" data-bs-toggle="modal"
                        data-bs-target="#modalLogout"data-bs-toggle=""="nav-link text-body font-weight-bold px-0">
                        <i class="fa fa-sign-out-alt me-sm-1"></i>
                        <span class="d-sm-inline d-none ">Cerrar sesión</span>
                    </a>
                    <div class="modal fade" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="modal-form"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-mg"
                            role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0 ">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h3 class="text-dark">Confirmar operación</h3>
                                            <p id="dialogo" class="mb-0">Está a punto de cerrar sesión. ¿Desea
                                                continuar?</p>
                                        </div>
                                        <div class="card-body">
                                            <form role="form text-left" action="{{ url('/logout') }}" method="POST">
                                                @csrf
                                                <input type="hidden" id="methodC">
                                                <div class="text-end">
                                                    <button type="reset" data-bs-dismiss="modal"
                                                        style="border-color:transparent"
                                                        class="btn btn-outline-dark btn-sm mt-4 mb-0">
                                                        <i class="fas fa-times text-xs"></i>&nbsp;&nbsp;No</button>
                                                    <button type="submit"
                                                        class="btn btn-icon bg-gradient-danger btn-sm mt-4 mb-0">
                                                        <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Sí</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
