<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Mail\CredencialesMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = auth()->user(); // Obtén el usuario autenticado

        if ($usuario->rol == 0) {
            // Si el rol es 0, obtiene todos los registros
            $usuarios = Usuario::all();
        } else {
            // Si no, filtra por la empresa asociada al usuario
            $usuarios = Usuario::where('idEmpresa', $usuario->idEmpresa)->get();
        }

        return view('usuarios.index')->with('usuarios', $usuarios);
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'clave' => [
                'required',
                'string',
                'min:8', // Mínimo 8 caracteres
                'regex:/[A-Z]/', // Al menos una letra mayúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[@$!%*?&]/', // Al menos un carácter especial
            ],
            'clave1' => 'required|same:clave',
        ], [
            'clave.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'clave.regex' => 'La contraseña debe incluir el uso de carácteres numéricos, en mayúscula y un carácteres especiales (@$!%*?&).',
            '*.required' => 'Campo obligatorio.',
            'clave1.same' => 'Las contraseñas no coinciden.',
        ]);

        // Crear un nuevo usuario
        $empresa = Empresa::find(auth()->user()->idEmpresa);

        if (!$empresa) {
            return response()->json([
                'type' => 'error',
                'message' => 'La empresa proporcionada no existe.',
            ], 404);
        }

        $usuario = new Usuario();
        $usuario->idUsuario = $this->generarIdUsuario();
        $usuario->usuario = $this->generarNombreUsuario($empresa);
        $usuario->clave = Hash::make($request->clave);
        $usuario->rol = 2;
        $usuario->fecha_creado = now();
        $usuario->idEmpresa = $empresa->idEmpresa;
        $usuario->estado = 1;

        try {
            $usuario->save();
            $alert = array(
                'type' => 'success',
                'message' => 'Operación exitosa.',
            );
            return response()->json($alert);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Error al guardar el usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($idEmpresa = null, string $id)
    {
        $usuario = Usuario::find($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $idEmpresa = null, string $id)
    {
        // Validar los datos ingresados
        $request->validate([

            'clave' => [
                'required',
                'string',
                'min:8', // Mínimo 8 caracteres
                'regex:/[a-z]/', // Al menos una letra minúscula
                'regex:/[A-Z]/', // Al menos una letra mayúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[@$!%*?&]/', // Al menos un carácter especial
            ],
            'clave1' => 'required|same:clave', // Confirmación de contraseña
        ], [
            // Mensajes personalizados

            'clave.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'clave.regex' => 'La contraseña debe incluir el uso de carácteres numéricos, en mayúscula y un carácteres especiales (@$!%*?&).',
            '*.required' => 'Campo obligatorio.',
            'clave1.same' => 'Las contraseñas no coinciden.',
        ]);


        $usuario = Usuario::find($id);
        // Verificar que la contraseña actual sea correcta
        // if (!Hash::check($request->input('clave_actual'), $usuario->clave)) {
        //     return response()->json([
        //         'errors' => [
        //             'clave_actual' => [
        //                 'La contraseña actual no es correcta.'
        //             ]
        //         ]
        //     ], 422);
        // }

        // Actualizar la contraseña del usuario
        $usuario->clave = Hash::make($request->input('clave'));
        $usuario->fecha_actualizado = now();
        $usuario->save();
        $alert = array(
            'type' => 'success',
            'message' => 'Operación exitosa.'
        );
        return response()->json($alert);
    }

    public function destroy($idEmpresa = null, string $id)
    {
        $usuario = Usuario::find($id);
        $usuario->delete();
        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha eliminado exitosamente'
        );
        return response()->json($alert);
    }

    public function baja($idEmpresa = null, $id)
    {
        $usuario = Usuario::find($id);
        $usuario->fecha_actualizado = now();
        $usuario->estado = 0;
        $usuario->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha deshabilitado exitosamente'
        );
        return response()->json($alert);
    }

    public function alta($idEmpresa = null, $id)
    {

        $usuario = Usuario::find($id);
        if (!$usuario) {
            $alert = array(
                'type' => 'error',
                'message' => 'No encontró el usuario'
            );
            return response()->json($alert);
        }
        $usuario->fecha_actualizado = now();
        $usuario->estado = 1;
        $usuario->save();

        $alert = array(
            'type' => 'success',
            'message' => 'El registro se ha restaurado exitosamente'
        );
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

    public function getUsuarios($idEmpresa)
    {
        $id=auth()->user()->idUsuario;
        $usuarios = Usuario::where('idEmpresa', $idEmpresa)->get(); // Ajusta esto según tus necesidades
        return response()->json([
            'usuarios' => $usuarios,
            'idUsuario' => $id
        ]);
    }
}
