@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsEvaluacion.js') }}"></script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-12 mb-lg-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 p-3">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <h4 class="mb-0">Registros</h4>
                                </div>
                                <div class="col-6 d-flex align-items-end justify-content-end">
                                    <div class="input-group" style="width: 60%">
                                        <span class="input-group-text text-body"><i class="fas fa-search"
                                                aria-hidden="true"></i></span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                                    </div>
                                    {{-- <div class="text-end ms-2">
                                        <a id="btnAgregar" class="btn bg-gradient-dark mb-0" href="javascript:;"
                                            data-bs-toggle="modal" data-bs-target="#modalForm"><i
                                                class="fas fa-plus"></i>&nbsp;&nbsp;Agregar</a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 9%">
                                            </th>

                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Código
                                            </th>

                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Realizada por:
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Fecha de realización:
                                            </th>
                                            <th
                                                class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                última de actualización:
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
                                        @foreach ($evaluaciones as $c)
                                            <tr class="tr-link" data-id="{{ $c->idEvaluacion }}">
                                                <td style="width: 9%">
                                                    <div
                                                        class="avatar avatar-sm icon bg-gradient-info shadow text-center border-radius-lg">
                                                        <i class="fas fa-file opacity-10 text-sm"></i>
                                                    </div>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $c->idEvaluacion }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        <i class="fas fa-user opacity-10 text-xs"></i>
                                                        {{ $c->usuario->usuario }}</p>
                                                </td>
                                                <td class="px-1">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $c->fecha_creado->format('d/m/y') }}</p>
                                                        <p class="text-xxs  mb-0">({{ $c->fecha_creado->format('h:i:s a') }})
                                                </td>
                                                <td class="px-1">
                                                    @isset($c->fecha_actualizado)
                                                        <p class="text-xs font-weight-bold mb-0">
                                                            {{ $c->fecha_actualizado->format('d/m/y') }}</p>
                                                        <p class="text-xxs  mb-0">
                                                            ({{ $c->fecha_actualizado->format('h:i:s a') }})
                                                        </p>
                                                    @endisset
                                                </td>

                                                <td class="px-1 text-sm">
                                                    <span
                                                        class="badge badge-xs opacity-7 bg-{{ $c->estado == 1 ? 'success' : 'secondary' }} ">
                                                        {{ $c->estado == 1 ? 'activo' : 'inactivo' }}</span>
                                                </td>

                                                <td>
                                                    @if ($c->estado == 1)
                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $c->idEvaluacion }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Deshabilitar"
                                                            class="btnDeshabilitar me-3">
                                                            <i class="fas fa-minus-circle text-secondary"></i>
                                                        </a>
                                                    @else
                                                        <a role="button" data-id="{{ $c->idEvaluacion }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Habilitar"
                                                            class="btnHabilitar me-3">
                                                            <i class="fas fa-arrow-up text-secondary"></i>
                                                        </a>

                                                        <a role="button" data-bs-toggle="modal"
                                                            data-bs-target="#modalConfirm" data-id="{{ $c->idEvaluacion }}"
                                                            data-bs-tt="tooltip" data-bs-original-title="Eliminar"
                                                            class="btnEliminar me-3">
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
