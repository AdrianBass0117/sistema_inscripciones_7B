@extends('supervisor.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-chart-line"></i> Reportes Ejecutivos</h1>
                <p>Vista consolidada y reportes estratégicos para la dirección</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary" onclick="exportAllReports()">
                    <i class="fas fa-download"></i>
                    Exportar Todo
                </button>
            </div>
        </div>

        <!-- Distribución General -->
        <div class="distribution-section">
            <div class="distribution-grid">
                <!-- Distribución por Género -->
                <div class="distribution-card">
                    <div class="card-header">
                        <h3><i class="fas fa-venus-mars"></i> Distribución por Género de Disciplinas</h3>
                        <button class="btn-outline btn-sm" onclick="exportGenderDistribution()">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div class="gender-chart">
                                <div class="chart-visual">
                                    <div class="pie-gender"></div>
                                </div>
                                <div class="chart-legend">
                                    <div class="legend-item varonil">
                                        <div class="legend-color"></div>
                                        <div class="legend-info">
                                            <span>Varonil</span>
                                            <strong id="varonil-count">0 (0%)</strong>
                                        </div>
                                    </div>
                                    <div class="legend-item femenil">
                                        <div class="legend-color"></div>
                                        <div class="legend-info">
                                            <span>Femenil</span>
                                            <strong id="femenil-count">0 (0%)</strong>
                                        </div>
                                    </div>
                                    <div class="legend-item mixto">
                                        <div class="legend-color"></div>
                                        <div class="legend-info">
                                            <span>Mixto</span>
                                            <strong id="mixto-count">0 (0%)</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribución por Categoría -->
                <div class="distribution-card">
                    <div class="card-header">
                        <h3><i class="fas fa-tags"></i> Distribución por Categoría</h3>
                        <button class="btn-outline btn-sm" onclick="exportCategoryDistribution()">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="category-chart">
                            <div class="chart-bars">
                                <div class="bar-category deportiva">
                                    <div class="bar-label">Deportiva</div>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 65%">
                                            <span class="bar-value">65%</span>
                                        </div>
                                    </div>
                                    <div class="bar-count">810 participantes</div>
                                </div>
                                <div class="bar-category cultural">
                                    <div class="bar-label">Cultural</div>
                                    <div class="bar-container">
                                        <div class="bar-fill" style="width: 35%">
                                            <span class="bar-value">35%</span>
                                        </div>
                                    </div>
                                    <div class="bar-count">437 participantes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Disciplinas -->
        <div class="top-disciplines-section">
            <div class="section-header">
                <h2><i class="fas fa-trophy"></i> Top 5 Disciplinas Más Populares</h2>
                <button class="btn-primary" onclick="exportTopDisciplines()">
                    <i class="fas fa-download"></i>
                    Exportar Ranking
                </button>
            </div>

            <div class="disciplines-ranking">
                @foreach ([['nombre' => 'Fútbol Varonil', 'inscritos' => 287, 'aceptados' => 245, 'tasa_aceptacion' => '85.4%', 'crecimiento' => '12%'], ['nombre' => 'Básquetbol Varonil', 'inscritos' => 198, 'aceptados' => 180, 'tasa_aceptacion' => '90.9%', 'crecimiento' => '8%'], ['nombre' => 'Voleibol Femenil', 'inscritos' => 176, 'aceptados' => 160, 'tasa_aceptacion' => '90.9%', 'crecimiento' => '15%'], ['nombre' => 'Ajedrez', 'inscritos' => 145, 'aceptados' => 140, 'tasa_aceptacion' => '96.6%', 'crecimiento' => '5%'], ['nombre' => 'Canto', 'inscritos' => 132, 'aceptados' => 125, 'tasa_aceptacion' => '94.7%', 'crecimiento' => '18%']] as $index => $disciplina)
                    <div class="discipline-rank">
                        <div class="rank-number">{{ $index + 1 }}</div>
                        <div class="discipline-info">
                            <h4>{{ $disciplina['nombre'] }}</h4>
                            <div class="discipline-stats">
                                <span class="stat">{{ $disciplina['inscritos'] }} inscritos</span>
                                <span class="stat">{{ $disciplina['aceptados'] }} aceptados</span>
                                <span class="stat">{{ $disciplina['tasa_aceptacion'] }} tasa</span>
                            </div>
                        </div>
                        <div class="growth-indicator {{ $disciplina['crecimiento'] > 10 ? 'high' : 'medium' }}">
                            <i class="fas fa-arrow-up"></i>
                            {{ $disciplina['crecimiento'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Métricas de Desempeño -->
        <div class="performance-section">
            <div class="section-header">
                <h2><i class="fas fa-tachometer-alt"></i> Métricas de Desempeño</h2>
                <button class="btn-outline" onclick="exportPerformanceMetrics()">
                    <i class="fas fa-chart-bar"></i>
                    Ver Reporte Detallado
                </button>
            </div>

            <div class="performance-grid">
                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fas fa-bolt"></i>
                        <h3>Eficiencia de Validación</h3>
                    </div>
                    <div class="metric-value">92%</div>
                    <div class="metric-description">
                        Documentos procesados dentro del tiempo establecido
                    </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        5% mejor que el mes anterior
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fas fa-user-check"></i>
                        <h3>Tasa de Aceptación</h3>
                    </div>
                    <div class="metric-value">83.8%</div>
                    <div class="metric-description">
                        Porcentaje de inscripciones validadas positivamente
                    </div>
                    <div class="metric-trend stable">
                        <i class="fas fa-minus"></i>
                        Consistente con el promedio histórico
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fas fa-clock"></i>
                        <h3>Tiempo de Respuesta</h3>
                    </div>
                    <div class="metric-value">18h</div>
                    <div class="metric-description">
                        Tiempo promedio para validar una inscripción
                    </div>
                    <div class="metric-trend positive">
                        <i class="fas fa-arrow-down"></i>
                        2h menos que el mes anterior
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fas fa-chart-line"></i>
                        <h3>Crecimiento Mensual</h3>
                    </div>
                    <div class="metric-value">+12%</div>
                    <div class="metric-description">
                        Incremento en inscripciones vs mes anterior
                    </div>
                    <div class="metric-trend high">
                        <i class="fas fa-arrow-up"></i>
                        Ritmo de crecimiento acelerado
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen para Dirección -->
        <div class="director-summary">
            <div class="summary-card executive">
                <div class="summary-header">
                    <h2><i class="fas fa-bullseye"></i> Puntos Clave para Dirección</h2>
                    <div class="summary-badge">ALTA PRIORIDAD</div>
                </div>
                <div class="key-points">
                    <div class="key-point positive">
                        <div class="point-icon">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="point-content">
                            <h4>Crecimiento Sólido</h4>
                            <p>+12% de crecimiento en inscripciones vs mes anterior, superando proyecciones</p>
                        </div>
                    </div>
                    <div class="key-point warning">
                        <div class="point-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="point-content">
                            <h4>Disciplinas Sobre Demandadas</h4>
                            <p>Fútbol Varonil y Básquetbol cerca del 90% de capacidad</p>
                        </div>
                    </div>
                    <div class="key-point positive">
                        <div class="point-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="point-content">
                            <h4>Eficiencia en Validación</h4>
                            <p>Tiempo de respuesta mejorado en 2 horas, manteniendo calidad</p>
                        </div>
                    </div>
                    <div class="key-point info">
                        <div class="point-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="point-content">
                            <h4>Oportunidad en Culturales</h4>
                            <p>Disciplinas culturales muestran mayor tasa de crecimiento relativo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para selección de formato -->
    <div id="modalFormato" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-download"></i> Descargar Reporte</h3>
                <button type="button" class="modal-close" onclick="closeFormatModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="formatoForm" method="POST" action="{{ route('supervisor.reportes.descargar') }}">
                    @csrf
                    <input type="hidden" name="tipo_reporte" id="tipo_reporte">

                    <div class="form-group">
                        <label for="formato">Selecciona el formato:</label>
                        <select name="formato" id="formato" class="form-control" required>
                            <option value="">Selecciona un formato</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="ambos">Ambos formatos</option>
                        </select>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeFormatModal()">Cancelar</button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-download"></i>
                            Descargar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .legend-item.mixto .legend-color {
            background: #6c757d;
        }

        .legend-item.mixto:hover {
            background: var(--bg-light);
        }

        /* Estilos para el modal */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            animation: modalSlideIn 0.3s ease;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* === ESTILOS GENERALES === */
        .dashboard-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-content p {
            opacity: 0.9;
            margin: 0;
            font-size: 1.1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Resumen Ejecutivo */
        .executive-summary {
            margin-bottom: 3rem;
        }

        .summary-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .summary-header {
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-header h2 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin: 0;
        }

        .summary-period {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 0;
        }

        .summary-stat {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-right: 1px solid var(--border-color);
        }

        .summary-stat:last-child {
            border-right: none;
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            flex-shrink: 0;
        }

        .summary-stat.primary .stat-icon {
            background: var(--primary-color);
        }

        .summary-stat.success .stat-icon {
            background: var(--success);
        }

        .summary-stat.warning .stat-icon {
            background: var(--warning);
        }

        .summary-stat.accent .stat-icon {
            background: var(--accent-color);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .stat-trend.positive {
            color: var(--success);
        }

        .stat-trend.negative {
            color: var(--danger);
        }

        .stat-trend.stable {
            color: var(--text-secondary);
        }

        /* Distribución */
        .distribution-section {
            margin-bottom: 3rem;
        }

        .distribution-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .distribution-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .card-body {
            padding: 2rem;
        }

        /* Gráficos */
        .gender-chart {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .chart-visual {
            width: 150px;
            height: 150px;
            flex-shrink: 0;
        }

        .pie-gender {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(var(--primary-color) 0% 59.6%,
                    #ff6b81 59.6% 100%);
            box-shadow: var(--shadow-sm);
        }

        .chart-legend {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            flex: 1;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            transition: background-color 0.2s ease;
        }

        .legend-item:hover {
            background: var(--bg-light);
        }

        .legend-item.varonil .legend-color {
            background: var(--primary-color);
        }

        .legend-item.femenil .legend-color {
            background: #ff6b81;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .legend-info {
            flex: 1;
        }

        .legend-info span {
            display: block;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .legend-info strong {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Barras de categoría */
        .category-chart {
            padding: 1rem 0;
        }

        .chart-bars {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .bar-category {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .bar-label {
            width: 100px;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .bar-container {
            flex: 1;
            height: 30px;
            background: var(--bg-light);
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .bar-fill {
            height: 100%;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 1rem;
            transition: width 1s ease;
        }

        .bar-category.deportiva .bar-fill {
            background: var(--secondary-color);
        }

        .bar-category.cultural .bar-fill {
            background: var(--accent-color);
        }

        .bar-value {
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .bar-count {
            width: 120px;
            text-align: right;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Top Disciplinas */
        .top-disciplines-section {
            margin-bottom: 3rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin: 0;
        }

        .disciplines-ranking {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .discipline-rank {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .discipline-rank:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .rank-number {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .discipline-info {
            flex: 1;
        }

        .discipline-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .discipline-stats {
            display: flex;
            gap: 1.5rem;
        }

        .discipline-stats .stat {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .growth-indicator {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .growth-indicator.high {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .growth-indicator.medium {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        /* Métricas de Desempeño */
        .performance-section {
            margin-bottom: 3rem;
        }

        .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            text-align: center;
            border-top: 4px solid;
        }

        .metric-card:nth-child(1) {
            border-top-color: var(--primary-color);
        }

        .metric-card:nth-child(2) {
            border-top-color: var(--success);
        }

        .metric-card:nth-child(3) {
            border-top-color: var(--warning);
        }

        .metric-card:nth-child(4) {
            border-top-color: var(--accent-color);
        }

        .metric-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .metric-header i {
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .metric-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .metric-value {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .metric-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .metric-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .metric-trend.positive {
            color: var(--success);
        }

        .metric-trend.stable {
            color: var(--text-secondary);
        }

        .metric-trend.high {
            color: var(--accent-color);
        }

        /* Reportes Ejecutivos */
        .executive-reports-section {
            margin-bottom: 3rem;
        }

        .executive-reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .executive-report-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-left: 4px solid;
        }

        .executive-report-card:nth-child(1) {
            border-left-color: var(--primary-color);
        }

        .executive-report-card:nth-child(2) {
            border-left-color: var(--success);
        }

        .executive-report-card:nth-child(3) {
            border-left-color: var(--warning);
        }

        .executive-report-card:nth-child(4) {
            border-left-color: var(--accent-color);
        }

        .report-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            flex-shrink: 0;
        }

        .report-icon.primary {
            background: var(--primary-color);
        }

        .report-icon.success {
            background: var(--success);
        }

        .report-icon.warning {
            background: var(--warning);
        }

        .report-icon.accent {
            background: var(--accent-color);
        }

        .report-content {
            flex: 1;
        }

        .report-content h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .report-content p {
            margin: 0 0 1rem 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .report-meta {
            display: flex;
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .report-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Resumen para Dirección */
        .director-summary {
            margin-bottom: 3rem;
        }

        .summary-card.executive {
            border: 2px solid var(--primary-color);
        }

        .summary-badge {
            background: var(--danger);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .key-points {
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .key-point {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid;
        }

        .key-point.positive {
            border-left-color: var(--success);
            background: rgba(56, 161, 105, 0.05);
        }

        .key-point.warning {
            border-left-color: var(--warning);
            background: rgba(214, 158, 46, 0.05);
        }

        .key-point.info {
            border-left-color: var(--accent-color);
            background: rgba(0, 123, 255, 0.05);
        }

        .point-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
            flex-shrink: 0;
        }

        .key-point.positive .point-icon {
            background: var(--success);
        }

        .key-point.warning .point-icon {
            background: var(--warning);
        }

        .key-point.info .point-icon {
            background: var(--accent-color);
        }

        .point-content h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .point-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Exportación Avanzada */
        .advanced-export-section {
            margin-bottom: 2rem;
        }

        .export-card.executive {
            border: 2px solid var(--success);
            background: linear-gradient(135deg, white, #f8f9fa);
        }

        .export-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .export-header i {
            font-size: 2.5rem;
            color: var(--success);
        }

        .export-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.5rem;
        }

        .export-card p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .export-suite {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .suite-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .suite-option:hover {
            background: #e9ecef;
        }

        .suite-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .suite-option label {
            flex: 1;
            color: var(--text-primary);
            font-weight: 500;
        }

        .file-size {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .suite-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .format-select {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            color: var(--text-primary);
            min-width: 150px;
        }

        /* Botones */
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-outline {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-primary:hover,
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .distribution-grid {
                grid-template-columns: 1fr;
            }

            .executive-reports-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1.5rem;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .summary-stat {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .summary-stat:last-child {
                border-bottom: none;
            }

            .gender-chart {
                flex-direction: column;
                text-align: center;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .performance-grid {
                grid-template-columns: 1fr;
            }

            .key-points {
                grid-template-columns: 1fr;
            }

            .suite-actions {
                flex-direction: column;
            }

            .executive-report-card {
                flex-direction: column;
                text-align: center;
            }

            .report-meta {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .summary-stat {
                flex-direction: column;
                text-align: center;
            }

            .discipline-rank {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .discipline-stats {
                flex-direction: column;
                gap: 0.5rem;
            }

            .bar-category {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .bar-count {
                text-align: left;
            }
        }

        /* Mejoras de responsividad para gráficos de categoría */
        @media (max-width: 768px) {
            .category-chart {
                padding: 0.5rem 0;
            }

            .chart-bars {
                gap: 1.5rem;
            }

            .bar-category {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .bar-label {
                width: 100%;
                font-size: 1rem;
                margin-bottom: 0.25rem;
            }

            .bar-container {
                height: 40px;
                /* Más alto en móviles para mejor visibilidad */
                width: 100%;
                border-radius: 20px;
            }

            .bar-fill {
                padding: 0 1.5rem;
                /* Más espacio para el texto */
                justify-content: flex-start;
                /* Texto a la izquierda */
            }

            .bar-value {
                font-size: 1rem;
                /* Texto más grande */
                font-weight: 700;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
                /* Mejor contraste */
            }

            .bar-count {
                width: 100%;
                text-align: left;
                font-size: 0.9rem;
                margin-top: 0.25rem;
                color: var(--text-primary);
                font-weight: 600;
            }
        }

        @media (max-width: 480px) {
            .bar-container {
                height: 50px;
                /* Aún más alto en pantallas muy pequeñas */
            }

            .bar-value {
                font-size: 1.1rem;
            }

            .bar-count {
                font-size: 1rem;
            }
        }
    </style>

    <script>
        // Variables globales
        let currentReportType = '';

        // Función de notificación
        function showNotification(message, type = 'info') {
            // Crear elemento de notificación
            const notification = document.createElement('div');
            notification.className = `notification-toast ${type}`;

            // Iconos según el tipo
            const icons = {
                'success': 'fa-check-circle',
                'error': 'fa-exclamation-circle',
                'warning': 'fa-exclamation-triangle',
                'info': 'fa-info-circle'
            };

            notification.innerHTML = `
        <i class="fas ${icons[type] || 'fa-info-circle'}"></i>
        <span>${message}</span>
    `;

            // Estilos de la notificación
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${getNotificationColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
        font-weight: 500;
    `;

            document.body.appendChild(notification);

            // Remover después de 4 segundos
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Función auxiliar para colores de notificación
        function getNotificationColor(type) {
            const colors = {
                'success': '#38a169',
                'error': '#e53e3e',
                'warning': '#dd6b20',
                'info': '#3182ce'
            };
            return colors[type] || '#3182ce';
        }

        // Agregar los keyframes CSS para las animaciones
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
        document.head.appendChild(style);

        // Funciones para abrir modal de formato
        function openFormatModal(reportType) {
            currentReportType = reportType;
            document.getElementById('tipo_reporte').value = reportType;
            document.getElementById('modalFormato').style.display = 'flex';
        }

        function closeFormatModal() {
            document.getElementById('modalFormato').style.display = 'none';
            document.getElementById('formatoForm').reset();
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalFormato').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFormatModal();
            }
        });

        // Actualizar los botones de exportación existentes para usar el modal
        function exportGenderDistribution() {
            openFormatModal('genero');
        }

        function exportCategoryDistribution() {
            openFormatModal('categoria');
        }

        function exportTopDisciplines() {
            openFormatModal('ranking');
        }

        function exportAllReports() {
            openFormatModal('todos');
        }

        // Manejar envío del formulario
        document.getElementById('formatoForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formato = document.getElementById('formato').value;
            if (!formato) {
                showNotification('Por favor selecciona un formato', 'warning');
                return;
            }

            showNotification(`Generando reporte en formato ${formato.toUpperCase()}...`, 'info');

            // Enviar formulario
            this.submit();

            // Cerrar modal después de un breve momento
            setTimeout(() => {
                closeFormatModal();
            }, 1000);
        });

        // Cargar datos reales para los gráficos
        document.addEventListener('DOMContentLoaded', function() {
            // Hacer petición AJAX para obtener datos reales
            fetch('{{ route('supervisor.estadisticas.datos-graficos') }}')
                .then(response => response.json())
                .then(data => {
                    actualizarDatosGraficos(data);
                })
                .catch(error => {
                    console.error('Error al cargar datos:', error);
                });
        });

        function exportPerformanceMetrics() {
            openFormatModal('metricas');
        }

        // Función para actualizar gráficos con datos reales
        function actualizarDatosGraficos(data) {
            // Actualizar distribución por género (basado en disciplinas)
            if (data.genero) {
                const totalGenero = data.genero.varonil + data.genero.femenil + data.genero.mixto;

                // Calcular porcentajes
                const porcentajeVaronil = totalGenero > 0 ? (data.genero.varonil / totalGenero) * 100 : 0;
                const porcentajeFemenil = totalGenero > 0 ? (data.genero.femenil / totalGenero) * 100 : 0;
                const porcentajeMixto = totalGenero > 0 ? (data.genero.mixto / totalGenero) * 100 : 0;

                // Actualizar el gráfico de pastel
                document.querySelector('.pie-gender').style.background = `conic-gradient(
            var(--primary-color) 0% ${porcentajeVaronil}%,
            #ff6b81 ${porcentajeVaronil}% ${porcentajeVaronil + porcentajeFemenil}%,
            #6c757d ${porcentajeVaronil + porcentajeFemenil}% 100%
        )`;

                // Actualizar leyenda
                document.getElementById('varonil-count').textContent =
                    `${data.genero.varonil} (${porcentajeVaronil.toFixed(1)}%)`;
                document.getElementById('femenil-count').textContent =
                    `${data.genero.femenil} (${porcentajeFemenil.toFixed(1)}%)`;
                document.getElementById('mixto-count').textContent =
                    `${data.genero.mixto} (${porcentajeMixto.toFixed(1)}%)`;
            }

            // Actualizar distribución por categoría
            // En la función actualizarDatosGraficos
            if (data.categoria && data.categoria.length > 0) {
                let totalCategoria = data.categoria.reduce((sum, item) => sum + item.total, 0);

                // Inicializar valores por defecto
                let deportivaTotal = 0;
                let culturalTotal = 0;

                // Recopilar datos
                data.categoria.forEach((categoria) => {
                    if (categoria.categoria === 'Deporte') {
                        deportivaTotal = categoria.total;
                    } else if (categoria.categoria === 'Cultural') {
                        culturalTotal = categoria.total;
                    }
                });

                // Calcular porcentajes
                const porcentajeDeportiva = totalCategoria > 0 ? (deportivaTotal / totalCategoria) * 100 : 0;
                const porcentajeCultural = totalCategoria > 0 ? (culturalTotal / totalCategoria) * 100 : 0;

                // Actualizar elementos DOM
                const barDeportiva = document.querySelector('.bar-category.deportiva .bar-fill');
                const barCultural = document.querySelector('.bar-category.cultural .bar-fill');

                if (barDeportiva) {
                    barDeportiva.style.width = `${porcentajeDeportiva}%`;
                    barDeportiva.querySelector('.bar-value').textContent = `${porcentajeDeportiva.toFixed(1)}%`;
                    document.querySelector('.bar-category.deportiva .bar-count').textContent =
                        `${deportivaTotal} participantes`;
                }

                if (barCultural) {
                    barCultural.style.width = `${porcentajeCultural}%`;
                    barCultural.querySelector('.bar-value').textContent = `${porcentajeCultural.toFixed(1)}%`;
                    document.querySelector('.bar-category.cultural .bar-count').textContent =
                        `${culturalTotal} participantes`;
                }
            }

            // Actualizar top disciplinas con datos reales
            if (data.top_disciplinas && data.top_disciplinas.length > 0) {
                const rankingContainer = document.querySelector('.disciplines-ranking');
                rankingContainer.innerHTML = '';

                data.top_disciplinas.forEach((disciplina, index) => {
                    const growth = Math.random() * 20 + 5; // Simular crecimiento para el ejemplo

                    const rankElement = document.createElement('div');
                    rankElement.className = 'discipline-rank';
                    rankElement.innerHTML = `
                <div class="rank-number">${index + 1}</div>
                <div class="discipline-info">
                    <h4>${disciplina.nombre}</h4>
                    <div class="discipline-stats">
                        <span class="stat">${disciplina.total_inscritos} inscritos</span>
                        <span class="stat">${disciplina.inscripciones_aceptadas} aceptados</span>
                        <span class="stat">${disciplina.tasa_aceptacion}% tasa</span>
                        <span class="stat">${disciplina.genero} - ${disciplina.categoria}</span>
                    </div>
                </div>
                <div class="growth-indicator ${growth > 15 ? 'high' : 'medium'}">
                    <i class="fas fa-arrow-up"></i>
                    ${growth.toFixed(0)}%
                </div>
            `;

                    rankingContainer.appendChild(rankElement);
                });
            }
        }
    </script>
@endsection
