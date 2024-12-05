<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Numeral;
use App\Models\Monitoreo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Mail\CredencialesMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class EvaluacionController extends Controller
{

    public function index()
    {
        $idEmpresa = auth()->user()->idEmpresa;
        $evaluaciones = $evaluaciones = Evaluacion::whereHas('usuario', function ($query) use ($idEmpresa) {
            $query->where('idEmpresa', $idEmpresa); // Filtrar por la empresa del usuario logueado
        })->get();
        return view('evaluaciones.index')->with('evaluaciones', $evaluaciones);
    }

    public function create()
    {
        $numerales = Numeral::whereNull('idNumeralPadre')
            ->with('numerales')->orderByRaw("CONCAT(LPAD(idNumeral, 10, '0'))")
            ->get();
        return view('evaluaciones.nueva', compact('numerales'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'evaluacion.*' => 'required|in:Sí,No,Parcialmente', // Valida que cada numeral tenga una respuesta válida
        ], [
            'evaluacion.*.*.in' => 'La respuesta a la pregunta debe ser \'Sí\', \'No\' o \'Parcialmente\'.',
            'evaluacion.*.required' => 'Aún no ha respondido esta pregunta.',
        ]);


        try {
            // Inicia una transacción
            DB::beginTransaction();
            $idEvaluacion = $this->generarId();
            // Crea una evaluación prin cipal
            $evaluacion = new Evaluacion(); // Ajusta a tu modelo
            $evaluacion->idEvaluacion = $idEvaluacion;
            $evaluacion->estado = 1;
            $evaluacion->idUsuario = auth()->user()->idUsuario;
            $evaluacion->fecha_creado = now(); // Modifícalo según corresponda
            $evaluacion->save();


            $i = 0;
            // Guarda cada respuesta con su pregunta respectiva
            foreach ($validated['evaluacion'] as $idNumeral => $respuesta) {
                $monitoreo = new Monitoreo();
                $monitoreo->idMonitoreo = $this->generarIdMonitoreo($i);
                $monitoreo->idNumeral = $idNumeral;
                $monitoreo->idEvaluacion = $idEvaluacion;
                $monitoreo->evaluacion = $respuesta == 'Sí' ? 1 : ($respuesta == 'No' ? 0 : 0.5);
                $monitoreo->fecha_creado = now();
                $monitoreo->save();
                $i++;
            }

            // Confirma la transacción
            DB::commit();
            $alert = array(
                'type' => 'success',
                'message' => 'Operación exitosa.',
            );
            session()->flash('alert', $alert);
            // Redirige con éxito
            return response()->json(['redirect' => url('/evaluaciones/'.$idEvaluacion)]);
        } catch (\Exception $e) {
            // Revierte la transacción en caso de error
            DB::rollBack();

            // Redirige con mensaje de error
            return response()->json('Error al guardar la evaluación: ' . $e->getMessage(), 500);
        }
    }

    public function show($id = null)
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
        // Obtener el ID de la empresa del usuario autenticado
        $idEmpresa = $user->idEmpresa;
        // Obtener la evaluación con el ID proporcionado
        $evaluacion = Evaluacion::find($id);
        if (!$evaluacion) {
            abort(404, 'Evaluacion no encontrada.');
        }
        // Comprobar el rol del usuario
        if ($user->rol == 1) {
            // Rol 1: Puede acceder a todas las evaluaciones de su empresa
            if ($evaluacion->usuario->idEmpresa !== $idEmpresa) {
                abort(403, 'No tienes permiso para acceder a esta evaluación.');
            }
        } elseif ($user->rol == 2) {
            // Rol 2: Solo puede acceder a las evaluaciones que él mismo ha realizado
            if ($evaluacion->idUsuario !== $user->idUsuario) {
                abort(403, 'No tienes permiso para acceder a esta evaluación.');
            }
        } else {
            // Si el rol no es 1 ni 2, se bloquea el acceso
            abort(403, 'No tienes permiso para acceder a esta evaluación.');
        }

        $evaluacion->resultado=$evaluacion->resultado()*100;
        $evaluacion->resultadosC = array_map(function ($item) {
            $item *= 100; // Multiplicar el valor del total por 100
            return $item;
        }, $evaluacion->resultadoC());

        // Retornar la vista con la evaluación
        return view('evaluaciones.detalle')->with('evaluacion', $evaluacion);
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::find($id);
        $evaluacion->delete();
        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha eliminado exitosamente'
        );
        return response()->json($alert);
    }

    public function baja($id)
    {
        $evaluacion = Evaluacion::find($id);
        $evaluacion->fecha_actualizado = now();
        $evaluacion->estado = 0;
        $evaluacion->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $evaluacion = Evaluacion::find($id);
        $evaluacion->fecha_actualizado = now();
        $evaluacion->estado = 1;
        $evaluacion->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "evaluacion"
        $ultimoEvaluacion = Evaluacion::latest('idEvaluacion')->first();

        if (!$ultimoEvaluacion) {
            // Si no hay registros previos, comenzar desde EM0001
            $nuevoId = 'EV0001';
        } else {
            // Obtener el número del último idEvaluacion
            $ultimoNumero = intval(substr($ultimoEvaluacion->idEvaluacion, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idEvaluacion con ceros a la izquierda
            $nuevoId = 'EV' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getEvaluaciones()
    {
        $idEmpresa = auth()->user()->idEmpresa;
        $evaluaciones = $evaluaciones = Evaluacion::with('usuario')->whereHas('usuario', function ($query) use ($idEmpresa) {
            $query->where('idEmpresa', $idEmpresa); // Filtrar por la empresa del usuario logueado
        })->get();
        return response()->json($evaluaciones);
    }

    public function generarIdMonitoreo($i)
    {
        // Obtener el último registro de la tabla "DetalleVenta"
        $ultimoDetalle = Monitoreo::latest('idMonitoreo')->first();

        if (!$ultimoDetalle) {
            // Si no hay registros previos, comenzar desde DV0001
            $nuevoId = 'MT0001';
        } else {
            // Obtener el número del último idDetalleVenta
            $ultimoNumero = intval(substr($ultimoDetalle->idMonitoreo, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;
            // Formatear el nuevo idVenta con ceros a la izquierda
            $nuevoId = 'MT' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }
}
