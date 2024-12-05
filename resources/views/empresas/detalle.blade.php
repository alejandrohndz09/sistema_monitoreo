@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsEmpresa.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="bg-gradient-dark border-radius-lg h-100 d-flex p-5 justify-content-center">
                                        <i class="fas fa-building opacity-10 text-xl text-white"
                                            style="font-size: 10rem"></i>
                                    </div>

                                </div>
                                <div class="col-lg-6 ms-3 mt-5 mt-lg-0">
                                    <div class="d-flex flex-column h-100">
                                        <h2 class="font-weight-bolder mb-2">{{ $empresa->nombre }}</h2>
                                        <label class="text-xl mb-0 ms-0">Código:</label>
                                        <p class="mb-2 mt-0 "><i class="fas fa-hashtag text-xs"></i>
                                            &nbsp;{{ $empresa->idEmpresa }}
                                        </p>
                                        <label class="text-xl mb-0 ms-0">Correo:</label>
                                        <p class="mb-2 mt-0 "><i class="fas fa-envelope text-xs"></i>
                                            &nbsp;{{ $empresa->correo }}
                                        </p>
                                        <label class="text-xl mb-0 ms-0">Dirección:</label>
                                        <p class="mb-2 mt-0 "><i class="fas fa-map-pin text-xs"></i>
                                            &nbsp;{{ $empresa->direccion }}
                                        </p>
                                        @if (Auth::user()->rol == 0 && $empresa->usuarios->isEmpty())
                                            <div class="mt-3 ms-0">
                                                <button class="btn bg-gradient-dark mb-0 btnCredencial"
                                                    data-id="{{ $empresa->idEmpresa }}"><i
                                                        class="fas fa-user-shield"></i>&nbsp;&nbsp;Enviar
                                                    Credenciales</button>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Registro de Usuarios</h4>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <div class="input-group" style="width: 60%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                    @if (Auth::user()->rol == 0 && !$empresa->usuarios->isEmpty())
                                        <div class="text-end ms-2">
                                            <button class="btn bg-gradient-dark mb-0 btnCredencial"
                                                data-id="{{ $empresa->idEmpresa }}"><i
                                                    class="fas fa-user-shield"></i>&nbsp;&nbsp;Agregar</button>
                                        </div>
                                    @elseif(Auth::user()->rol == 1)
                                        <div class="text-end ms-2">
                                            <a class="btn bg-gradient-dark mb-0" href="javascript:;" data-bs-toggle="modal"
                                                id="btnAgregar" data-bs-target="#modalFormUsuario"><i
                                                    class="fas fa-plus"></i>&nbsp;&nbsp;Agregar</a>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center justify-content-center  mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 3%">
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Código
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Usuario
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Rol
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Creado el:
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Actualizado el:
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Estado
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Acción
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody">
                                        @foreach ($empresa->usuarios as $b)
                                            <tr class="" data-id="{{ $b->idUsuario }}">
                                                <td>
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-user opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->idUsuario }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->usuario }}
                                                    </p>
                                                    <p class="text-xxs mb-0">{{Auth::user()->idUsuario != $b->idUsuario?'':' (Tu)'}}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->rol == 1 ? 'Administrador' : 'Colaborador' }}
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $b->fecha_creado->format('d/m/y') }}</p>
                                                    <p class="text-xxs  mb-0">({{ $b->fecha_creado->format('h:i:s a') }})
                                                    </p>
                                                </td>
                                                <td class="px-1">
                                                    @isset($b->fecha_actualizado)
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $b->fecha_actualizado->format('d/m/y') }}</p>
                                                        <p class="text-xxs  mb-0">
                                                            ({{ $b->fecha_actualizado->format('h:i:s a') }})
                                                        </p>
                                                    @endisset
                                                </td>

                                                <td class="px-1 text-xs">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $b->estado == 1 ? 'success' : ($b->estado == 2 ? 'info' : 'dark') }} ">
                                                        {{ $b->estado == 1 ? 'Activo' : ($b->estado == 2 ? 'Pendiente de activación' : 'Inactivo') }}</span>
                                                </td>
                                                <td>
                                                    @if ($b->estado != 0)
                                                        @if (Auth::user()->rol !=0)
                                                            <a role="button" data-bs-toggle="modal"
                                                                data-bs-target="#modalFormUser"
                                                                data-id="{{ $b->idUsuario }}" data-bs-tt="tooltip"
                                                                data-bs-original-title="Cambiar contraseña"
                                                                class="btnEditar me-2">
                                                                <i class="fas fa-pen text-secondary"></i>
                                                            </a>
                                                        @endif
                                                        @if (Auth::user()->idUsuario != $b->idUsuario)
                                                            <a role="button" data-bs-toggle="modal"
                                                                data-bs-target="#modalConfirm"
                                                                data-id="{{ $b->idUsuario }}" data-bs-tt="tooltip"
                                                                data-bs-original-title="Deshabilitar"
                                                                class="btnDeshabilitar">
                                                                <i class="fas fa-minus-circle text-secondary"></i>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a role="button" data-id="{{ $b->idUsuario }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-2">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $b->idUsuario }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Eliminar"
                                                            class="btnEliminar">
                                                            <i class="fas fa-trash text-secondary"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="pagination" class="d-flex justify-content-center mt-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('empresas.modales')
@endsection
