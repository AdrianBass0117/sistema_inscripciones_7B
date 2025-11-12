<!DOCTYPE html>
<html>
<head>
    <title>Actualización de Documento</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h1 style="color: #004F6E;">Hola, {{ $documento->usuario->nombre_completo }}</h1>

    <p>Queremos informarte sobre el estado de tu documento:</p>

    <p><strong>Documento:</strong> {{ $documento->tipo_documento }}</p>

    @if ($estado === 'aceptado')
        <h2 style="color: #38A169;">¡Aprobado!</h2>
        <p>Tu documento ha sido revisado y aprobado por nuestro comité.</p>
    
    @elseif ($estado === 'rechazado')
        <h2 style="color: #E53E3E;">Rechazado (Acción Requerida)</h2>
        <p>Lamentablemente, tu documento no pudo ser aprobado. Por favor, inicia sesión en la plataforma para corregirlo.</p>
        
        <p><strong>Motivo del rechazo:</strong></p>
        <div style="background: #f8d7da; border-left: 4px solid #721c24; padding: 10px 15px; margin-top: 10px;">
            <p style="margin: 0;">{{ $motivoRechazo }}</p>
        </div>
    @endif

    <p>Puedes gestionar tus documentos iniciando sesión en tu cuenta.</p>

    <p>Saludos,<br>El equipo de AAJDEV</p>

</body>
</html>