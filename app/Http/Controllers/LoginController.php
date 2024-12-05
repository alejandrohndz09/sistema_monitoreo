<?php

namespace App\Http\Controllers;

use App\Mail\RecuperarClaveMail;
use App\Models\Miembro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // protected $guard = 'usuario';

    public function show()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'contraseña' => 'required',
        ]);
        $user = Usuario::with('empresa')->where('usuario', $request->usuario)->first();

        $alert = null;

        if (!$user || !Hash::check($request->contraseña, $user->clave)) {
            // Mensaje unificado para usuario inexistente o contraseña incorrecta
            $alert = [
                'type' => 'error',
                'message' => 'Credenciales incorrectas.',
            ];
        } elseif ($user->estado == 0) {
            $alert = [
                'type' => 'error',
                'message' => 'El usuario está inactivo.',
            ];
        } elseif ($user->empresa && $user->empresa->estado == 0) {
            // Solo verifica el estado del empleado si el usuario tiene uno asociado
            $alert = [
                'type' => 'error',
                'message' => 'La empresa asociada está inactiva.',
            ];
        } elseif ($user->estado == 2) {
            return redirect('/cambiar-clave/' . base64_encode($user->token).'/temp');
            // return view('usuarios.actualizarClave')->with([
            //     'usuario' => $user,
            //     'token' => $user->token,
            // ]);
        } else {
            Auth::login($user);
            $request->session()->regenerate();
            $alert = [
                'type' => 'success',
                'message' => '¡Bienvenido/a, ' . Auth::user()->usuario . '!',
            ];
            session()->flash('alert', $alert);
            return redirect('/');
        }

        session()->flash('alert', $alert);
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return redirect('/');
    }

    public function cambiarClave(Request $request)
    {
        // Validar la entrada
        $request->validate([
            'clave1' => [
                'required',
                'string',
                'min:8', // Mínimo 8 caracteres
                'regex:/[a-z]/', // Al menos una letra minúscula
                'regex:/[A-Z]/', // Al menos una letra mayúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[@$!%*?&]/', // Al menos un carácter especial
            ],
            'clave2' => 'required|same:clave1',
            'token' => 'required',
        ], [
            '*.required' => 'Este campo es requerido.',
            'clave1.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'clave1.regex' => 'La contraseña debe incluir el uso de carácteres numéricos, en mayúscula y un carácteres especiales (@$!%*?&).',
            'clave2.same' => 'Las contraseñas no coinciden.',
            'token.required' => 'Token inválido.',
        ]);

        // Buscar el usuario basado en el token 
        $usuario = Usuario::where('token', $request->post('token'))->first();

        if (!$usuario) {
            return redirect()->back()->withErrors([
                'token' => 'El token proporcionado no es válido.',
            ]);
        }

        // Cambiar la clave del usuario
        $usuario->estado = 1; // Activar usuario en caso de estar inactivo
        $usuario->clave = Hash::make($request->post('clave1'));
        $usuario->fecha_actualizado = now();
        $usuario->token = null; // Invalidar el token una vez usado
        $usuario->save();

        // Autenticar al usuario
        Auth::login($usuario);
        $request->session()->regenerate();

        // Redirigir con éxito
        session()->flash('alert', [
            'type' => 'success',
            'message' => '¡Bienvenido/a, ' . Auth::user()->usuario . '!',
        ]);

        return redirect('/'); // Redirigir a la página principal o destino deseado
    }

    public function recuperarClaveMail(Request $request)
    {
        $request->validate([
            'usuarioR' => 'required', // Validación del campo usuario
        ], [
            '*.required' => 'Campo requerido.'
        ]);

        $username = $request->post('usuarioR');

        // Buscar al usuario por su nombre de usuario
        $usuario = Usuario::with('empresa')->where('usuario', $username)->first();

        if (!$usuario || !$usuario->empresa || empty($usuario->empresa->correo)) {
            // Mensaje genérico para evitar dar información específica
            $alert = [
                'type' => 'warning',
                'message' => 'No se pudo procesar la solicitud. Por favor, verifica los datos ingresados.',
            ];
            return response()->json($alert);
        }

        // Generar un token de recuperación
        $token = Str::random(8);

        // Preparar y enviar el correo
        try {
            $mail = new RecuperarClaveMail($usuario, $token);
            Mail::to($usuario->empresa->correo)->send($mail);

            // Guardar el token solo si el correo se envía correctamente
            $usuario->token = Hash::make($token);
            $usuario->fecha_actualizado = now();
            $usuario->save();

            $alert = [
                'type' => 'success',
                'message' => 'Se ha enviado un código de seguridad, por favor verifique su correo.',
            ];
            session()->flash('alert', $alert);
            return response()->json([
                'redirect' => url('/codigo-seguridad/' . base64_encode($usuario->token)),
            ]);
        } catch (\Exception $e) {
            // Error al enviar el correo
            return response()->json([
                'type' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function vistaToken($token)
    {
        // Decodificar el token recibido
        $tokenDec = base64_decode($token);

        // Buscar un usuario con el token proporcionado
        $user = Usuario::where('token', $tokenDec)->first();

        // Verificar si el usuario existe
        if (!$user) {
            // Si no se encuentra el usuario, abortar con mensaje de error
            abort(403, 'El token no es válido o no está asociado a ningún usuario.' . $tokenDec);
        }

        // Verificar si el token ha expirado
        if ($user->fecha_actualizado < now()->subMinutes(5)) {
            // Si el token ha expirado, abortar con mensaje de error
            abort(403, 'El enlace de recuperación ha expirado.');
        }

        // Si el token es válido y no ha expirado, retornar la vista con los datos del usuario
        return view('usuarios.codigoSeguridad')->with([
            'usuario' => $user,
            'opcion' => 1,
        ]);
    }

    public function verificarToken(Request $request)
    {
        // Validar que el código ingresado es un valor requerido y que sea un texto
        $request->validate([
            'codigo' => 'required|string', // Valida que el código esté presente y sea un texto
            'usuario' => 'required|exists:usuario,idUsuario' // Verifica que el usuario existe en la base de datos
        ]);

        // Obtener el usuario usando el ID que llegó en el formulario
        $usuario = Usuario::find($request->input('usuario'));

        // Verificar si el usuario existe
        if (!$usuario) {
            return redirect()->back()->withErrors(['codigo' => 'Usuario no encontrado.']);
        }

        // Comprobar si el código ingresado coincide con el token del usuario encriptado en la base de datos
        $valido = Hash::check($request->input('codigo'), $usuario->token);

        if ($valido) {
            // Si el token es válido, redirigir a la página para cambiar la clave
            return redirect('/cambiar-clave/' . base64_encode($usuario->token));
        } else {
            // Si el token no es válido, redirigir de vuelta con un mensaje de error
            return redirect()->back()->withErrors(['codigo' => 'El código de seguridad es incorrecto.']);
        }
    }

    public function vistaCambiarClave($token,  $temporal = null)
    {
        // Decodificar el token recibido
        $tokenDec = base64_decode($token);

        // Buscar un usuario con el token proporcionado
        $user = Usuario::where('token', $tokenDec)->first();

        // Verificar si el usuario existe
        if (!$user) {
            // Si no se encuentra el usuario, abortar con mensaje de error
            abort(403, 'El token no es válido o no está asociado a ningún usuario.' . $tokenDec);
        }

        //Si la vista no se ha llamado por cambio de clave tempora, validar el tiempo de expiración del token
        if (!$temporal) {
            // Verificar si el token ha expirado
            if ($user->fecha_actualizado < now()->subMinutes(5)) {
                // Si el token ha expirado, abortar con mensaje de error
                abort(403, 'El enlace de recuperación ha expirado.');
            }
        }

        // Si el token es válido y no ha expirado, retornar la vista con los datos del usuario
        return view('usuarios.actualizarClave')->with([
            'usuario' => $user,
            'token' => $tokenDec,
            'opcion' => !$temporal?1:2,
        ]);
    }
}
