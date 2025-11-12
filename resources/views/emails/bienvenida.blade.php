<!DOCTYPE html>
<html>
<head>
    <title>¡Bienvenido/a!</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h1 style="color: #004F6E;">¡Bienvenido/a, {{ $usuario->nombre_completo }}!</h1>
    
    <p>Te has registrado exitosamente en nuestra plataforma de inscripciones.</p>
    
    <p>Tu cuenta ha sido creada con el correo: <strong>{{ $usuario->email }}</strong></p>
    
    <p>Actualmente, tu información personal y los documentos que subiste se encuentran <strong>Pendientes de Validación</strong>. Nuestro comité los revisará a la brevedad.</p>
    
    <p>Te notificaremos por este medio tan pronto como tu cuenta sea validada.</p>
    
    <p>Gracias,<br>El equipo de AAJDEV</p>

</body>
</html>