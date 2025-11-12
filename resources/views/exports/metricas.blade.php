<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Métricas de Desempeño - {{ $fecha }}</title>
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
        .metric-excelente { color: #27ae60; font-weight: bold; }
        .metric-bueno { color: #f39c12; }
        .metric-regular { color: #e74c3c; }
        .metric-value {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
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
        .metric-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid;
        }
        .metric-card.excelente { border-left-color: #27ae60; }
        .metric-card.bueno { border-left-color: #f39c12; }
        .metric-card.regular { border-left-color: #e74c3c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Métricas de Desempeño</h1>
        <div class="subtitle">Sistema de Gestión Deportiva y Cultural</div>
        <div class="subtitle">Generado el: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <h3>Resumen Ejecutivo</h3>
        <p>Análisis de las principales métricas de desempeño del sistema</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="40%">Métrica</th>
                <th width="20%">Valor</th>
                <th width="20%">Unidad</th>
                <th width="20%">Evaluación</th>
            </tr>
        </thead>
        <tbody>
            @php
                $evaluacionTiempo = $datos['tiempo_validacion'] < 24 ? 'Excelente' : ($datos['tiempo_validacion'] < 48 ? 'Bueno' : 'Regular');
                $evaluacionCrecimiento = $datos['crecimiento_mensual'] > 10 ? 'Excelente' : ($datos['crecimiento_mensual'] > 5 ? 'Bueno' : 'Regular');
                $evaluacionEficiencia = $datos['eficiencia_validacion'] > 90 ? 'Excelente' : ($datos['eficiencia_validacion'] > 80 ? 'Bueno' : 'Regular');
                $evaluacionTasa = $datos['tasa_aceptacion_general'] > 85 ? 'Excelente' : ($datos['tasa_aceptacion_general'] > 75 ? 'Bueno' : 'Regular');

                $claseTiempo = strtolower($evaluacionTiempo);
                $claseCrecimiento = strtolower($evaluacionCrecimiento);
                $claseEficiencia = strtolower($evaluacionEficiencia);
                $claseTasa = strtolower($evaluacionTasa);
            @endphp

            <tr>
                <td>⏱️ Tiempo Promedio de Validación</td>
                <td class="metric-value">{{ $datos['tiempo_validacion'] ?? 0 }}</td>
                <td>horas</td>
                <td class="metric-{{ $claseTiempo }}">{{ $evaluacionTiempo }}</td>
            </tr>
            <tr>
                <td>📈 Crecimiento Mensual</td>
                <td class="metric-value">{{ $datos['crecimiento_mensual'] ?? 0 }}%</td>
                <td>porcentaje</td>
                <td class="metric-{{ $claseCrecimiento }}">{{ $evaluacionCrecimiento }}</td>
            </tr>
            <tr>
                <td>⚡ Eficiencia de Validación</td>
                <td class="metric-value">{{ $datos['eficiencia_validacion'] ?? 0 }}%</td>
                <td>porcentaje</td>
                <td class="metric-{{ $claseEficiencia }}">{{ $evaluacionEficiencia }}</td>
            </tr>
            <tr>
                <td>✅ Tasa de Aceptación General</td>
                <td class="metric-value">{{ $datos['tasa_aceptacion_general'] ?? 0 }}%</td>
                <td>porcentaje</td>
                <td class="metric-{{ $claseTasa }}">{{ $evaluacionTasa }}</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <h3>Análisis Detallado</h3>

        <div class="metric-card {{ $claseTiempo }}">
            <h4>⏱️ Tiempo de Validación</h4>
            <p><strong>Valor:</strong> {{ $datos['tiempo_validacion'] ?? 0 }} horas</p>
            <p><strong>Evaluación:</strong> {{ $evaluacionTiempo }}</p>
            <p><strong>Análisis:</strong>
                @if($evaluacionTiempo == 'Excelente')
                El tiempo de respuesta es óptimo, permitiendo una validación rápida de inscripciones.
                @elseif($evaluacionTiempo == 'Bueno')
                El tiempo de respuesta es aceptable, con margen para mejoras en eficiencia.
                @else
                Se recomienda revisar los procesos de validación para reducir tiempos de respuesta.
                @endif
            </p>
        </div>

        <div class="metric-card {{ $claseCrecimiento }}">
            <h4>📈 Crecimiento Mensual</h4>
            <p><strong>Valor:</strong> {{ $datos['crecimiento_mensual'] ?? 0 }}%</p>
            <p><strong>Evaluación:</strong> {{ $evaluacionCrecimiento }}</p>
            <p><strong>Análisis:</strong>
                @if($evaluacionCrecimiento == 'Excelente')
                Crecimiento sólido que supera las expectativas, indicando alta participación.
                @elseif($evaluacionCrecimiento == 'Bueno')
                Crecimiento positivo y sostenido, acorde con las proyecciones.
                @else
                Crecimiento moderado, se recomienda evaluar estrategias de promoción.
                @endif
            </p>
        </div>

        <div class="metric-card {{ $claseEficiencia }}">
            <h4>⚡ Eficiencia de Validación</h4>
            <p><strong>Valor:</strong> {{ $datos['eficiencia_validacion'] ?? 0 }}%</p>
            <p><strong>Evaluación:</strong> {{ $evaluacionEficiencia }}</p>
            <p><strong>Análisis:</strong>
                @if($evaluacionEficiencia == 'Excelente')
                Procesos de validación altamente eficientes y bien establecidos.
                @elseif($evaluacionEficiencia == 'Bueno')
                Buen nivel de eficiencia, con oportunidades menores de optimización.
                @else
                Se recomienda revisar y optimizar los flujos de trabajo de validación.
                @endif
            </p>
        </div>

        <div class="metric-card {{ $claseTasa }}">
            <h4>✅ Tasa de Aceptación</h4>
            <p><strong>Valor:</strong> {{ $datos['tasa_aceptacion_general'] ?? 0 }}%</p>
            <p><strong>Evaluación:</strong> {{ $evaluacionTasa }}</p>
            <p><strong>Análisis:</strong>
                @if($evaluacionTasa == 'Excelente')
                Alta tasa de aceptación indica procesos de inscripción bien definidos.
                @elseif($evaluacionTasa == 'Bueno')
                Tasa de aceptación saludable, con buen equilibrio entre calidad y cantidad.
                @else
                Se recomienda revisar criterios de aceptación y comunicación con usuarios.
                @endif
            </p>
        </div>
    </div>

    <div class="footer">
        Reporte de Métricas de Desempeño - Sistema de Gestión Deportiva y Cultural |
        Página 1 de 1 | {{ $fecha }}
    </div>
</body>
</html>
