<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Usuario;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use App\Mail\CredencialesMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;



class EmpresaController extends Controller
{

    public function index()
    {
        $empresas = Empresa::all();
        return view('empresas.index')->with('empresas', $empresas);
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'nombre' => 'required|min:3',
            'direccion' => 'required',
            'correo' => 'required|min:3|unique:empresa'

        ], [
            'correo.unique' => 'Este correo ya ha sido ingresado.',
        ]);

        $empresa = new Empresa();
        $empresa->idEmpresa = $this->generarId();
        $empresa->nombre = $request->post('nombre');
        $empresa->direccion = $request->post('direccion');
        $empresa->correo = $request->post('correo');
        $empresa->fecha_creado = now();
        $empresa->estado = 1;
        $empresa->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );

        return response()->json($alert);
    }

    public function show($id=null)
    {
        // Obtener el usuario autenticado
        $usuario = auth()->user();

        // Continuar con la lógica si pasa la validación
        $empresa = Empresa::find($id==null?$usuario->idEmpresa:$id);

        if (!$empresa) {
            abort(404, 'Empresa no encontrada.');
        }

        if ($usuario->rol!=0 &&$usuario->idEmpresa!=$id) {
            abort(403, 'No Tiene permiso para acceder al recurso.');
        }

        return view('empresas.detalle')->with('empresa', $empresa);
    }

    public function edit($id)
    {
        $empresa = Empresa::find($id);
        return response()->json($empresa);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nombre' => 'required|min:3',
            'direccion' => 'required',
            'correo' => 'required|min:3|unique:empresa,correo,' . $id . ',idEmpresa'

        ], [
            'correo.unique' => 'Este correo ya ha sido ingresado.',
        ]);

        $empresa = Empresa::find($id);
        $empresa->nombre = $request->post('nombre');
        $empresa->direccion = $request->post('direccion');
        $empresa->correo = $request->post('correo');
        $empresa->fecha_actualizado = now();
        $empresa->save();


        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.',
        );

        return response()->json($alert);
    }

    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        $empresa->delete();
        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha eliminado exitosamente'
        );
        return response()->json($alert);
    }

    public function baja($id)
    {
        $empresa = Empresa::find($id);
        $empresa->fecha_actualizado = now();
        $empresa->estado = 0;
        $empresa->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($id)
    {
        $empresa = Empresa::find($id);
        $empresa->fecha_actualizado = now();
        $empresa->estado = 1;
        $empresa->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
        return response()->json($alert);
    }

    public function generarId()
    {
        // Obtener el último registro de la tabla "empresa"
        $ultimoEmpresa = Empresa::latest('idEmpresa')->first();

        if (!$ultimoEmpresa) {
            // Si no hay registros previos, comenzar desde EM0001
            $nuevoId = 'EM0001';
        } else {
            // Obtener el número del último idEmpresa
            $ultimoNumero = intval(substr($ultimoEmpresa->idEmpresa, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idEmpresa con ceros a la izquierda
            $nuevoId = 'EM' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function getEmpresas()
    {
        $empresas = Empresa::with('usuarios')->get(); // Ajusta esto según tus necesidades
        return response()->json($empresas);
    }

    public function enviarCredenciales($id)
    {
        $empresa = Empresa::find($id);

        // Validar que no haya usuarios pendientes de activación (estado = 2)
        $usuarioPendiente = $empresa->usuarios()->where('estado', 2)->first();

        if ($usuarioPendiente) {
            // Si existe un usuario pendiente de activación, no permitir la creación de un nuevo usuario
            $alert = [
                'type' => 'info',
                'message' => "Existen usuarios pendientes de activación. No se puede crear uno nuevo aún."
            ];
            
        } else {
            // Determinar el rol del usuario (1 si es el primer usuario, 2 si ya hay más)
            $rol = $empresa->usuarios->isEmpty() ? 1 : 2;

            // Crear el nuevo usuario
            $usuario = new Usuario();
            $usuario->idUsuario = $this->generarIdUsuario();
            $usuario->usuario = $this->generarNombreUsuario($empresa);
            $claveGenerada = Str::random(8);
            $usuario->clave = Hash::make($claveGenerada);
            $usuario->rol = $rol;
            $usuario->fecha_creado = now();
            $usuario->idEmpresa = $empresa->idEmpresa;
            $usuario->token = Hash::make(Str::random(8));
            $usuario->estado = 2;

            // Enviar correo y guardar usuario
            try {
                $mail = new CredencialesMail($usuario, $claveGenerada);
                Mail::to($empresa->correo)->send($mail);

                // Guardar el usuario solo si el correo se envía correctamente
                $usuario->save();

                $alert = [
                    'type' => 'success',
                    'message' => 'Usuario creado y credenciales enviadas exitosamente.',
                ];
            } catch (\Exception $e) {
                // Manejar el error de envío de correo
                return response()->json([
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }


        return response()->json($alert);
    }

    public function generarIdUsuario()
    {
        // Obtener el último registro de la tabla "usuario"
        $ultimoUsuario = Usuario::latest('idUsuario')->first();

        if (!$ultimoUsuario) {
            // Si no hay registros previos, comenzar desde EM0001
            $nuevoId = 'US0001';
        } else {
            // Obtener el número del último idUsuario
            $ultimoNumero = intval(substr($ultimoUsuario->idUsuario, 2));

            // Incrementar el número para el nuevo registro
            $nuevoNumero = $ultimoNumero + 1;

            // Formatear el nuevo idUsuario con ceros a la izquierda
            $nuevoId = 'US' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);
        }

        return $nuevoId;
    }

    public function generarNombreUsuario(Empresa $empresa)
    {
        // Obtener la primera palabra del nombre de la empresa en minúsculas
        $primerPalabraEmpresa = strtolower(explode(' ', $empresa->nombre)[0]);

        // Base del nombre de usuario
        $nombreUsuarioBase = $primerPalabraEmpresa . '.user';

        // Verificar si el nombre de usuario ya existe
        $correlativo = 1;
        $nombreUsuario = $nombreUsuarioBase . $correlativo;

        while (Usuario::where('usuario', $nombreUsuario)->exists()) {
            // Incrementar el correlativo y generar un nuevo nombre de usuario
            $correlativo++;
            $nombreUsuario = $nombreUsuarioBase . $correlativo;
        }

        return $nombreUsuario;
    }
}
