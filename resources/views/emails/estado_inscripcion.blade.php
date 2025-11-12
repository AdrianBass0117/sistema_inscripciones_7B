<!DOCTYPE html>
<html>
<head>
    <title>Actualización de Inscripción</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h1 style="color: #004F6E;">Hola, {{ $inscripcion->usuario->nombre_completo }}</h1>

    <p>Aquí tienes una actualización sobre tu inscripción a:</p>
    <p><strong>Evento/Disciplina:</strong> {{ $inscripcion->disciplina->nombre }}</p>

    @if ($estado === 'pendiente')
        <h2 style="color: #D69E2E;">Solicitud Recibida (Pendiente)</h2>
        <p>Hemos recibido correctamente tu solicitud de inscripción. Nuestro comité la está revisando.</p>
        <p>Te enviaremos un correo de confirmación tan pronto como tu plaza sea aprobada o rechazada.</p>
    
    @elseif ($estado === 'aceptado')
        <h2 style="color: #38A169;">¡Inscripción Aceptada!</h2>
        <p>¡Felicidades! Tu lugar para <strong>{{ $inscripcion->disciplina->nombre }}</strong> ha sido confirmado.</p>
        <p>¡Nos vemos allí!</p>
    
    @elseif ($estado === 'rechazado')
        <h2 style="color: #E53E3E;">Inscripción Rechazada</h2>
        <p>Lamentablemente, no pudimos procesar tu inscripción para <strong>{{ $inscripcion->disciplina->nombre }}</strong> en esta ocasión.</p>
        <p>Esto puede deberse a cupo lleno o a que no se cumplieron todos los requisitos. Puedes iniciar sesión para ver más detalles.</p>
    @endif

    <p>Saludos,<br>El equipo de AAJDEV</p>

</body>
</html>