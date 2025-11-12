<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Usuarios por Estado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #004F6E;
            padding-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #004F6E;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .info-item {
            margin-bottom: 5px;
            flex: 1;
            min-width: 200px;
        }

        .summary {
            background: #e9ecef;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }

        .table th {
            background-color: #004F6E;
            color: white;
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .table td {
            padding: 4px;
            border: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 8px;
            margin-bottom: 10px;
        }

        .stat-item {
            background: #f8f9fa;
            padding: 6px;
            border-radius: 4px;
            text-align: center;
            border-left: 3px solid #004F6E;
        }

        .stat-value {
            font-weight: bold;
            font-size: 10px;
            color: #004F6E;
        }

        .stat-label {
            font-size: 7px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">SISTEMA DE INSCRIPCIONES - REPORTE DE USUARIOS</div>
        <div class="subtitle">Sistema de Gestión Deportiva</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item"><strong>Estado:</strong> {{ $estado }}</div>
            <div class="info-item"><strong>Total de Usuarios:</strong> {{ $total_usuarios }}</div>
            <div class="info-item"><strong>Fecha de Generación:</strong> {{ $fechaGeneracion }}</div>
        </div>
    </div>

    @if ($usuarios->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="8%">Número Trabajador</th>
                    <th width="15%">Nombre Completo</th>
                    <th width="12%">Email</th>
                    <th width="8%">Teléfono</th>
                    <th width="10%">CURP</th>
                    <th width="6%">Antigüedad</th>
                    <th width="8%">Fecha Nacimiento</th>
                    <th width="8%">Fecha Registro</th>
                    <th width="6%">Total Inscripciones</th>
                    <th width="8%">Inscripciones Aceptadas</th>
                    <th width="8%">Documentos Pendientes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $index => $usuario)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $usuario['numero_trabajador'] ?? 'N/A' }}</td>
                        <td>{{ $usuario['nombre_completo'] }}</td>
                        <td>{{ $usuario['email'] }}</td>
                        <td>{{ $usuario['telefono'] ?? 'No disponible' }}</td>
                        <td>{{ $usuario['curp'] ?? 'No disponible' }}</td>
                        <td>{{ $usuario['antiguedad'] ?? '0' }} años</td>
                        <td>{{ $usuario['fecha_nacimiento'] ?? 'No disponible' }}</td>
                        <td>{{ $usuario['fecha_registro'] ?? 'No disponible' }}</td>
                        <td>{{ $usuario['total_inscripciones'] ?? 0 }}</td>
                        <td>{{ $usuario['inscripciones_aceptadas_count'] ?? 0 }}</td>
                        <td>{{ $usuario['documentos_pendientes_count'] ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No hay usuarios para el estado seleccionado.</p>
        </div>
    @endif

    <div class="footer">
        <p>Generado el: {{ $fechaGeneracion }}</p>
    </div>
</body>

</html>
