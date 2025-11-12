@extends('supervisor.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header de Bienvenida -->
        <div class="welcome-section">
            <div class="welcome-card">
                <div class="welcome-content">
                    <div class="welcome-badge">
                        <i class="fas fa-chart-line"></i>
                        Panel de Supervisión
                    </div>
                    <h1>Bienvenido, <span class="highlight">Supervisor</span></h1>
                    <p class="welcome-description">
                        Desde aquí tienes el control total sobre la supervisión de inscripciones y disciplinas.
                        Monitorea el progreso, genera reportes y asegura el éxito del evento.
                    </p>

                    <div class="quick-stats">
                        <div class="stat-item">
                            <div class="stat-value">1,247</div>
                            <div class="stat-label">Total Inscritos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">24</div>
                            <div class="stat-label">Disciplinas Activas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">89%</div>
                            <div class="stat-label">Tasa de Validación</div>
                        </div>
                    </div>
                </div>
                <div class="welcome-illustration">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="actions-section">
            <h2 class="section-title">
                <i class="fas fa-rocket"></i>
                Acciones Rápidas
            </h2>
            <div class="actions-grid">
                <div class="action-card" onclick="navigateTo('estadisticas')">
                    <div class="action-icon primary">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-content">
                        <h3>Ver Estadísticas y Generar Reportes</h3>
                        <p>Consulta reportes detallados y análisis de datos</p>
                        <p>Descarga reportes en PDF y Excel</p>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="info-content">
                    <h3>Tu Rol como Supervisor</h3>
                    <p>Tienes acceso completo para visualizar todas las estadísticas, generar reportes y monitorear el
                        progreso general del evento. Tu supervisión garantiza la transparencia y éxito de las inscripciones.
                    </p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="info-content">
                    <h3>Datos en Tiempo Real</h3>
                    <p>Todas las estadísticas se actualizan automáticamente. Puedes ver la distribución por género,
                        disciplinas más populares y tendencias de inscripción en tiempo real.</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-file-export"></i>
                </div>
                <div class="info-content">
                    <h3>Reportes Automatizados</h3>
                    <p>Genera reportes detallados en diferentes formatos. Exporta datos completos de participantes,
                        estadísticas por disciplina y análisis consolidados.</p>
                </div>
            </div>
        </div>

        <!-- Estado del Sistema -->
        <div class="system-status">
            <h2 class="section-title">
                <i class="fas fa-heartbeat"></i>
                Estado del Sistema
            </h2>
            <div class="status-grid">
                <div class="status-item online">
                    <div class="status-indicator"></div>
                    <div class="status-content">
                        <h4>Sistema Principal</h4>
                        <p>Operando normalmente</p>
                    </div>
                </div>
                <div class="status-item online">
                    <div class="status-indicator"></div>
                    <div class="status-content">
                        <h4>Base de Datos</h4>
                        <p>Sincronización activa</p>
                    </div>
                </div>
                <div class="status-item online">
                    <div class="status-indicator"></div>
                    <div class="status-content">
                        <h4>Servicio de Reportes</h4>
                        <p>Disponible para descargas</p>
                    </div>
                </div>
                <div class="status-item online">
                    <div class="status-indicator"></div>
                    <div class="status-content">
                        <h4>API de Datos</h4>
                        <p>Respuesta óptima</p>
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
                    <h3>La Excelencia en la Supervisión Transforma Datos en Decisiones</h3>
                    <p>Tu capacidad para interpretar la información y generar insights valiosos es lo que convierte números
                        en estrategias. Cada reporte que generas, cada estadística que analizas, contribuye al éxito de este
                        evento y a la experiencia de cada participante.</p>
                    <div class="quote-author">
                        <i class="fas fa-star"></i>
                        Equipo de Organización
                    </div>
                </div>
            </div>
        </div>

        <!-- Recordatorios Importantes -->
        <div class="reminders-section">
            <h2 class="section-title">
                <i class="fas fa-bell"></i>
                Recordatorios Importantes
            </h2>
            <div class="reminders-list">
                <div class="reminder-item">
                    <i class="fas fa-sync-alt"></i>
                    <div class="reminder-content">
                        <h4>Actualización Diaria</h4>
                        <p>Los datos se actualizan automáticamente cada 24 horas</p>
                    </div>
                </div>
                <div class="reminder-item">
                    <i class="fas fa-shield-alt"></i>
                    <div class="reminder-content">
                        <h4>Seguridad de Datos</h4>
                        <p>Toda la información está protegida y encriptada</p>
                    </div>
                </div>
                <div class="reminder-item">
                    <i class="fas fa-clock"></i>
                    <div class="reminder-content">
                        <h4>Horario de Soporte</h4>
                        <p>Soporte técnico disponible de 8:00 AM a 6:00 PM</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === VARIABLES Y ESTILOS GENERALES === */
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

        /* === HEADER DE BIENVENIDA === */
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
            font-size: 3rem;
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
            font-size: 8rem;
            opacity: 0.7;
            z-index: 1;
        }

        /* === ESTADÍSTICAS RÁPIDAS === */
        .quick-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
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

        /* === ACCIONES RÁPIDAS === */
        .actions-section {
            margin-bottom: 3rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .action-card {
            background: var(--bg-white);
            padding: 2rem;
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

        /* === INFORMACIÓN DEL SISTEMA === */
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

        /* === ESTADO DEL SISTEMA === */
        .system-status {
            margin-bottom: 3rem;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .status-item {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 4px solid var(--success);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--success);
            position: relative;
        }

        .status-indicator::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s infinite;
        }

        .status-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .status-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* === MENSAJE MOTIVACIONAL === */
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

        /* === RECORDATORIOS === */
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

        /* === ANIMACIONES === */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.5;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
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
                font-size: 2.5rem;
            }

            .welcome-illustration {
                font-size: 5rem;
            }

            .quick-stats {
                justify-content: center;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .action-card {
                flex-direction: column;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .status-grid {
                grid-template-columns: 1fr;
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
                font-size: 2rem;
            }

            .welcome-description {
                font-size: 1rem;
            }

            .quick-stats {
                flex-direction: column;
                gap: 1rem;
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
            // Navegación para las tarjetas de acción
            window.navigateTo = function(destination) {
                // Mapeo de destinos a rutas
                const routes = {
                    'reportes': '{{ route('supervisor.reportes') }}',
                    'estadisticas': '{{ route('supervisor.estadisticas') }}',
                };

                const route = routes[destination];

                if (route) {
                    // Redirigir a la ruta correspondiente
                    window.location.href = route;
                } else {
                    console.error(`Ruta no encontrada para: ${destination}`);
                    showNotification(`Error: La ruta ${destination} no está configurada`, 'danger');
                }
            };

            // Animación de elementos al hacer scroll
            const animatedElements = document.querySelectorAll(
                '.action-card, .info-card, .status-item, .reminder-item');

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

            // Simular verificación de estado del sistema
            setTimeout(() => {
                console.log('Estado del sistema verificado - Todos los servicios operando normalmente');
            }, 1000);

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
