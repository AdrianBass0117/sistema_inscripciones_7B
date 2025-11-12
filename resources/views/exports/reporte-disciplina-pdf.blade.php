<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Disciplina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #004F6E;
            padding-bottom: 10px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #004F6E;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
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

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10px;
        }

        .table th {
            background-color: #004F6E;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .summary {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">REPORTE DE DISCIPLINA</div>
        <div class="subtitle">Sistema de Gestión Deportiva</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item"><strong>Disciplina:</strong> {{ $disciplina->nombre }}</div>
            <div class="info-item"><strong>Categoría:</strong> {{ $disciplina->getCategoriaFormateada() }}</div>
            <div class="info-item"><strong>Género:</strong> {{ $disciplina->getGeneroFormateado() }}</div>
            <div class="info-item"><strong>Período:</strong> {{ $periodo }}</div>
        </div>
    </div>

    <div class="summary">
        Total de Participantes: {{ $participantes->count() }} / {{ $cupo_maximo }}
    </div>

    @if ($participantes->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">Nombre Completo</th>
                    <th width="20%">Email</th>
                    <th width="10%">Estado</th>
                    <th width="10%">Participó</th>
                    <th width="15%">Fecha Inscripción</th>
                    <th width="15%">Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $index => $participante)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $participante['nombre'] }}</td>
                        <td>{{ $participante['email'] }}</td>
                        <td>{{ $participante['estado'] }}</td>
                        <td>{{ $participante['participo'] }}</td>
                        <td>{{ $participante['fecha_inscripcion'] }}</td>
                        <td>{{ $participante['telefono'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No hay participantes para el período seleccionado.</p>
        </div>
    @endif

    <div class="footer">
        <p>Generado el: {{ $fechaGeneracion }}</p>
    </div>
</body>

</html>
