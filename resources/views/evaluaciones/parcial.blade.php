@php
    use App\Models\Numeral;

    $numeraleshijos = $numeral->numerales;
    $numeraleshijos = Numeral::where('idNumeralPadre', $numeral->idNumeral)
        ->orderByRaw("CONCAT(LPAD(idNumeral, 10, '0'))")
        ->get();

@endphp

@foreach ($numeraleshijos as $numeralHijo)
    <div class="ms-4">
        <h5>
            {{ $numeralHijo->idNumeral }}&nbsp;{{ $numeralHijo->nombre }}
        </h5>

        @if ($numeralHijo->descripcion)
            <p>{{ $numeralHijo->descripcion }}</p>
            <div class="btn-group mb-3 " role="group" aria-label="Evaluar {{ $numeralHijo->nombre }}">
                <input type="radio" class="btn-check" name="monitoreo_{{$i}}"
                    id="btnradio-si-{{ $numeralHijo->idNumeral }}" value="Sí" autocomplete="off"
                    {{ old('monitoreo_' . $i) == 'Sí' ? 'checked' : '' }}>
                <label class="btn btn-outline-dark " for="btnradio-si-{{ $numeralHijo->idNumeral }}">Sí</label>

                <input type="radio" class="btn-check" name="monitoreo_{{$i}}"
                    id="btnradio-no-{{ $numeralHijo->idNumeral }}" value="No" autocomplete="off"
                    {{ old('monitoreo_' . $i) == 'No' ? 'checked' : '' }}>
                <label class="btn btn-outline-dark" for="btnradio-no-{{ $numeralHijo->idNumeral }}">No</label>

                <input type="radio" class="btn-check" name="monitoreo_{{$i}}"
                    id="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}" value="Parcialmente" autocomplete="off"
                    {{ old('monitoreo_' . $i) == 'Parcialmente' ? 'checked' : '' }}>
                <label class="btn btn-outline-dark"
                    for="btnradio-parcialmente-{{ $numeralHijo->idNumeral }}">Parcialmente</label>
            </div>
            @error('monitoreo_' . $i)
                <span class="text-danger d-block mt-1">{{ $message }}</span>
            @enderror
            @php
                $i++;
            @endphp
        @endif

        @if ($numeralHijo->numerales->isNotEmpty())
            @include('evaluaciones.parcial', ['numeral' => $numeralHijo, 'i'=>$i])
        @endif
    </div>
@endforeach
