<!DOCTYPE html>
<html>
<head>
    <title>Restablece tu Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    
        <h1 style="color: #004F6E; font-size: 24px;">Hola, {{ $name }}</h1>
        
        <p>Recibiste este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.</p>
        
        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #00AA8B; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Restablecer Contraseña
            </a>
        </p>
        
        <p>Este enlace de restablecimiento de contraseña caducará en {{ config('auth.passwords.users.expire') }} minutos.</p>
        
        <p>Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna acción adicional.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin-top: 20px;">
        
        <p style="font-size: 12px; color: #777;">
            Si tienes problemas para hacer clic en el botón "Restablecer contraseña", copia y pega la siguiente URL en tu navegador web:
            <br>
            <a href="{{ $url }}" style="color: #0077B6; word-break: break-all;">{{ $url }}</a>
        </p>
    
    </div>

</body>
</html>