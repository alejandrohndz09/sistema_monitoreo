<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales de Acceso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #0056b3;
        }

        p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .credentials {
            background: #f0f4f8;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .button {
            display: inline-block;
            background: #0056b3;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
        }

        .button:hover {
            background: #003f88;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Credenciales de Acceso a NormaSegura System</h1>
        <p>Estimados,</p>
        <p>
            Su empresa <strong>{{ $usuario->empresa->nombre }}</strong> ha sido registrada en nuestro sistema de
            monitoreo basado en
            <strong>ISO 27001:2022</strong>. A continuación, encontrará las credenciales temporales para acceder al
            sistema:
        </p>

        @if ($usuario->empresa->usuarios->count() > 1)
            <p>
                A continuación, encontrará las credenciales para el nuevo usuario de la empresa:
            </p>
        @else
            <p>
                Dado que es el primer usuario registrado para su empresa, a continuación se muestran las credenciales de
                acceso:
            </p>
        @endif
        <div class="credentials">
            <p><strong>Usuario:</strong> {{ $usuario->usuario }}</p>
            <p><strong>Contraseña temporal:</strong> {{ $claveTemporal }}</p>
        </div>
        <p>
            <strong>Instrucciones:</strong>
        <ol>
            <li>Acceda al sistema a través del siguiente enlace: <a href="{{ url('/') }}"
                    target="_blank">{{ url('/') }}</a>.</li>
            <li>Inicie sesión con las credenciales proporcionadas.</li>
            <li>Cambie su contraseña al ingresar por primera vez.</li>
        </ol>
        </p>
        <p>
            <strong>Recomendaciones de seguridad:</strong>
        </p>
        <ul>
            <li>Establezca una contraseña segura (mínimo 8 caracteres, incluyendo mayúsculas, minúsculas, números y
                símbolos).</li>
            <li>No comparta sus credenciales con nadie.</li>
            <li>Si detecta accesos no autorizados, contáctenos de inmediato.</li>
        </ul>

        <p>Atentamente,</p>
        <p>
            <strong>NormaSegura System</strong><br>
            <a href="{{ url('/') }}">{{ url('/') }}</a>
        </p>
    </div>
</body>

</html>
