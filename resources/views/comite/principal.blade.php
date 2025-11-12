@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header de Bienvenida -->
        <div class="welcome-section">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-badge">
                        <i class="fas fa-users-cog"></i>
                        Panel del Comité
                    </div>
                    <h1>Bienvenido, <span class="highlight">Miembro del Comité</span></h1>
                    <p class="welcome-description">
                        Eres el corazón operativo del evento. Desde aquí gestionas validaciones, disciplinas
                        y aseguras que cada participante tenga una experiencia excepcional.
                    </p>

                    <div class="quick-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $estadisticas['pendientes_validar'] }}</div>
                            <div class="stat-label">Pendientes de Validar</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $estadisticas['disciplinas_activas'] }}</div>
                            <div class="stat-label">Disciplinas Activas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ number_format($estadisticas['usuarios_aceptados']) }}</div>
                            <div class="stat-label">Usuarios Aceptados</div>
                        </div>
                    </div>
                </div>
                <div class="welcome-illustration">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="actions-section">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Acciones Operativas
            </h2>
            <div class="actions-grid">
                <div class="action-card" onclick="navigateTo('validaciones')">
                    <div class="action-icon warning">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="action-content">
                        <h3>Validar Documentos</h3>
                        <p>Revisa y valida documentos de usuarios pendientes</p>
                        <div class="action-badge urgent">{{ $estadisticas['documentos_pendientes'] }} pendientes</div>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="action-card" onclick="navigateTo('disciplinas')">
                    <div class="action-icon primary">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="action-content">
                        <h3>Gestionar Disciplinas</h3>
                        <p>Administra disciplinas, cupos y configuraciones</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="action-card" onclick="navigateTo('aspirantes')">
                    <div class="action-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="action-content">
                        <h3>Gestión de validacion</h3>
                        <p>Administra inscripciones y estados de validacion</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>

                <div class="action-card" onclick="navigateTo('reportes')">
                    <div class="action-icon secondary">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="action-content">
                        <h3>Reportes Operativos</h3>
                        <p>Genera reportes diarios para tu trabajo</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tareas Pendientes -->
        <div class="tasks-section">
            <h2 class="section-title">
                <i class="fas fa-list-check"></i>
                Tareas Prioritarias
            </h2>
            <div class="tasks-grid">
                <div class="task-card high-priority">
                    <div class="task-header">
                        <h3>Validaciones Urgentes</h3>
                        <span class="task-count">{{ $estadisticas['validaciones_urgentes'] }}</span>
                    </div>
                    <p>Documentos con más de 48 horas en espera</p>
                    <div class="task-actions">
                        <button class="btn-primary btn-sm" onclick="navigateTo('validaciones')">
                            Revisar Ahora
                        </button>
                    </div>
                </div>

                <div class="task-card medium-priority">
                    <div class="task-header">
                        <h3>Disciplinas por Configurar</h3>
                        <span class="task-count">{{ $estadisticas['disciplinas_por_configurar'] }}</span>
                    </div>
                    <p>Disciplinas que requieren ajustes de cupo o fechas</p>
                    <div class="task-actions">
                        <button class="btn-secondary btn-sm" onclick="navigateTo('disciplinas')">
                            Configurar
                        </button>
                    </div>
                </div>

                <div class="task-card low-priority">
                    <div class="task-header">
                        <h3>Reporte Diario</h3>
                        <span class="task-count">1</span>
                    </div>
                    <p>Generar reporte de actividades del día</p>
                    <div class="task-actions">
                        <button class="btn-secondary btn-sm" onclick="navigateTo('reportes')">
                            Generar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Rol -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div class="info-content">
                    <h3>Tu Impacto como Validador</h3>
                    <p>Cada documento que validas garantiza la integridad del evento. Tu atención al detalle asegura que
                        todos los aspirantes cumplan con los requisitos necesarios para una competencia justa y segura.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>Tiempos de Respuesta</h3>
                    <p>Mantén los tiempos de validación por debajo de 24 horas. Se agradece tu eficiencia y tu trabajo ágil,
                        mejorando toda la experiencia del evento.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="info-content">
                    <h3>Trabajo en Equipo</h3>
                    <p>Coordinación con otros miembros del comité es clave. Tu comunicación efectiva asegura que la
                        información fluya y los procesos operen sin inconvenientes.</p>
                </div>
            </div>
        </div>

        <!-- Métricas de Hoy -->
        <div class="metrics-section">
            <h2 class="section-title">
                <i class="fas fa-chart-simple"></i>
                Tu Impacto Hoy
            </h2>
            <div class="metrics-grid">
                <div class="metric-card">
                    <div class="metric-value">{{ $metricasHoy['documentos_validados']['valor'] }}</div>
                    <div class="metric-label">Documentos Validados</div>
                    <div class="metric-trend {{ $metricasHoy['documentos_validados']['tendencia'] }}">
                        <i
                            class="fas fa-arrow-{{ $metricasHoy['documentos_validados']['tendencia'] == 'positive' ? 'up' : ($metricasHoy['documentos_validados']['tendencia'] == 'negative' ? 'down' : 'minus') }}"></i>
                        {{ $metricasHoy['documentos_validados']['texto_tendencia'] }}
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $metricasHoy['tasa_aprobacion']['valor'] }}%</div>
                    <div class="metric-label">Tasa de Aprobación</div>
                    <div class="metric-trend {{ $metricasHoy['tasa_aprobacion']['tendencia'] }}">
                        <i
                            class="fas fa-{{ $metricasHoy['tasa_aprobacion']['tendencia'] == 'positive' ? 'arrow-up' : ($metricasHoy['tasa_aprobacion']['tendencia'] == 'negative' ? 'arrow-down' : 'minus') }}"></i>
                        {{ $metricasHoy['tasa_aprobacion']['texto_tendencia'] }}
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $metricasHoy['tiempo_respuesta']['valor'] }}h</div>
                    <div class="metric-label">Tiempo Promedio de Respuesta</div>
                    <div class="metric-trend {{ $metricasHoy['tiempo_respuesta']['tendencia'] }}">
                        <i
                            class="fas fa-arrow-{{ $metricasHoy['tiempo_respuesta']['tendencia'] == 'positive' ? 'down' : 'up' }}"></i>
                        {{ $metricasHoy['tiempo_respuesta']['texto_tendencia'] }}
                    </div>
                </div>
                <div class="metric-card">
                    <div class="metric-value">{{ $metricasHoy['usuarios_aceptados']['valor'] }}</div>
                    <div class="metric-label">Usuarios Nuevos Aceptados</div>
                    <div class="metric-trend {{ $metricasHoy['usuarios_aceptados']['tendencia'] }}">
                        <i
                            class="fas fa-arrow-{{ $metricasHoy['usuarios_aceptados']['tendencia'] == 'positive' ? 'up' : ($metricasHoy['usuarios_aceptados']['tendencia'] == 'negative' ? 'down' : 'minus') }}"></i>
                        {{ $metricasHoy['usuarios_aceptados']['texto_tendencia'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje Motivacional -->
        <div class="motivational-section">
            <div class="motivational-card">
                <div class="quote-icon">
                    <i class="fas fa-quote-left"></i>
                </div>
                <div class="quote-content">
                    <h3>La Excelencia en los Detalles Construye Grandes Eventos</h3>
                    <p>Tu meticulosa revisión de cada documento, tu atención a cada detalle en las disciplinas, y tu
                        compromiso con la validación justa son los cimientos sobre los que se construye la confianza de
                        todos los validacion. Eres el guardián de la integridad del evento.</p>
                    <div class="quote-author">
                        <i class="fas fa-award"></i>
                        Equipo de Organización
                    </div>
                </div>
            </div>
        </div>

        <!-- Recordatorios Operativos -->
        <div class="reminders-section">
            <h2 class="section-title">
                <i class="fas fa-lightbulb"></i>
                Mejores Prácticas
            </h2>
            <div class="reminders-list">
                <div class="reminder-item">
                    <i class="fas fa-search"></i>
                    <div class="reminder-content">
                        <h4>Revisión Exhaustiva</h4>
                        <p>Verifica cada documento minuciosamente antes de aprobar</p>
                    </div>
                </div>
                <div class="reminder-item">
                    <i class="fas fa-comment-medical"></i>
                    <div class="reminder-content">
                        <h4>Comunicación Clara</h4>
                        <p>Proporciona retroalimentación específica en rechazos</p>
                    </div>
                </div>
                <div class="reminder-item">
                    <i class="fas fa-balance-scale"></i>
                    <div class="reminder-content">
                        <h4>Consistencia en Criterios</h4>
                        <p>Aplica los mismos estándares para todos los validacion</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === ESTILOS EXISTENTES (similares al supervisor) === */
        :root {
            --primary-color: #004F6E;
            --secondary-color: #00AA8B;
            --accent-color: #0077B6;
            --success: #38A169;
            --warning: #D69E2E;
            --danger: #E53E3E;
            --bg-white: #FFFFFF;
            --bg-light: #F7FAFC;
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .actions-section {
            margin: 2rem 0;
        }

        /* === ESTILOS ESPECÍFICOS PARA COMITÉ === */

        /* Badge de urgencia en acciones */
        .action-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .action-badge.urgent {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        /* Sección de Tareas */
        .tasks-section {
            margin-bottom: 3rem;
        }

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .task-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .task-card.high-priority {
            border-left-color: var(--danger);
        }

        .task-card.medium-priority {
            border-left-color: var(--warning);
        }

        .task-card.low-priority {
            border-left-color: var(--accent-color);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .task-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .task-count {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .task-card p {
            margin: 0 0 1.5rem 0;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .task-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Métricas de Impacto */
        .metrics-section {
            margin-bottom: 3rem;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .metric-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            text-align: center;
            border: 1px solid var(--bg-light);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .metric-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .metric-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .metric-trend.positive {
            color: var(--success);
        }

        .metric-trend.negative {
            color: var(--danger);
        }

        .metric-trend.stable {
            color: var(--text-secondary);
        }

        /* === ESTILOS COMPARTIDOS (similares al supervisor) === */

        .welcome-section {
            margin-bottom: 3rem;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .welcome-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
        }

        .highlight {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-description {
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 600px;
        }

        .welcome-illustration {
            font-size: 6rem;
            opacity: 0.7;
            z-index: 1;
        }

        .quick-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--secondary-color), #00CC99);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 500;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .action-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--secondary-color);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .action-icon.primary {
            background: var(--primary-color);
        }

        .action-icon.secondary {
            background: var(--secondary-color);
        }

        .action-icon.success {
            background: var(--success);
        }

        .action-icon.warning {
            background: var(--warning);
        }

        .action-content {
            flex: 1;
        }

        .action-content h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .action-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .action-arrow {
            color: var(--text-secondary);
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .action-card:hover .action-arrow {
            transform: translateX(5px);
            color: var(--secondary-color);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .info-card {
            background: var(--bg-white);
            padding: 2rem;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid var(--bg-light);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .info-content h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .info-content p {
            margin: 0;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .motivational-section {
            margin-bottom: 3rem;
        }

        .motivational-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .motivational-card::before {
            content: "";
            position: absolute;
            top: -20%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .quote-icon {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 1rem;
        }

        .quote-content h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .quote-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .quote-author {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-style: italic;
            opacity: 0.8;
        }

        .reminders-section {
            margin-bottom: 2rem;
        }

        .reminders-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .reminder-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--accent-color);
        }

        .reminder-item i {
            color: var(--accent-color);
            font-size: 1.25rem;
            width: 24px;
        }

        .reminder-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .reminder-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Botones */
        .btn-primary,
        .btn-secondary {
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .welcome-card {
                flex-direction: column;
                text-align: center;
                gap: 2rem;
                padding: 2rem;
            }

            .welcome-content h1 {
                font-size: 2rem;
            }

            .welcome-illustration {
                font-size: 4rem;
            }

            .quick-stats {
                justify-content: center;
                flex-wrap: wrap;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .action-card {
                flex-direction: column;
                text-align: center;
            }

            .tasks-grid {
                grid-template-columns: 1fr;
            }

            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .reminder-item {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .welcome-card {
                padding: 1.5rem;
            }

            .welcome-content h1 {
                font-size: 1.75rem;
            }

            .welcome-description {
                font-size: 1rem;
            }

            .quick-stats {
                flex-direction: column;
                gap: 1rem;
            }

            .metrics-grid {
                grid-template-columns: 1fr;
            }

            .info-card {
                padding: 1.5rem;
            }

            .motivational-card {
                padding: 1.5rem;
            }

            .action-card {
                padding: 1.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navegación para el comité
            window.navigateTo = function(destination) {
                const routes = {
                    'disciplinas': '{{ route('comite.disciplinas') }}',
                    'validacion': '{{ route('comite.validacion') }}',
                    'reportes': '{{ route('comite.reportes') }}',
                    'aspirantes': '{{ route('comite.aspirantes') }}'
                };

                const route = routes[destination];

                if (route) {
                    window.location.href = route;
                } else {
                    // Rutas directas como fallback
                    const directRoutes = {
                        'validaciones': '/Comite/Validaciones',
                        'disciplinas': '/comite/disciplinas',
                        'aspirantes': '/comite/aspirantes',
                        'reportes': '/comite/reportes'
                    };

                    if (directRoutes[destination]) {
                        window.location.href = directRoutes[destination];
                    } else {
                        showNotification(`Función en desarrollo: ${destination}`, 'info');
                    }
                }
            };

            // Animación de elementos al hacer scroll
            const animatedElements = document.querySelectorAll(
                '.action-card, .info-card, .task-card, .metric-card, .reminder-item');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Aplicar estilos iniciales y observar
            animatedElements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(element);
            });

            // Simular actualización de métricas en tiempo real
            setInterval(() => {
                // En una aplicación real, esto haría una petición al servidor
                console.log('Actualizando métricas del comité...');
            }, 30000); // Cada 30 segundos

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            `;

                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : 'var(--accent-color)'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: var(--shadow-lg);
                display: flex;
                align-items: center;
                gap: 0.5rem;
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Añadir estilos de animación para notificaciones
            const style = document.createElement('style');
            style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
            document.head.appendChild(style);
        });
    </script>
@endsection
