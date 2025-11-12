<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Ejecutivo Completo - {{ $fecha }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 20px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 12px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #2c3e50;
            background-color: #ecf0f1;
            padding: 8px 12px;
            border-left: 4px solid #3498db;
            margin: 15px 0 10px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .table th {
            background-color: #34495e;
            color: white;
            padding: 6px 4px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 5px 4px;
            border-bottom: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .metric-grid {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .metric-card {
            flex: 1;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            margin: 0 5px;
            text-align: center;
        }
        .metric-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .metric-label {
            font-size: 9px;
            color: #7f8c8d;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 8px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Ejecutivo Completo</h1>
        <div class="subtitle">Sistema de Gestión Deportiva y Cultural</div>
        <div class="subtitle">Generado el: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</div>
    </div>

    <!-- Métricas Principales -->
    <div class="section">
        <h2>📊 Métricas Principales</h2>
        <div class="metric-grid">
            <div class="metric-card">
                <div class="metric-value">{{ $datos['genero']->total ?? 0 }}</div>
                <div class="metric-label">Total Inscritos</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $datos['metricas']['crecimiento_mensual'] ?? 0 }}%</div>
                <div class="metric-label">Crecimiento Mensual</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $datos['metricas']['tiempo_validacion'] ?? 0 }}h</div>
                <div class="metric-label">Tiempo Validación</div>
            </div>
            <div class="metric-card">
                <div class="metric-value">{{ $datos['metricas']['eficiencia_validacion'] ?? 0 }}%</div>
                <div class="metric-label">Eficiencia</div>
            </div>
        </div>
    </div>

    <!-- Distribución por Género -->
    <div class="section">
        <h2>👥 Distribución por Género de Disciplinas</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Género</th>
                    <th>Total Inscritos</th>
                    <th>Porcentaje</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Varonil</td>
                    <td>{{ $datos['genero']->varonil ?? 0 }}</td>
                    <td>{{ $datos['genero']->total > 0 ? number_format(($datos['genero']->varonil / $datos['genero']->total) * 100, 1) . '%' : '0%' }}</td>
                </tr>
                <tr>
                    <td>Femenil</td>
                    <td>{{ $datos['genero']->femenil ?? 0 }}</td>
                    <td>{{ $datos['genero']->total > 0 ? number_format(($datos['genero']->femenil / $datos['genero']->total) * 100, 1) . '%' : '0%' }}</td>
                </tr>
                <tr>
                    <td>Mixto</td>
                    <td>{{ $datos['genero']->mixto ?? 0 }}</td>
                    <td>{{ $datos['genero']->total > 0 ? number_format(($datos['genero']->mixto / $datos['genero']->total) * 100, 1) . '%' : '0%' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Distribución por Categoría -->
    <div class="section">
        <h2>🏷️ Distribución por Categoría</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Total Inscritos</th>
                    <th>Aceptados</th>
                    <th>Tasa Aceptación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos['categoria'] as $categoria)
                @php
                    $tasaAceptacion = $categoria->total > 0 ? ($categoria->aceptados / $categoria->total) * 100 : 0;
                @endphp
                <tr>
                    <td>{{ $categoria->categoria }}</td>
                    <td>{{ $categoria->total }}</td>
                    <td>{{ $categoria->aceptados }}</td>
                    <td>{{ number_format($tasaAceptacion, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Top 5 Disciplinas -->
    <div class="section">
        <h2>🏆 Top 5 Disciplinas Más Populares</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Disciplina</th>
                    <th>Inscritos</th>
                    <th>Aceptados</th>
                    <th>Tasa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($datos['ranking']->take(5) as $index => $disciplina)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $disciplina->nombre }}</td>
                    <td>{{ $disciplina->total_inscritos }}</td>
                    <td>{{ $disciplina->inscripciones_aceptadas }}</td>
                    <td>{{ number_format($disciplina->tasa_aceptacion, 1) }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Reporte Ejecutivo Completo - Sistema de Gestión Deportiva y Cultural |
        Página 1 de 1 | {{ $fecha }}
    </div>
</body>
</html>
