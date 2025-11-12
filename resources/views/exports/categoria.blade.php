<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por Categoría - {{ $fecha }}</title>
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
        .deporte-row {
            border-left: 4px solid #e74c3c;
        }
        .cultural-row {
            border-left: 4px solid #3498db;
        }
        .total-row {
            background-color: #2c3e50 !important;
            color: white;
            font-weight: bold;
        }
        .tasa-alta { color: #27ae60; font-weight: bold; }
        .tasa-media { color: #f39c12; }
        .tasa-baja { color: #e74c3c; }
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Distribución por Categoría</h1>
        <div class="subtitle">Sistema de Gestión Deportiva y Cultural</div>
        <div class="subtitle">Generado el: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <h3>Resumen Ejecutivo</h3>
        <p>Análisis de participación por categoría de disciplinas</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="30%">Categoría</th>
                <th width="20%">Total Inscritos</th>
                <th width="20%">Inscripciones Aceptadas</th>
                <th width="15%">Tasa de Aceptación</th>
                <th width="15%">Porcentaje del Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalGeneral = $datos->sum('total');
                $totalAceptados = $datos->sum('aceptados');
            @endphp

            @foreach($datos as $categoria)
            @php
                $porcentajeTotal = $totalGeneral > 0 ? ($categoria->total / $totalGeneral) * 100 : 0;
                $tasaAceptacion = $categoria->total > 0 ? ($categoria->aceptados / $categoria->total) * 100 : 0;
                $tasaClase = $tasaAceptacion >= 80 ? 'tasa-alta' : ($tasaAceptacion >= 60 ? 'tasa-media' : 'tasa-baja');
            @endphp
            <tr class="{{ $categoria->categoria == 'Deporte' ? 'deporte-row' : 'cultural-row' }}">
                <td>
                    @if($categoria->categoria == 'Deporte')
                    🏅 Deporte
                    @else
                    🎨 Cultural
                    @endif
                </td>
                <td>{{ $categoria->total }}</td>
                <td>{{ $categoria->aceptados }}</td>
                <td class="{{ $tasaClase }}">{{ number_format($tasaAceptacion, 1) }}%</td>
                <td>{{ number_format($porcentajeTotal, 1) }}%</td>
            </tr>
            @endforeach

            <tr class="total-row">
                <td><strong>TOTAL GENERAL</strong></td>
                <td><strong>{{ $totalGeneral }}</strong></td>
                <td><strong>{{ $totalAceptados }}</strong></td>
                <td><strong>{{ $totalGeneral > 0 ? number_format(($totalAceptados / $totalGeneral) * 100, 1) . '%' : '0%' }}</strong></td>
                <td><strong>100%</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <h3>Análisis por Categoría</h3>
        @foreach($datos as $categoria)
        @php
            $porcentajeTotal = $totalGeneral > 0 ? ($categoria->total / $totalGeneral) * 100 : 0;
        @endphp
        <p>
            <strong>{{ $categoria->categoria }}:</strong>
            {{ $categoria->total }} inscripciones ({{ number_format($porcentajeTotal, 1) }}% del total) |
            {{ $categoria->aceptados }} aceptadas |
            Tasa de aceptación: {{ $categoria->total > 0 ? number_format(($categoria->aceptados / $categoria->total) * 100, 1) : 0 }}%
        </p>
        @endforeach
    </div>

    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión Deportiva y Cultural |
        Página 1 de 1
    </div>
</body>
</html>
