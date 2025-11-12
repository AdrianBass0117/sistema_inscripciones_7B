@extends('participante.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Bienvenida -->
        <div class="welcome-section">
            <div class="welcome-card">
                <div class="welcome-content">
                    <h2>¡Bienvenido de vuelta, {{ session('user_name') ?? $usuario->nombre_completo }}!</h2>
                    <p>Tu participación hace la diferencia en nuestro evento universitario</p>
                    <div class="welcome-stats">
                        <div class="stat-item">
                            <i class="fas fa-calendar-check"></i>
                                <span>{{ App\Models\Inscripcion::aceptadas()->count() }}+ inscripciones</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                                <span>{{ App\Models\Usuario::validados()->count() }}+ participantes</span>
                        </div>
                    </div>
                </div>
                <div class="welcome-illustration">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>

        <!-- Estado General -->
        <div class="status-grid">
            <div class="status-card primary">
                <div class="status-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="status-content">
                    <h3>Estado de Inscripción</h3>
                    <div
                        class="status-badge {{ $estadisticas['estado_inscripcion'] == 'Aprobado' ? 'approved' : 'pending' }}">
                        {{ $estadisticas['estado_inscripcion'] }}
                    </div>
                    <p>Tu registro principal ha sido
                        {{ $estadisticas['estado_inscripcion'] == 'Aprobado' ? 'aceptado' : 'pendiente' }}</p>
                </div>
            </div>

            <div class="status-card secondary">
                <div class="status-icon">
                    <i class="fas fa-running"></i>
                </div>
                <div class="status-content">
                    <h3>Disciplinas</h3>
                    <div class="count-display">
                        {{ $estadisticas['disciplinas_inscritas'] }}/{{ $estadisticas['maximo_disciplinas'] }}</div>
                    <p>Inscrito de {{ $estadisticas['maximo_disciplinas'] }} permitidas</p>
                </div>
            </div>

            <div class="status-card accent">
                <div class="status-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="status-content">
                    <h3>Progreso</h3>
                    <div class="progress-circle" data-progress="{{ $estadisticas['progreso'] }}">
                        <span>{{ $estadisticas['progreso'] }}%</span>
                    </div>
                    <p>Completado del proceso</p>
                </div>
            </div>
        </div>

        <!-- Contadores y Notificaciones -->
        <div class="content-grid">
            <!-- Disciplinas -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list-alt"></i> Mis Disciplinas</h3>
                    <a href="{{ route('personal.disciplinas') }}" class="card-action">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="discipline-list">
                        <!-- Primero mostrar disciplinas inscritas -->
                        @forelse($disciplinasParticipante as $disciplina)
                            <div
                                class="discipline-item {{ $disciplina['estado'] == 'Aceptado' ? 'confirmed' : 'pending' }}">
                                <div class="discipline-icon">
                                    <i class="fas fa-{{ $disciplina['icono'] }}"></i>
                                </div>
                                <div class="discipline-info">
                                    <h4>{{ $disciplina['nombre'] }}</h4>
                                    <span class="discipline-status">{{ $disciplina['estado_formateado'] }}</span>
                                </div>
                                <div class="discipline-date">
                                    <small>{{ $disciplina['fecha_inscripcion']->format('d M, h:i A') }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="no-disciplines">
                                <p>No tienes disciplinas inscritas</p>
                            </div>
                        @endforelse

                        <!-- Luego mostrar disciplinas disponibles (solo lectura) -->
                        @foreach ($disciplinasDisponibles as $disciplina)
                            <div class="discipline-item available">
                                <div class="discipline-icon">
                                    <i class="fas fa-{{ $disciplina['icono'] }}"></i>
                                </div>
                                <div class="discipline-info">
                                    <h4>{{ $disciplina['nombre'] }}</h4>
                                    <span class="discipline-status">Disponible</span>
                                    <small>{{ $disciplina['cupos_disponibles'] }} cupos disponibles</small>
                                </div>
                                <div class="discipline-action">
                                    <span class="btn-view"
                                        onclick="window.location.href='{{ route('personal.disciplinas') }}'">
                                        Ver detalles
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Notificaciones -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-bell"></i> Notificaciones Recientes</h3>
                    <a href="{{ route('personal.notificaciones') }}" class="card-action">Ver todas</a>
                </div>
                <div class="card-body">
                    <div class="notifications-list">
                        @forelse($notificaciones as $notificacion)
                            <div class="notification-item {{ $notificacion['clase_tipo'] }}">
                                <div class="notification-icon">
                                    <i class="fas fa-{{ $notificacion['icono'] }}"></i>
                                </div>
                                <div class="notification-content">
                                    <h4>{{ $notificacion['asunto'] }}</h4>
                                    <p>{{ $notificacion['mensaje'] }}</p>
                                    <small>{{ $notificacion['tiempo_transcurrido'] }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="no-notifications">
                                <p>No hay notificaciones recientes</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .no-disciplines,
        .no-notifications {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .status-badge.pending {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .notification-item.success {
            border-left: 4px solid var(--success);
        }

        .notification-item.warning {
            border-left: 4px solid var(--warning);
        }

        .notification-item.info {
            border-left: 4px solid var(--accent-color);
        }

        .notification-item.danger {
            border-left: 4px solid var(--danger);
        }

        /* Estilos para el botón de ver detalles */
        .btn-view {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view:hover {
            background: #0066a1;
            transform: translateY(-1px);
        }

        .discipline-action {
            margin-left: auto;
        }

        /* === ESTILOS PRINCIPALES === */
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Bienvenida */
        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-lg);
        }

        .welcome-content h2 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .welcome-content p {
            opacity: 0.9;
            margin-bottom: 1.5rem;
        }

        .welcome-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .welcome-illustration {
            font-size: 4rem;
            opacity: 0.8;
        }

        /* Estado General */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .status-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s ease;
        }

        .status-card:hover {
            transform: translateY(-2px);
        }

        .status-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .status-card.primary .status-icon {
            background: var(--primary-color);
        }

        .status-card.secondary .status-icon {
            background: var(--secondary-color);
        }

        .status-card.accent .status-icon {
            background: var(--accent-color);
        }

        .status-content h3 {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .status-badge.approved {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .count-display {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .progress-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: conic-gradient(var(--secondary-color) 75%, #E2E8F0 0);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* Grid de Contenido */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .card {
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            color: var(--text-primary);
        }

        .card-action {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Lista de Disciplinas */
        .discipline-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .discipline-item:last-child {
            border-bottom: none;
        }

        .discipline-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .discipline-info {
            flex: 1;
        }

        .discipline-info h4 {
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .discipline-status {
            font-size: 0.8rem;
            padding: 0.2rem 0.5rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .discipline-item.confirmed .discipline-status {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .discipline-item.pending .discipline-status {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .discipline-item.available .discipline-status {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        .btn-add {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-add:hover {
            background: #009975;
            transform: translateY(-1px);
        }

        /* Notificaciones */
        .notification-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notification-item.success .notification-icon {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .notification-item.warning .notification-icon {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .notification-item.info .notification-icon {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        .notification-content h4 {
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
        }

        .notification-content p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .notification-content small {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Acciones Rápidas */
        .quick-actions {
            margin-bottom: 2rem;
        }

        .quick-actions h3 {
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-primary);
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--secondary-color);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .action-card:hover .action-icon {
            background: var(--secondary-color);
            color: white;
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .welcome-card {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .welcome-stats {
                justify-content: center;
            }

            .status-grid {
                grid-template-columns: 1fr;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .discipline-item {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .discipline-info {
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }

            .welcome-stats {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de progreso
            const progressCircle = document.querySelector('.progress-circle');
            if (progressCircle) {
                const progress = progressCircle.getAttribute('data-progress');
                progressCircle.style.background = `conic-gradient(var(--secondary-color) ${progress}%, #E2E8F0 0)`;
            }

            // Interacción con botones de inscripción
            document.querySelectorAll('.btn-add').forEach(btn => {
                btn.addEventListener('click', function() {
                    const discipline = this.closest('.discipline-item').querySelector('h4')
                        .textContent;
                    if (confirm(`¿Deseas inscribirte en ${discipline}?`)) {
                        this.textContent = 'Solicitado';
                        this.disabled = true;
                        this.style.background = '#CBD5E0';

                        // Mostrar notificación de éxito
                        showNotification('Solicitud enviada correctamente', 'success');
                    }
                });
            });

            function showNotification(message, type) {
                // Crear elemento de notificación
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
                <span>${message}</span>
            `;

                // Estilos de la notificación
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

                // Remover después de 3 segundos
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Añadir estilos de animación
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
