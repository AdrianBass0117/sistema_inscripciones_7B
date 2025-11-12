@extends('participante.layouts.app')

@section('content')
    <div class="inscripciones-content">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>Mis Inscripciones</h1>
                <p>Seguimiento del estado de tus disciplinas seleccionadas</p>
            </div>
            <div class="header-stats">
                <div class="stat-badge total">
                    <i class="fas fa-clipboard-list"></i>
                    <span id="totalActivas">{{ $totalActivas }} disciplinas activas</span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-th"></i>
                    Todas
                </button>
                <button class="filter-tab" data-filter="estado:{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}">
                    <i class="fas fa-check-circle"></i>
                    Aceptadas
                </button>
                <button class="filter-tab" data-filter="estado:{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}">
                    <i class="fas fa-clock"></i>
                    Pendientes
                </button>
                <button class="filter-tab" data-filter="estado:{{ \App\Models\Inscripcion::ESTADO_RECHAZADO }}">
                    <i class="fas fa-exclamation-triangle"></i>
                    Rechazadas
                </button>
                <button class="filter-tab" data-filter="estado:{{ \App\Models\Inscripcion::ESTADO_CANCELADO }}">
                    <i class="fas fa-times-circle"></i>
                    Canceladas
                </button>
            </div>

            <div class="filter-group">
                <div class="filter-select">
                    <select id="categoriaFilter" class="filter-dropdown">
                        <option value="">Todas las categorías</option>
                        <option value="Deporte">Deportivas</option>
                        <option value="Cultural">Culturales</option>
                    </select>
                </div>

                <div class="filter-select">
                    <select id="ordenFilter" class="filter-dropdown">
                        <option value="recientes">Más recientes</option>
                        <option value="antiguas">Más antiguas</option>
                        <option value="nombre_asc">A-Z</option>
                        <option value="nombre_desc">Z-A</option>
                    </select>
                </div>

                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Buscar por disciplina..." class="search-input">
                </div>

                <button class="btn-reset-filters" id="resetFilters">
                    <i class="fas fa-redo"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="quick-stats" id="quickStats">
            <div class="stat-item">
                <span
                    class="stat-number">{{ $inscripciones->where('estado', \App\Models\Inscripcion::ESTADO_ACEPTADO)->count() }}</span>
                <span class="stat-label">Aceptadas</span>
            </div>
            <div class="stat-item">
                <span
                    class="stat-number">{{ $inscripciones->where('estado', \App\Models\Inscripcion::ESTADO_PENDIENTE)->count() }}</span>
                <span class="stat-label">Pendientes</span>
            </div>
            <div class="stat-item">
                <span
                    class="stat-number">{{ $inscripciones->where('estado', \App\Models\Inscripcion::ESTADO_RECHAZADO)->count() }}</span>
                <span class="stat-label">Rechazadas</span>
            </div>
            <div class="stat-item">
                <span
                    class="stat-number">{{ $inscripciones->where('estado', \App\Models\Inscripcion::ESTADO_CANCELADO)->count() }}</span>
                <span class="stat-label">Canceladas</span>
            </div>
        </div>

        <!-- Grid de Inscripciones -->
        <div class="inscripciones-grid" id="inscripcionesGrid">
            @forelse ($inscripciones as $inscripcion)
                @php
                    $iconos = [
                        'Deporte' => 'fa-running',
                        'Cultural' => 'fa-paint-brush',
                        'Fútbol' => 'fa-futbol',
                        'Baloncesto' => 'fa-basketball-ball',
                        'Voleibol' => 'fa-volleyball-ball',
                        'Tenis' => 'fa-table-tennis',
                        'Natación' => 'fa-swimmer',
                        'Atletismo' => 'fa-running',
                        'Béisbol' => 'fa-baseball-ball',
                        'Ajedrez' => 'fa-chess',
                        'Pintura' => 'fa-palette',
                        'Música' => 'fa-music',
                        'Teatro' => 'fa-theater-masks',
                        'Danza' => 'fa-child',
                    ];

                    $icono =
                        $iconos[$inscripcion->disciplina->nombre] ??
                        ($iconos[$inscripcion->disciplina->categoria] ?? 'fa-running');

                    // Determinar clases CSS según el estado
                    $cardClass = '';
                    $statusClass = '';
                    $statusIcon = '';
                    $statusText = $inscripcion->getEstadoFormateado();

                    switch ($inscripcion->estado) {
                        case \App\Models\Inscripcion::ESTADO_ACEPTADO:
                            $cardClass = 'accepted';
                            $statusClass = 'approved';
                            $statusIcon = 'fa-check-circle';
                            break;
                        case \App\Models\Inscripcion::ESTADO_PENDIENTE:
                            $cardClass = 'pending';
                            $statusClass = 'pending';
                            $statusIcon = 'fa-clock';
                            break;
                        case \App\Models\Inscripcion::ESTADO_RECHAZADO:
                            $cardClass = 'action-required';
                            $statusClass = 'action-required';
                            $statusIcon = 'fa-exclamation-triangle';
                            break;
                        case \App\Models\Inscripcion::ESTADO_CANCELADO:
                            $cardClass = 'rejected';
                            $statusClass = 'rejected';
                            $statusIcon = 'fa-times-circle';
                            break;
                    }
                @endphp

                <div class="inscripcion-card {{ $cardClass }}" data-inscripcion-id="{{ $inscripcion->id_inscripcion }}"
                    data-estado="{{ $inscripcion->estado }}" data-categoria="{{ $inscripcion->disciplina->categoria }}"
                    data-disciplina="{{ strtolower($inscripcion->disciplina->nombre) }}"
                    data-fecha="{{ $inscripcion->created_at->timestamp }}">
                    <div class="card-header">
                        <div class="sport-info">
                            <div class="sport-icon">
                                <i class="fas {{ $icono }}"></i>
                            </div>
                            <div class="sport-details">
                                <h3>{{ $inscripcion->disciplina->nombre }}</h3>
                                <span
                                    class="sport-category">{{ $inscripcion->disciplina->getCategoriaFormateada() }}</span>
                                <span class="fecha-inscripcion">
                                    <i class="far fa-calendar"></i>
                                    {{ $inscripcion->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="status-badge {{ $statusClass }}">
                            <i class="fas {{ $statusIcon }}"></i>
                            {{ $statusText }}
                        </div>
                    </div>

                    <!-- Timeline del Proceso -->
                    <div class="process-timeline" id="timeline-{{ $inscripcion->id_inscripcion }}">
                        <div class="timeline-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                            Cargando historial...
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="action-buttons">
                        @if ($inscripcion->estado === \App\Models\Inscripcion::ESTADO_ACEPTADO)
                            <button class="btn btn-primary btn-descargar"
                                data-inscripcion-id="{{ $inscripcion->id_inscripcion }}"
                                data-disciplina="{{ $inscripcion->disciplina->nombre }}">
                                <i class="fas fa-download"></i>
                                Descargar Constancia
                            </button>
                        @endif

                        @if ($inscripcion->estado === \App\Models\Inscripcion::ESTADO_PENDIENTE)
                            <button class="btn btn-warning btn-cancelar"
                                data-inscripcion-id="{{ $inscripcion->id_inscripcion }}"
                                data-disciplina="{{ $inscripcion->disciplina->nombre }}">
                                <i class="fas fa-times"></i>
                                Cancelar Inscripción
                            </button>
                        @endif

                        <button class="btn btn-outline btn-detalles"
                            data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                            <i class="fas fa-info-circle"></i>
                            Ver Detalles
                        </button>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>No tienes inscripciones</h3>
                    <p>Visita la sección de disciplinas para inscribirte en alguna actividad.</p>
                    <a href="{{ route('personal.disciplinas') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Explorar Disciplinas
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Empty State para Filtros -->
        <div class="empty-state filtered-empty" id="filteredEmptyState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No se encontraron inscripciones</h3>
            <p>No hay resultados que coincidan con los filtros aplicados.</p>
            <button class="btn btn-primary" id="resetEmptyFilters">
                <i class="fas fa-redo"></i>
                Mostrar todas las inscripciones
            </button>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div class="modal" id="detallesModal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitulo">Detalles de la Inscripción</h2>
                <button class="modal-close" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-loading" id="modalLoading">
                    <i class="fas fa-spinner fa-spin"></i>
                    Cargando información...
                </div>
                <div class="modal-info" id="modalInfo" style="display: none;">

                    <!-- Información de la Disciplina -->
                    <div class="info-section">
                        <h3><i class="fas fa-info-circle"></i> Información de la Disciplina</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Nombre:</label>
                                <span id="infoNombre"></span>
                            </div>
                            <div class="info-item">
                                <label>Categoría:</label>
                                <span id="infoCategoria"></span>
                            </div>
                            <div class="info-item">
                                <label>Género:</label>
                                <span id="infoGenero"></span>
                            </div>
                            <div class="info-item">
                                <label>Cupo máximo:</label>
                                <span id="infoCupoMaximo"></span>
                            </div>
                            <div class="info-item">
                                <label>Cupos disponibles:</label>
                                <span id="infoCuposDisponibles"></span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de inicio:</label>
                                <span id="infoFechaInicio"></span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de fin:</label>
                                <span id="infoFechaFin"></span>
                            </div>
                            <div class="info-item">
                                <label>Estado disponibilidad:</label>
                                <span id="infoEstadoDisponibilidad"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="info-section">
                        <h3><i class="fas fa-file-alt"></i> Descripción</h3>
                        <p id="infoDescripcion" class="info-description"></p>
                    </div>

                    <!-- Instrucciones -->
                    <div class="info-section">
                        <h3><i class="fas fa-list-alt"></i> Instrucciones</h3>
                        <p id="infoInstrucciones" class="info-instructions"></p>
                    </div>

                    <!-- Información de la Inscripción -->
                    <div class="info-section">
                        <h3><i class="fas fa-clipboard-check"></i> Información de la Inscripción</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Estado:</label>
                                <span id="infoEstadoInscripcion" class="status-badge"></span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de inscripción:</label>
                                <span id="infoFechaInscripcion"></span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de validación:</label>
                                <span id="infoFechaValidacion"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-error" id="modalError" style="display: none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="errorMessage">Error al cargar la información</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="closeModalBtn">Cerrar</button>
            </div>
        </div>
    </div>

    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: var(--bg-white);
            margin: 2rem auto;
            max-width: 700px;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--bg-light);
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #E2E8F0;
            color: var(--text-primary);
        }

        .modal-body {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: flex-end;
            background: var(--bg-light);
        }

        .modal-loading,
        .modal-error {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .modal-error {
            color: var(--error);
        }

        .modal-error i {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        /* Info Sections */
        .info-section {
            margin-bottom: 2rem;
        }

        .info-section:last-child {
            margin-bottom: 0;
        }

        .info-section h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-section h3 i {
            color: var(--primary-color);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-item label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .info-item span {
            color: var(--text-primary);
            font-size: 1rem;
        }

        .info-description,
        .info-instructions {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                margin: 1rem;
                max-height: calc(100vh - 2rem);
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1rem 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Timeline del Proceso */
        .process-timeline {
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 12px;
        }

        .timeline-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem 0;
            position: relative;
        }

        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 24px;
            top: 48px;
            bottom: -1rem;
            width: 2px;
            background: #E2E8F0;
        }

        .timeline-marker {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            z-index: 2;
        }

        .timeline-item.completed .timeline-marker {
            background: var(--success);
            color: white;
        }

        .timeline-item.current .timeline-marker {
            background: var(--primary-color);
            color: white;
            animation: pulse 2s infinite;
        }

        .timeline-item.upcoming .timeline-marker {
            background: #E2E8F0;
            color: var(--text-secondary);
        }

        .timeline-item .timeline-marker.error {
            background: var(--error);
            color: white;
        }

        .timeline-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .timeline-content span {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .rejection-reason {
            color: var(--error);
            font-size: 0.9rem;
            margin-top: 0.5rem;
            font-style: italic;
        }

        .timeline-loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .timeline-loading i {
            margin-right: 0.5rem;
        }

        .timeline-error {
            text-align: center;
            padding: 2rem;
            color: var(--error);
        }

        .timeline-connector {
            height: 20px;
            margin-left: 24px;
            border-left: 2px solid #E2E8F0;
        }

        /* Animaciones */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 79, 110, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(0, 79, 110, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 79, 110, 0);
            }
        }

        /* Responsive para timeline */
        @media (max-width: 768px) {
            .timeline-item::after {
                left: 20px !important;
            }

            .timeline-marker {
                width: 40px;
                height: 40px;
            }

            .timeline-connector {
                margin-left: 20px;
            }
        }

        .inscripciones-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1rem 0;
        }

        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .header-content p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .stat-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
        }

        .stat-badge.total {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        /* Estilos para los filtros */
        .filters-section {
            background: var(--bg-white);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-light);
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .filter-tab:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .filter-tab.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            position: relative;
        }

        .filter-dropdown {
            padding: 0.75rem 2.5rem 0.75rem 1rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            background: var(--bg-white);
            color: var(--text-primary);
            font-size: 0.9rem;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
        }

        .filter-dropdown:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn-reset-filters {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: transparent;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .btn-reset-filters:hover {
            border-color: var(--error);
            color: var(--error);
        }

        /* Estadísticas rápidas */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            background: var(--bg-white);
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary-color);
        }

        .stat-number {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Grid de Inscripciones */
        .inscripciones-grid {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Cards de Inscripción */
        .inscripcion-card {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        .inscripcion-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .inscripcion-card.accepted {
            border-left-color: var(--success);
        }

        .inscripcion-card.pending {
            border-left-color: var(--warning);
        }

        .inscripcion-card.action-required {
            border-left-color: var(--error);
        }

        .inscripcion-card.rejected {
            border-left-color: #718096;
            opacity: 0.8;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        .sport-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sport-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .sport-details h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .sport-category {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .fecha-inscripcion {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .status-badge {
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge.approved {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .status-badge.pending {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .status-badge.action-required {
            background: rgba(229, 62, 62, 0.1);
            color: var(--error);
        }

        .status-badge.rejected {
            background: rgba(113, 128, 150, 0.1);
            color: #718096;
        }

        /* Timeline del Proceso */
        .process-timeline {
            margin: 2rem 0;
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 12px;
        }

        .timeline-loading {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .timeline-loading i {
            margin-right: 0.5rem;
        }

        /* Botones de Acción */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #003D58;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background: #B7791F;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid #E2E8F0;
        }

        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Estados vacíos */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            grid-column: 1 / -1;
        }

        .filtered-empty {
            display: none;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        /* Animaciones */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 79, 110, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(0, 79, 110, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 79, 110, 0);
            }
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                min-width: auto;
            }

            .filter-tabs {
                justify-content: center;
            }

            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .filter-tabs {
                flex-direction: column;
            }

            .filter-tab {
                justify-content: center;
            }

            .quick-stats {
                grid-template-columns: 1fr;
            }

            .inscripcion-card {
                padding: 1.5rem;
            }

            .sport-info {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }

            .process-timeline {
                padding: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let currentFilters = {
                estado: 'all',
                categoria: '',
                orden: 'recientes',
                busqueda: ''
            };

            // Inicializar filtros
            initFilters();

            // Cargar historial para cada inscripción
            document.querySelectorAll('.inscripcion-card').forEach(card => {
                const inscripcionId = card.getAttribute('data-inscripcion-id');
                cargarHistorialInscripcion(inscripcionId);
            });

            // Event Listeners para filtros
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const filterValue = this.getAttribute('data-filter');

                    // Remover active de todas las pestañas
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove(
                        'active'));
                    // Activar la pestaña clickeada
                    this.classList.add('active');

                    if (filterValue === 'all') {
                        currentFilters.estado = 'all';
                    } else if (filterValue.startsWith('estado:')) {
                        currentFilters.estado = filterValue.replace('estado:', '');
                    }

                    aplicarFiltros();
                });
            });

            document.getElementById('categoriaFilter').addEventListener('change', function() {
                currentFilters.categoria = this.value;
                aplicarFiltros();
            });

            document.getElementById('ordenFilter').addEventListener('change', function() {
                currentFilters.orden = this.value;
                aplicarFiltros();
            });

            document.getElementById('searchInput').addEventListener('input', function() {
                currentFilters.busqueda = this.value.toLowerCase().trim();
                aplicarFiltros();
            });

            document.getElementById('resetFilters').addEventListener('click', resetFilters);
            document.getElementById('resetEmptyFilters').addEventListener('click', resetFilters);

            function initFilters() {
                // Configurar valores iniciales
                document.querySelector('[data-filter="all"]').classList.add('active');
                document.getElementById('categoriaFilter').value = '';
                document.getElementById('ordenFilter').value = 'recientes';
                document.getElementById('searchInput').value = '';
            }

            function aplicarFiltros() {
                const cards = document.querySelectorAll('.inscripcion-card');
                let visibleCount = 0;
                let totalActivas = 0;

                cards.forEach(card => {
                    const estado = card.getAttribute('data-estado');
                    const categoria = card.getAttribute('data-categoria');
                    const disciplina = card.getAttribute('data-disciplina');
                    const fecha = parseInt(card.getAttribute('data-fecha'));
                    const nombre = card.querySelector('h3').textContent.toLowerCase();

                    let matchesEstado = true;
                    let matchesCategoria = true;
                    let matchesBusqueda = true;

                    // Filtro por estado
                    if (currentFilters.estado !== 'all' && estado !== currentFilters.estado) {
                        matchesEstado = false;
                    }

                    // Filtro por categoría
                    if (currentFilters.categoria && categoria !== currentFilters.categoria) {
                        matchesCategoria = false;
                    }

                    // Filtro por búsqueda
                    if (currentFilters.busqueda) {
                        const matchesNombre = nombre.includes(currentFilters.busqueda);
                        const matchesDisciplina = disciplina.includes(currentFilters.busqueda);
                        matchesBusqueda = matchesNombre || matchesDisciplina;
                    }

                    if (matchesEstado && matchesCategoria && matchesBusqueda) {
                        card.style.display = 'block';
                        visibleCount++;

                        // Contar activas para el contador
                        if (estado === '{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}' ||
                            estado === '{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}') {
                            totalActivas++;
                        }
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Ordenar resultados
                ordenarResultados();

                // Actualizar estadísticas
                document.getElementById('totalActivas').textContent = `${totalActivas} disciplinas activas`;

                // Mostrar/ocultar estados vacíos
                const emptyState = document.getElementById('filteredEmptyState');
                const mainGrid = document.getElementById('inscripcionesGrid');

                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                    mainGrid.style.display = 'none';
                } else {
                    emptyState.style.display = 'none';
                    mainGrid.style.display = 'grid';
                }
            }

            function ordenarResultados() {
                const grid = document.getElementById('inscripcionesGrid');
                const cards = Array.from(document.querySelectorAll('.inscripcion-card[style="display: block"]'));

                cards.sort((a, b) => {
                    const fechaA = parseInt(a.getAttribute('data-fecha'));
                    const fechaB = parseInt(b.getAttribute('data-fecha'));
                    const nombreA = a.querySelector('h3').textContent.toLowerCase();
                    const nombreB = b.querySelector('h3').textContent.toLowerCase();

                    switch (currentFilters.orden) {
                        case 'recientes':
                            return fechaB - fechaA;
                        case 'antiguas':
                            return fechaA - fechaB;
                        case 'nombre_asc':
                            return nombreA.localeCompare(nombreB);
                        case 'nombre_desc':
                            return nombreB.localeCompare(nombreA);
                        default:
                            return fechaB - fechaA;
                    }
                });

                // Reordenar en el DOM
                cards.forEach(card => {
                    grid.appendChild(card);
                });
            }

            function resetFilters() {
                currentFilters = {
                    estado: 'all',
                    categoria: '',
                    orden: 'recientes',
                    busqueda: ''
                };

                // Reset UI
                document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
                document.querySelector('[data-filter="all"]').classList.add('active');
                document.getElementById('categoriaFilter').value = '';
                document.getElementById('ordenFilter').value = 'recientes';
                document.getElementById('searchInput').value = '';

                aplicarFiltros();
            }

            // ... (el resto de las funciones existentes: cargarHistorialInscripcion, descargarConstancia, etc.)
            async function cargarHistorialInscripcion(inscripcionId) {
                try {
                    const response = await fetch(`/personal/inscripciones/${inscripcionId}/historial`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const timeline = document.getElementById(`timeline-${inscripcionId}`);
                        timeline.innerHTML = generarHTMLTimeline(data.historial);
                    } else {
                        document.getElementById(`timeline-${inscripcionId}`).innerHTML =
                            '<div class="timeline-error">Error al cargar el historial</div>';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    document.getElementById(`timeline-${inscripcionId}`).innerHTML =
                        '<div class="timeline-error">Error al cargar el historial</div>';
                }
            }

            function generarHTMLTimeline(historial) {
                let html = '';
                historial.forEach((item, index) => {
                    const isLast = index === historial.length - 1;
                    html += `
                        <div class="timeline-item ${item.clase}">
                            <div class="timeline-marker ${item.clase.includes('error') ? 'error' : ''}">
                                <i class="fas fa-${item.icono}"></i>
                            </div>
                            <div class="timeline-content">
                                <h4>${item.titulo}</h4>
                                <span>${item.fecha || ''}</span>
                            </div>
                        </div>
                        ${!isLast ? '<div class="timeline-connector"></div>' : ''}
                    `;
                });
                return html;
            }

            async function descargarConstancia(inscripcionId, disciplina) {
                try {
                    showNotification(`Generando constancia para ${disciplina}...`, 'info');

                    const url = `/personal/inscripciones/${inscripcionId}/descargar-constancia`;
                    window.open(url, '_blank');

                    // Si la ventana se bloquea, mostrar instrucciones
                    if (!newWindow || newWindow.closed || typeof newWindow.closed == 'undefined') {
                        showNotification(
                            'La descarga se abrió en una nueva ventana. Si no se abre, permite ventanas emergentes.',
                            'info');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al generar la constancia', 'error');
                }
            }

            async function cancelarInscripcion(inscripcionId, disciplina) {
                if (!confirm(
                        `¿Estás seguro de que quieres cancelar tu inscripción pendiente en ${disciplina}?`)) {
                    return;
                }

                try {
                    const response = await fetch('/personal/disciplinas/cancelar', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            id_inscripcion: inscripcionId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showNotification(`Inscripción en ${disciplina} cancelada correctamente`, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message, 'warning');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al cancelar la inscripción', 'error');
                }
            }

            function showNotification(message, type) {
                // Tu código existente de notificación
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'exclamation-circle' : 'info'}-circle"></i>
                    <span>${message}</span>
                `;

                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'success' ? 'var(--success)' : type === 'warning' ? 'var(--warning)' : type === 'error' ? 'var(--error)' : 'var(--accent-color)'};
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

            // Botones de acción
            document.querySelectorAll('.btn-descargar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    const disciplina = this.getAttribute('data-disciplina');
                    descargarConstancia(inscripcionId, disciplina);
                });
            });

            document.querySelectorAll('.btn-cancelar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    const disciplina = this.getAttribute('data-disciplina');
                    cancelarInscripcion(inscripcionId, disciplina);
                });
            });

            // Reemplaza la función existente del botón detalles
            document.querySelectorAll('.btn-detalles').forEach(btn => {
                btn.addEventListener('click', function() {
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    abrirModalDetalles(inscripcionId);
                });
            });

            // Función para abrir el modal de detalles
            async function abrirModalDetalles(inscripcionId) {
                const modal = document.getElementById('detallesModal');
                const modalLoading = document.getElementById('modalLoading');
                const modalInfo = document.getElementById('modalInfo');
                const modalError = document.getElementById('modalError');

                // Mostrar modal y loading
                modal.style.display = 'block';
                modalLoading.style.display = 'block';
                modalInfo.style.display = 'none';
                modalError.style.display = 'none';

                try {
                    const response = await fetch(`/personal/inscripciones/${inscripcionId}/detalles`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Llenar la información en el modal
                        llenarModalConDatos(data.data);
                        modalLoading.style.display = 'none';
                        modalInfo.style.display = 'block';
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    modalLoading.style.display = 'none';
                    modalError.style.display = 'block';
                    document.getElementById('errorMessage').textContent = error.message ||
                        'Error al cargar la información';
                }
            }

            // Función para llenar el modal con los datos
            function llenarModalConDatos(data) {
                const {
                    disciplina,
                    inscripcion
                } = data;

                // Información de la disciplina
                document.getElementById('infoNombre').textContent = disciplina.nombre;
                document.getElementById('infoCategoria').textContent = disciplina.categoria_formateada;
                document.getElementById('infoGenero').textContent = disciplina.genero_formateado;
                document.getElementById('infoCupoMaximo').textContent = disciplina.cupo_maximo;
                document.getElementById('infoCuposDisponibles').textContent = disciplina.cupos_disponibles;
                document.getElementById('infoFechaInicio').textContent = disciplina.fecha_inicio;
                document.getElementById('infoFechaFin').textContent = disciplina.fecha_fin;
                document.getElementById('infoEstadoDisponibilidad').textContent = disciplina
                    .texto_estado_disponibilidad;
                document.getElementById('infoDescripcion').textContent = disciplina.descripcion ||
                    'No hay descripción disponible';
                document.getElementById('infoInstrucciones').textContent = disciplina.instrucciones ||
                    'No hay instrucciones disponibles';

                // Información de la inscripción
                const estadoBadge = document.getElementById('infoEstadoInscripcion');
                estadoBadge.textContent = inscripcion.estado_formateado;
                estadoBadge.className = 'status-badge ' + obtenerClaseEstado(inscripcion.estado);

                document.getElementById('infoFechaInscripcion').textContent = inscripcion.fecha_inscripcion;
                document.getElementById('infoFechaValidacion').textContent = inscripcion.fecha_validacion ||
                    'Pendiente';

                // Actualizar título del modal
                document.getElementById('modalTitulo').textContent = `Detalles: ${disciplina.nombre}`;
            }

            // Función auxiliar para obtener clase CSS del estado
            function obtenerClaseEstado(estado) {
                switch (estado) {
                    case '{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}':
                        return 'approved';
                    case '{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}':
                        return 'pending';
                    case '{{ \App\Models\Inscripcion::ESTADO_RECHAZADO }}':
                        return 'action-required';
                    case '{{ \App\Models\Inscripcion::ESTADO_CANCELADO }}':
                        return 'rejected';
                    default:
                        return '';
                }
            }

            // Cerrar modal
            document.getElementById('closeModal').addEventListener('click', cerrarModal);
            document.getElementById('closeModalBtn').addEventListener('click', cerrarModal);
            document.querySelector('.modal-overlay').addEventListener('click', cerrarModal);

            function cerrarModal() {
                document.getElementById('detallesModal').style.display = 'none';
            }

            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    cerrarModal();
                }
            });
        });
    </script>
@endsection
