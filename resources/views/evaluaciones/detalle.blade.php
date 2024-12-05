@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    {{-- <script src="{{ asset('js/tablas.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/validaciones/jsResultado.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Configuración inicial del gráfico
            const ctx = document.getElementById('resultado-chart').getContext('2d');
            const pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                labels: ['C. Organizativos','C. Personal','C. Físico','C. Tecnológico'], // Se llenarán con la respuesta de la API

                    datasets: [{
                        data: [{{$evaluacion->resultadosC[0]*0.4}},{{$evaluacion->resultadosC[1]*0.09}},{{$evaluacion->resultadosC[2]*0.15}},{{$evaluacion->resultadosC[3]*0.37}}, {{100-$evaluacion->resultado}}],
                        backgroundColor: [
                            '#7789a9', '#2176fe', '#2fb835', '#f02229', '#FFF'
                        ],
                        hoverOffset: 4,
                    }]
                },
            });


        });
    </script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row ">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="bg-gradient-dark border-radius-lg h-100 d-flex p-5 justify-content-center">
                                    <i class="ni ni-single-copy-04 opacity-10 text-xl text-white"
                                        style="font-size: 5rem"></i>
                                </div>
                            </div>
                            <div class="col-lg-6 ms-3 mt-5 mt-lg-0">
                                <div class="d-flex flex-column">
                                    <h2 class="font-weight-bolder mb-2">Resultados de la evaluación</h2>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="text-xl mb-0 ms-0">Código:</label>
                                            <p class="mb-2 mt-0 "><i class="fas fa-hashtag text-xs"></i>
                                                &nbsp;{{ $evaluacion->idEvaluacion }}
                                            </p>
                                            <label class="text-xl mb-0 ms-0">Realizada por:</label>
                                            <p class="mb-2 mt-0 "><i class="fas fa-user text-xs"></i>
                                                &nbsp;{{ $evaluacion->usuario->usuario }}
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <label class="text-xl mb-0 ms-0">Fecha de realización:</label>
                                            <p class="mb-2 mt-0 "><i class="fas fa-calendar text-xs"></i>
                                                &nbsp;{{ $evaluacion->fecha_creado->format('d/m/y') }}
                                            </p>
                                            <label class="text-xl mb-0 ms-0">Hora de realización:</label>
                                            <p class="mb-2 mt-0 "><i class="fas fa-clock text-xs"></i>
                                                &nbsp;{{ $evaluacion->fecha_creado->format('h:i:s a') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-6">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="bg-gradient-secondary border-radius-lg  d-flex justify-content-center">
                                        <i class="fas fa-cubes opacity-10 text-xl text-white p-4"
                                            style="font-size: 1.5rem"></i>
                                    </div>
                                </div>
                                <div class="col-lg-7 ps-3">
                                    <h5 class="font-weight-bolder mb-1">Controles organizativos</h5>
                                    <p class="text-sm mb-0">
                                        37 Controles</p>
                                </div>
                                <div class="col-lg-3 ps-3">
                                    <h4 class="font-weight-bolder mb-1">{{number_format($evaluacion->resultadosC[0],1)}}%</h4>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-dark w-{{round($evaluacion->resultadosC[0]/5)*5}}" role="progressbar" aria-valuenow="{{round($evaluacion->resultadosC[0])}}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="bg-gradient-info border-radius-lg  d-flex justify-content-center">
                                        <i class="fas fa-users opacity-10 text-xl text-white p-4"
                                            style="font-size: 1.5rem"></i>
                                    </div>
                                </div>
                                <div class="col-lg-7 ps-3">
                                    <h5 class="font-weight-bolder mb-1">Controles de personas</h5>
                                    <p class="text-sm mb-0">
                                       8 Controles</p>
                                </div>
                                <div class="col-lg-3 ps-3">
                                    <h4 class="font-weight-bolder mb-1">{{$evaluacion->resultadosC[1]}}%</h4>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-dark w-{{round($evaluacion->resultadosC[1]/5)*5}}" role="progressbar" aria-valuenow="{{$evaluacion->resultadosC[1]}}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="bg-gradient-success border-radius-lg  d-flex justify-content-center">
                                        <i class="fas fa-building opacity-10 text-xl text-white p-4"
                                            style="font-size: 1.5rem"></i>
                                    </div>
                                </div>
                                <div class="col-lg-7 ps-3">
                                    <h5 class="font-weight-bolder mb-1">Controles físicos</h5>
                                    <p class="text-sm mb-0">
                                        14 Controles</p>
                                </div>
                                <div class="col-lg-3 ps-3">
                                    <h4 class="font-weight-bolder mb-1">{{number_format($evaluacion->resultadosC[2],2)}}%</h4>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-dark w-{{round($evaluacion->resultadosC[2]/5)*5}}" role="progressbar" aria-valuenow="{{$evaluacion->resultadosC[2]}}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-lg-2">
                                    <div class="bg-gradient-danger border-radius-lg  d-flex justify-content-center">
                                        <i class="fas fa-laptop opacity-10 text-xl text-white p-4"
                                            style="font-size: 1.5rem"></i>
                                    </div>
                                </div>
                                <div class="col-lg-7 ps-3">
                                    <h5 class="font-weight-bolder mb-1">Controles tecnológicos</h5>
                                    <p class="text-sm mb-0">
                                        34 Controles</p>
                                </div>
                                <div class="col-lg-3 ps-3">
                                    <h4 class="font-weight-bolder mb-1">{{number_format($evaluacion->resultadosC[3],2)}}%</h4>
                                    <div class="progress w-100">
                                        <div class="progress-bar bg-dark w-{{round($evaluacion->resultadosC[3]/5)*5}}" role="progressbar" aria-valuenow="{{$evaluacion->resultadosC[3]}}"
                                            aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 h-100">

                <div class="card h-100">
                    <div class="card-body p-3 h-100">
                        <div class="row align-items-center">

                            <h5 class="font-weight-bolder mb-1">Resultado general</h5>
                            <p class="text-sm mb-0">
                                Porcentaje obtenido:</p>

                            <div class="d-flex align-items-center justify-content-center">
                                <h1 class="font-weight-bolder mb-1">{{ number_format($evaluacion->resultado, 2) }}%</h1>
                                <div class="chart ms-2" style="height: 300px">
                                    <canvas id="resultado-chart" class="chart-canvas" height="20px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
