@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    {{-- <script src="{{ asset('js/tablas.js') }}"></script>
    <script src="{{ asset('js/validaciones/jsActivo.js') }}"></script> --}}
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row ">
            <div class="col-lg-12">
                <div class="row ">
                    @if (Auth::user()->rol == 0)
                        <div class="col-xl-6 mb-xl-0 mb-4">
                            <div class="card card-link">
                                <a href="/empresas" class="stretched-link"></a>
                                <div class="card-header mx-4 p-3 text-center">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="fas fa-building opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h4 class="text-center mb-0">Empresas</h4>
                                    <hr class="horizontal dark my-1">
                                    <i class="fas fa-circle-info text-xs"></i>
                                    <span class="text-xs">Gestión de la información de las empresas.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 mb-4">
                            <div class="card card-link">
                                <a href="/usuarios" class="stretched-link"></a>
                                <div class="card-header mx-4 p-3 text-center">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                                        <i class="fas fa-users opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h4 class="text-center mb-0">Usuarios</h4>
                                    <hr class="horizontal dark my-1">
                                    <i class="fas fa-circle-info text-xs"></i>
                                    <span class="text-xs">Gestión de usuarios y envíos de credenciales.</span>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->rol == 1)
                        <div class="col-md-6 col-sm-3 mb-4">
                            <div class="card card-link">
                                <a href="/nueva-evaluacion" class="stretched-link"></a>
                                <div class="card-header mx-4 p-3 text-center">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="fas fa-plus opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h4 class="text-center mb-0">Nueva evaluación</h4>
                                    <hr class="horizontal dark my-1">
                                    <i class="fas fa-circle-info text-xs"></i>
                                    <span class="text-xs">Realiza una nueva evaluación.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-3 mb-4">
                            <div class="card card-link">
                                <a href="/evaluaciones" class="stretched-link"></a>
                                <div class="card-header mx-4 p-3 text-center">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg">
                                        <i class="ni ni-lg ni-single-copy-04 mt-2 opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h4 class="text-center mb-0">Evaluaciones</h4>
                                    <hr class="horizontal dark my-1">
                                    <i class="fas fa-circle-info text-xs"></i>
                                    <span class="text-xs">Historial de los registros de evaluaciones.</span>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->rol == 2)
                        <div class="col-md-12 col-sm-3 mb-4">
                            <div class="card card-link">
                                <a href="/nueva-evaluacion" class="stretched-link"></a>
                                <div class="card-header mx-4 p-3 text-center">
                                    <div
                                        class="icon icon-shape icon-lg bg-gradient-dark shadow text-center border-radius-lg">
                                        <i class="fas fa-plus opacity-10"></i>
                                    </div>
                                </div>
                                <div class="card-body pt-0 p-3 text-center">
                                    <h4 class="text-center mb-0">Nueva evaluación</h4>
                                    <hr class="horizontal dark my-1">
                                    <i class="fas fa-circle-info text-xs"></i>
                                    <span class="text-xs">Realiza una nueva evaluación.</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
