<!-- resources/views/correo_recuperacion_clave.blade.php -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Clave</title>
</head>
<body>
    <p>Hola {{ $usuario->usuario }},</p>

    <p>Recibes este correo porque hemos recibido una solicitud para recuperar la clave de acceso de tu cuenta. 
        Utiliza el siguiente código de seguridad para continuar con el proceso de recuperación:</p>
    <ul>
        <li>Código de Seguridad: <strong>{{$tokenTemporal}}</strong> </li>
    </ul>

    <p>Por favor, no compartas este código con nadie. Si no solicitaste la recuperación de la clave, puedes ignorar este correo electrónico.</p>
    <p>Saludos, equipo de Tejutepets.</p>
</body>
</html>
