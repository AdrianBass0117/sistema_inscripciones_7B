<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Género - {{ $fecha }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 14px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #34495e;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .table tr:hover {
            background-color: #e8f4f8;
        }
        .total-row {
            background-color: #2c3e50 !important;
            color: white;
            font-weight: bold;
        }
        .summary {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .summary h3 {
            color: #2c3e50;
            margin-top: 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .chart-placeholder {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border: 1px dashed #bdc3c7;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Distribución por Género</h1>
        <div class="subtitle">Sistema de Gestión Deportiva y Cultural</div>
        <div class="subtitle">Generado el: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <h3>Resumen Ejecutivo</h3>
        <p>Total de inscripciones analizadas: <strong>{{ $datos->total ?? 0 }}</strong></p>
        <p>Este reporte muestra la distribución de inscripciones según el género de las disciplinas.</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="40%">Género de Disciplina</th>
                <th width="30%">Total Inscritos</th>
                <th width="30%">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>🏆 Varonil</td>
                <td>{{ $datos->varonil ?? 0 }}</td>
                <td>{{ $datos->total > 0 ? number_format(($datos->varonil / $datos->total) * 100, 2) . '%' : '0%' }}</td>
            </tr>
            <tr>
                <td>🎯 Femenil</td>
                <td>{{ $datos->femenil ?? 0 }}</td>
                <td>{{ $datos->total > 0 ? number_format(($datos->femenil / $datos->total) * 100, 2) . '%' : '0%' }}</td>
            </tr>
            <tr>
                <td>🤝 Mixto</td>
                <td>{{ $datos->mixto ?? 0 }}</td>
                <td>{{ $datos->total > 0 ? number_format(($datos->mixto / $datos->total) * 100, 2) . '%' : '0%' }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>TOTAL GENERAL</strong></td>
                <td><strong>{{ $datos->total ?? 0 }}</strong></td>
                <td><strong>100%</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="chart-placeholder">
        <h3>Distribución por Género</h3>
        <p>Género más popular:
            <strong>
                @php
                    $max = max($datos->varonil ?? 0, $datos->femenil ?? 0, $datos->mixto ?? 0);
                    if($max == ($datos->varonil ?? 0)) echo 'VARONIL';
                    elseif($max == ($datos->femenil ?? 0)) echo 'FEMENIL';
                    else echo 'MIXTO';
                @endphp
            </strong>
        </p>
        <p>Representación visual de la distribución de inscripciones por género de disciplina</p>
    </div>

    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión Deportiva y Cultural |
        Página 1 de 1
    </div>
</body>
</html>
