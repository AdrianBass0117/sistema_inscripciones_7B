<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ranking de Disciplinas - {{ $fecha }}</title>
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
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .table th {
            background-color: #34495e;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            padding: 6px 6px;
            border-bottom: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .top-1 { background-color: #fff9c4 !important; }
        .top-2 { background-color: #f3e5f5 !important; }
        .top-3 { background-color: #e3f2fd !important; }
        .rank-cell {
            text-align: center;
            font-weight: bold;
            width: 30px;
        }
        .tasa-alta { color: #27ae60; font-weight: bold; }
        .tasa-media { color: #f39c12; }
        .tasa-baja { color: #e74c3c; }
        .cupo-lleno { color: #e74c3c; font-weight: bold; }
        .cupo-disponible { color: #27ae60; }
        .summary {
            background-color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
            font-size: 10px;
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
        <h1>Ranking Top 10 Disciplinas Más Populares</h1>
        <div class="subtitle">Sistema de Gestión Deportiva y Cultural</div>
        <div class="subtitle">Generado el: {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="summary">
        <h3>Resumen Ejecutivo</h3>
        <p>Ranking basado en el total de inscripciones por disciplina | Total de disciplinas analizadas: {{ $datos->count() }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="25%">Disciplina</th>
                <th width="10%">Categoría</th>
                <th width="10%">Género</th>
                <th width="12%">Total Inscritos</th>
                <th width="12%">Aceptados</th>
                <th width="10%">Tasa Aceptación</th>
                <th width="8%">Cupo Máx</th>
                <th width="8%">Disponibles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos as $index => $disciplina)
            @php
                $tasaAceptacion = $disciplina->total_inscritos > 0 ?
                    ($disciplina->inscripciones_aceptadas / $disciplina->total_inscritos) * 100 : 0;
                $tasaClase = $tasaAceptacion >= 80 ? 'tasa-alta' : ($tasaAceptacion >= 60 ? 'tasa-media' : 'tasa-baja');
                $cuposDisponibles = $disciplina->cupo_maximo - $disciplina->inscripciones_aceptadas;
                $cupoClase = $cuposDisponibles <= 0 ? 'cupo-lleno' : 'cupo-disponible';
                $rowClass = $index == 0 ? 'top-1' : ($index == 1 ? 'top-2' : ($index == 2 ? 'top-3' : ''));
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="rank-cell">{{ $index + 1 }}</td>
                <td><strong>{{ $disciplina->nombre }}</strong></td>
                <td>
                    @if($disciplina->categoria == 'Deporte')
                    🏅
                    @else
                    🎨
                    @endif
                    {{ $disciplina->categoria }}
                </td>
                <td>
                    @if($disciplina->genero == 'Varonil')
                    👨
                    @elseif($disciplina->genero == 'Femenil')
                    👩
                    @else
                    👥
                    @endif
                    {{ $disciplina->genero }}
                </td>
                <td>{{ $disciplina->total_inscritos }}</td>
                <td>{{ $disciplina->inscripciones_aceptadas }}</td>
                <td class="{{ $tasaClase }}">{{ number_format($tasaAceptacion, 1) }}%</td>
                <td>{{ $disciplina->cupo_maximo }}</td>
                <td class="{{ $cupoClase }}">
                    {{ max(0, $cuposDisponibles) }}
                    @if($cuposDisponibles <= 0)
                    (LLENO)
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Estadísticas del Ranking</h3>
        @php
            $totalInscritos = $datos->sum('total_inscritos');
            $promedioInscritos = $datos->count() > 0 ? $totalInscritos / $datos->count() : 0;
            $disciplinaTop = $datos->first();
        @endphp
        <p><strong>Disciplina #1:</strong> {{ $disciplinaTop->nombre }} con {{ $disciplinaTop->total_inscritos }} inscritos</p>
        <p><strong>Total de inscritos en Top 10:</strong> {{ $totalInscritos }}</p>
        <p><strong>Promedio de inscritos por disciplina:</strong> {{ number_format($promedioInscritos, 1) }}</p>
        <p><strong>Disciplinas con cupo lleno:</strong>
            {{ $datos->where('inscripciones_aceptadas', '>=', \DB::raw('cupo_maximo'))->count() }}
        </p>
    </div>

    <div class="footer">
        Reporte generado automáticamente por el Sistema de Gestión Deportiva y Cultural |
        Página 1 de 1
    </div>
</body>
</html>
