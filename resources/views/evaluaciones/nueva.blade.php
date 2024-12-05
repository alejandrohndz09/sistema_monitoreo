@extends('layouts.user_type.auth')
@section('styles')
    <link rel="stylesheet" href="<?php echo asset('css/extras.css'); ?>" type="text/css">
@endsection
@section('scripts')
    <script src="{{ asset('js/validaciones/jsEvaluacion.js') }}"></script>
    <script>
        // Función para llenar aleatoriamente los radios
        function llenarRadiosAleatorios() {
            const groups = document.querySelectorAll('.btn-group'); // Seleccionamos todos los grupos de botones
            
            groups.forEach(group => {
                const radios = group.querySelectorAll('input[type="radio"]'); // Seleccionamos todos los radios dentro del grupo
                const randomIndex = Math.floor(Math.random() * radios.length); // Seleccionamos un índice aleatorio
                radios[randomIndex].checked = true; // Marcamos el radio seleccionado aleatoriamente
            });
        }
    
        // Evento para llenar los radios cuando se hace clic en el botón
        document.getElementById('llenarAleatorio').addEventListener('click', llenarRadiosAleatorios);
    </script>
@endsection
@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-lg-12">
                <div class="col-md-12 mb-lg-0 mb-4">
                    <form action="/evaluaciones" id="evaluacionForm" method="POST">
                        @csrf
                        {{-- <div class="card-header pb-0 p-3">
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
                                <div class="text-end ms-2">
                                    <a id="btnAgregar" class="btn bg-gradient-dark mb-0" href="javascript:;"
                                        data-bs-toggle="modal" data-bs-target="#modalForm"><i
                                            class="fas fa-plus"></i>&nbsp;&nbsp;Agregar</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        @php
                            use App\Models\Numeral;
                            $i = 0;
                        @endphp
                        @foreach ($numerales as $numeralPadre)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h4 class="card-title">
                                        {{ $numeralPadre->idNumeral }}.&nbsp;{{ $numeralPadre->nombre }}
                                    </h4>

                                    @if ($numeralPadre->descripcion)
                                        <p class="card-text">{{ $numeralPadre->descripcion }}</p>
                                        <div class="btn-group mb-0" role="group"
                                            aria-label="Evaluar {{ $numeralPadre->nombre }}">
                                            <input type="radio" class="btn-check" name="monitoreo_{{ $i }}"
                                                id="btnradio-si-{{ $numeralPadre->idNumeral }}" value="Sí"
                                                autocomplete="off" {{ old('monitoreo_' . $i) == 'Sí' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-dark"
                                                for="btnradio-si-{{ $numeralPadre->idNumeral }}">Sí</label>

                                            <input type="radio" class="btn-check" name="monitoreo_{{ $i }}"
                                                id="btnradio-no-{{ $numeralPadre->idNumeral }}" value="No"
                                                autocomplete="off" {{ old('monitoreo_' . $i) == 'No' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-dark"
                                                for="btnradio-no-{{ $numeralPadre->idNumeral }}">No</label>

                                            <input type="radio" class="btn-check" name="monitoreo_{{ $i }}"
                                                id="btnradio-parcialmente-{{ $numeralPadre->idNumeral }}"
                                                value="Parcialmente" autocomplete="off"
                                                {{ old('monitoreo_' . $i) == 'Parcialmente' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-dark"
                                                for="btnradio-parcialmente-{{ $numeralPadre->idNumeral }}">Parcialmente</label>
                                        </div>
                                        <span
                                            id="error-monitoreo_{{ $i }}"class="text-danger d-block mt-1"></span>
                                        @php
                                            $i++;
                                        @endphp
                                    @endif

                                    @if ($numeralPadre->numerales->isNotEmpty())
                                        @php
                                            $numeraleshijos = $numeralPadre->numerales;
                                            $numeraleshijos = Numeral::where('idNumeralPadre', $numeralPadre->idNumeral)
                                                ->orderByRaw("CONCAT(LPAD(idNumeral, 10, '0'))")
                                                ->get();

                                        @endphp

                                        @foreach ($numeraleshijos as $numeralHijo)
                                            <div class="ms-4 mb-6">
                                                <h5>
                                                    {{ $numeralHijo->idNumeral }}&nbsp;{{ $numeralHijo->nombre }}
                                                </h5>

                                                @if ($numeralHijo->descripcion)
                                                    <p>{{ $numeralHijo->descripcion }}</p>
                                                    <div class="btn-group mb-0 " role="group"
                                                        aria-label="Evaluar {{ $numeralHijo->nombre }}">
                                                        {{--
                                                            <input type="radio" class="btn-check"
                                                                name="monitoreo_{{ $i }}"
                                                                id="btnradio-si-{{ $numeralHijo->idNumeral }}" value="Sí"
                                                                autocomplete="off"
                                                                {{ old('monitoreo_' . $i) == 'Sí' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-dark mb-0 "
                                                                for="btnradio-si-{{ $numeralHijo->idNumeral }}">Sí</label>

                                                            <input type="radio" class="btn-check"
                                                                name="monitoreo_{{ $i }}"
                                                                id="btnradio-no-{{ $numeralHijo->idNumeral }}" value="No"
                                                                autocomplete="off"
                                                                {{ old('monitoreo_' . $i) == 'No' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-dark mb-0"
                                                                for="btnradio-no-{{ $numeralHijo->idNumeral }}">No</label>

                                                            <input type="radio" class="btn-check"
                                                                name="monitoreo_{{ $i }}"
                                                                id="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}"
                                                                value="Parcialmente" autocomplete="off"
                                                                {{ old('monitoreo_' . $i) == 'Parcialmente' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-dark mb-0"
                                                                for="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}">Parcialmente</label> 
                                                        --}}

                                                        <input type="radio" class="btn-check"
                                                            name="evaluacion[{{ $numeralHijo->idNumeral }}]"
                                                            id="btnradio-si-{{ $numeralHijo->idNumeral }}" value="Sí"
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-dark mb-0"
                                                            for="btnradio-si-{{ $numeralHijo->idNumeral }}">Sí</label>

                                                        <input type="radio" class="btn-check"
                                                            name="evaluacion[{{ $numeralHijo->idNumeral }}]"
                                                            id="btnradio-no-{{ $numeralHijo->idNumeral }}" value="No"
                                                            autocomplete="off">
                                                        <label class="btn btn-outline-dark mb-0"
                                                            for="btnradio-no-{{ $numeralHijo->idNumeral }}">No</label>

                                                        <input type="radio" class="btn-check"
                                                            name="evaluacion[{{ $numeralHijo->idNumeral }}]"
                                                            id="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}"
                                                            value="Parcialmente" autocomplete="off">
                                                        <label class="btn btn-outline-dark mb-0"
                                                            for="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}">Parcialmente</label>                                                      

                                                    </div>
                                                    {{-- <span id="error-monitoreo_{{ $i }}"class="text-danger d-block mt-1 mb-0"></span> --}}
                                                    <span id="error-evaluacion-{{ $numeralHijo->idNumeral }}"
                                                        class="text-danger d-block mt-1 mb-0"></span>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endif
                                            </div>
                                        @endforeach
                                        {{-- @include('evaluaciones.parcial', ['numeral' => $numeralPadre, 'i'=>$i]) --}}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-end">
                            <button id="llenarAleatorio" class="btn btn-secondary btn-lg mt-4 mb-0 me-3" style=" display: none" >Llenar Aleatoriamente</button>

                            <button type="submit" class="btn btn-icon bg-gradient-success btn-sm mt-4 mb-0">
                                <i class="fas fa-check text-xs"></i>&nbsp;&nbsp;Guardar</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
