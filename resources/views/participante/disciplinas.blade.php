@extends('participante.layouts.app')

@section('content')
    <div class="disciplines-content">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>Disciplinas Disponibles</h1>
                <p>Selecciona hasta {{ $maxDisciplinas ?? 2 }} disciplinas para participar</p>
            </div>
            <div class="header-stats">
                <div class="stat-badge">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $selectedCount ?? 0 }}/{{ $maxDisciplinas ?? 2 }} seleccionadas</span>
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
                <button class="filter-tab" data-filter="selected">
                    <i class="fas fa-check"></i>
                    Seleccionadas
                </button>
                <button class="filter-tab" data-filter="available">
                    <i class="fas fa-plus"></i>
                    Disponibles
                </button>
                <button class="filter-tab" data-filter="deporte">
                    <i class="fas fa-running"></i>
                    Deportivas
                </button>
                <button class="filter-tab" data-filter="cultural">
                    <i class="fas fa-paint-brush"></i>
                    Culturales
                </button>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar disciplinas..." class="search-input">
            </div>
        </div>

        <!-- Grid de Disciplinas -->
        <div class="disciplines-grid" id="disciplinesGrid">
            @foreach ($disciplinas as $disciplina)
                @php
                    $inscripcionUsuario = $inscripcionesUsuario
                        ->where('id_disciplina', $disciplina->id_disciplina)
                        ->where('estado', '!=', \App\Models\Inscripcion::ESTADO_CANCELADO)
                        ->first();
                    $estaInscrito = $inscripcionUsuario !== null;
                    $estaSeleccionado =
                        $estaInscrito &&
                        in_array($inscripcionUsuario->estado, [
                            \App\Models\Inscripcion::ESTADO_PENDIENTE,
                            \App\Models\Inscripcion::ESTADO_ACEPTADO,
                        ]);
                    $estaPendiente = $estaInscrito && $inscripcionUsuario->estaPendiente();
                    $estaAceptado = $estaInscrito && $inscripcionUsuario->estaAceptada();
                    $estaCancelado = $estaInscrito && $inscripcionUsuario->estaCancelada();

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

                    $icono = $iconos[$disciplina->nombre] ?? ($iconos[$disciplina->categoria] ?? 'fa-running');

                    // Determinar estado de disponibilidad
                    $estadoDisponibilidad = $disciplina->getEstadoDisponibilidad();
                    $puedeInscribirse = $estadoDisponibilidad === 'disponible' && !$estaSeleccionado;
                    $esProximamente = $estadoDisponibilidad === 'no_iniciada';
                    $cupoLleno = $estadoDisponibilidad === 'cupo_lleno';
                    $expirada = $estadoDisponibilidad === 'expirada';
                @endphp

                <div class="discipline-card {{ $estaSeleccionado ? 'selected' : ($puedeInscribirse ? 'available' : 'unavailable') }}"
                    data-category="{{ strtolower($disciplina->categoria) }}"
                    data-discipline-id="{{ $disciplina->id_disciplina }}" data-discipline-name="{{ $disciplina->nombre }}"
                    data-availability="{{ $estadoDisponibilidad }}">
                    <div class="card-header">
                        <div class="sport-icon">
                            <i class="fas {{ $icono }}"></i>
                        </div>
                        <div class="card-actions">
                            @if ($estaSeleccionado)
                                @if ($estaPendiente)
                                    <button class="btn-remove"
                                        data-inscripcion-id="{{ $inscripcionUsuario->id_inscripcion }}"
                                        data-discipline-name="{{ $disciplina->nombre }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @else
                                    <button class="btn-locked" disabled title="No puedes cancelar inscripciones aceptadas">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            @else
                                @if ($puedeInscribirse)
                                    <button class="btn-add" data-discipline-id="{{ $disciplina->id_disciplina }}"
                                        data-discipline-name="{{ $disciplina->nombre }}">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                @elseif($esProximamente)
                                    <button class="btn-locked" disabled title="Inscripciones próximamente">
                                        <i class="fas fa-clock"></i>
                                    </button>
                                @elseif($cupoLleno)
                                    <button class="btn-locked" disabled title="Cupo lleno">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                @elseif($expirada)
                                    <button class="btn-locked" disabled title="Inscripciones cerradas">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                @else
                                    <button class="btn-locked" disabled title="No disponible">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            @endif
                            <button class="btn-view" data-discipline-id="{{ $disciplina->id_disciplina }}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-content">
                        <h3>{{ $disciplina->nombre }}</h3>
                        <p>{{ $disciplina->descripcion ?? 'Sin descripción disponible' }}</p>

                        <!-- Información de fechas -->
                        @if ($disciplina->fecha_inicio && $disciplina->fecha_fin)
                            <div class="date-info">
                                <div class="date-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Inicio: {{ $disciplina->fecha_inicio->format('d/m/Y') }}</span>
                                </div>
                                <div class="date-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Fin: {{ $disciplina->fecha_fin->format('d/m/Y') }}</span>
                                </div>
                                <div class="date-item days-remaining">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>{{ $disciplina->getTextoDiasRestantes() }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="discipline-meta">
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $disciplina->cupo_maximo }} cupos</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-{{ $disciplina->esDeportiva() ? 'running' : 'paint-brush' }}"></i>
                                <span>{{ $disciplina->getCategoriaFormateada() }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-venus-mars"></i>
                                <span>{{ $disciplina->getGeneroFormateado() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="availability">
                            <div class="slots-info">
                                <span class="slots-text">
                                    Cupos: {{ $disciplina->inscripciones_aceptadas_count }}/{{ $disciplina->cupo_maximo }}
                                </span>
                                <div class="slots-bar">
                                    <div class="slots-fill {{ $disciplina->porcentaje_ocupado > 80 ? 'warning' : '' }}"
                                        style="width: {{ $disciplina->porcentaje_ocupado }}%"></div>
                                </div>
                            </div>
                            <div
                                class="status-badge
                                @if ($estaAceptado) confirmed
                                @elseif($estaPendiente) pending
                                @elseif($puedeInscribirse) available
                                @elseif($esProximamente) upcoming
                                @elseif($cupoLleno) full
                                @elseif($expirada) expired
                                @else disabled @endif">
                                @if ($estaAceptado)
                                    <i class="fas fa-check"></i>
                                    Confirmado
                                @elseif($estaPendiente)
                                    <i class="fas fa-clock"></i>
                                    En revisión
                                @elseif($puedeInscribirse)
                                    @if ($disciplina->cupos_disponibles <= 3)
                                        Últimos cupos
                                    @else
                                        Disponible
                                    @endif
                                @elseif($esProximamente)
                                    <i class="fas fa-clock"></i>
                                    Próximamente
                                @elseif($cupoLleno)
                                    <i class="fas fa-times"></i>
                                    Cupo lleno
                                @elseif($expirada)
                                    <i class="fas fa-ban"></i>
                                    Cerrada
                                @else
                                    <i class="fas fa-times"></i>
                                    No disponible
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mensaje para disciplinas próximamente -->
                    @if ($esProximamente && !$estaSeleccionado)
                        <div class="upcoming-message">
                            <i class="fas fa-info-circle"></i>
                            <span>Las inscripciones estarán disponibles a partir del
                                {{ $disciplina->fecha_inicio->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No se encontraron disciplinas</h3>
            <p>Intenta con otros filtros o términos de búsqueda</p>
            <button class="btn-reset-filters">Restablecer filtros</button>
        </div>
    </div>

    <!-- Modal Detalles de Disciplina -->
    <div id="disciplineModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="discipline-icon-large">
                        <i class="fas" id="modalIcon"></i>
                    </div>
                    <div>
                        <h3 id="modalDisciplineName">Cargando...</h3>
                        <span class="discipline-category" id="modalCategory">Cargando...</span>
                    </div>
                </div>
                <button class="modal-close" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-grid">
                    <!-- Columna Izquierda - Información General -->
                    <div class="modal-column">
                        <div class="info-section">
                            <h4><i class="fas fa-info-circle"></i> Información General</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Descripción:</label>
                                    <p id="modalDescription">Cargando descripción...</p>
                                </div>
                                <div class="info-item">
                                    <label>Cupo Máximo:</label>
                                    <span id="modalCapacity">Cargando...</span>
                                </div>
                                <div class="info-item">
                                    <label>Género:</label>
                                    <span id="modalGender">Cargando...</span>
                                </div>
                                <div class="info-item">
                                    <label>Categoría:</label>
                                    <span id="modalCategoryFull">Cargando...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Nueva sección de fechas en el modal -->
                        <div class="info-section">
                            <h4><i class="fas fa-calendar-alt"></i> Fechas de Inscripción</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <label>Inicio:</label>
                                    <span id="modalStartDate">Cargando...</span>
                                </div>
                                <div class="info-item">
                                    <label>Fin:</label>
                                    <span id="modalEndDate">Cargando...</span>
                                </div>
                                <div class="info-item">
                                    <label>Estado:</label>
                                    <span id="modalDateStatus">Cargando...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha - Requisitos y Documentos -->
                    <div class="modal-column">
                        <div class="info-section">
                            <h4><i class="fas fa-clipboard-list"></i> Instrucciones Especiales</h4>
                            <div class="instructions-content">
                                <p id="modalInstructions">Cargando instrucciones...</p>
                            </div>
                        </div>

                        <div class="info-section">
                            <h4><i class="fas fa-chart-bar"></i> Disponibilidad</h4>
                            <div class="availability-info">
                                <div class="slots-display">
                                    <span class="slots-text" id="modalSlotsText">Cargando...</span>
                                    <div class="slots-bar-large">
                                        <div class="slots-fill-large" id="modalSlotsFill" style="width: 0%"></div>
                                    </div>
                                    <span class="slots-percentage" id="modalSlotsPercentage">Cargando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-secondary" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                    Cerrar
                </button>
                <button class="btn-primary" id="enrollBtn" style="display: none;">
                    <i class="fas fa-plus"></i>
                    Inscribirse
                </button>
                <button class="btn-upcoming" id="upcomingBtn" style="display: none;">
                    <i class="fas fa-clock"></i>
                    Próximamente
                </button>
            </div>
        </div>
    </div>

    <style>
        .disciplines-content {
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
            background: rgba(0, 170, 139, 0.1);
            color: var(--secondary-color);
            border-radius: 12px;
            font-weight: 600;
        }

        .stat-badge i {
            font-size: 1.1rem;
        }

        /* Filtros */
        .filters-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--bg-white);
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
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

        .search-box {
            position: relative;
            min-width: 300px;
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
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
        }

        /* Grid de Disciplinas */
        .disciplines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Cards de Disciplina */
        .discipline-card {
            background: var(--bg-white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .discipline-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .discipline-card.selected {
            border-color: var(--secondary-color);
            background: linear-gradient(135deg, var(--bg-white) 0%, rgba(0, 170, 139, 0.05) 100%);
        }

        .discipline-card.available {
            border-color: #E2E8F0;
        }

        .discipline-card.unavailable {
            opacity: 0.7;
            border-color: #E2E8F0;
        }

        .discipline-card.unavailable:hover {
            transform: none;
            box-shadow: var(--shadow-md);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
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

        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .btn-add,
        .btn-remove,
        .btn-view,
        .btn-locked {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 1rem;
        }

        .btn-add {
            background: var(--secondary-color);
            color: white;
        }

        .btn-add:hover {
            background: #009975;
            transform: scale(1.1);
        }

        .btn-remove {
            background: rgba(229, 62, 62, 0.1);
            color: var(--error);
        }

        .btn-remove:hover {
            background: var(--error);
            color: white;
            transform: scale(1.1);
        }

        .btn-view {
            background: var(--primary-color);
            color: white;
        }

        .btn-view:hover {
            background: #003D58;
            transform: scale(1.1);
        }

        .btn-locked {
            background: #E2E8F0;
            color: #94A3B8;
            cursor: not-allowed;
        }

        .card-content h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .card-content p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        /* Información de fechas */
        .date-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .date-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .date-item:last-child {
            margin-bottom: 0;
        }

        .date-item i {
            width: 16px;
            color: var(--primary-color);
        }

        .days-remaining {
            font-weight: 600;
            color: var(--accent-color) !important;
        }

        .discipline-meta {
            display: flex;
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .meta-item i {
            width: 16px;
            color: var(--primary-color);
        }

        .card-footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #F1F5F9;
        }

        .availability {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .slots-info {
            flex: 1;
        }

        .slots-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            display: block;
        }

        .slots-bar {
            height: 6px;
            background: #E2E8F0;
            border-radius: 3px;
            overflow: hidden;
        }

        .slots-fill {
            height: 100%;
            background: var(--secondary-color);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .slots-fill.warning {
            background: var(--warning);
        }

        .status-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .status-badge.confirmed {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .status-badge.pending {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .status-badge.available {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        .status-badge.upcoming {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .status-badge.full {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .status-badge.expired {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .status-badge.disabled {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
        }

        /* Mensaje para disciplinas próximamente */
        .upcoming-message {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffecb5;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: #856404;
        }

        .upcoming-message i {
            color: #ffc107;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
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

        .btn-reset-filters {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-reset-filters:hover {
            background: #003D58;
            transform: translateY(-1px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: white;
            margin: 2% auto;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.3s ease;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .discipline-icon-large {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .modal-title h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
        }

        .discipline-category {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #F1F5F9;
            color: var(--primary-color);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .modal-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .info-section {
            background: #F8FAFC;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #E2E8F0;
        }

        .info-section h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            color: var(--text-primary);
            margin: 0 0 1rem 0;
            font-weight: 600;
        }

        .info-section h4 i {
            color: var(--secondary-color);
            width: 16px;
        }

        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-item label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item p,
        .info-item span {
            font-size: 0.9rem;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.4;
        }

        .instructions-content {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
        }

        .instructions-content p {
            margin: 0;
            color: var(--text-primary);
            line-height: 1.5;
        }

        .availability-info {
            text-align: center;
        }

        .slots-display {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .slots-text {
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        .slots-bar-large {
            height: 8px;
            background: #E2E8F0;
            border-radius: 4px;
            overflow: hidden;
        }

        .slots-fill-large {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), #009975);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .slots-percentage {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #E2E8F0;
            background: #F8FAFC;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .btn-secondary,
        .btn-primary,
        .btn-upcoming {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-secondary {
            background: white;
            color: var(--text-primary);
            border: 2px solid #E2E8F0;
        }

        .btn-secondary:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #009975;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 170, 139, 0.3);
        }

        .btn-upcoming {
            background: #ffc107;
            color: #856404;
            cursor: not-allowed;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-tabs {
                justify-content: center;
            }

            .search-box {
                min-width: auto;
            }

            .disciplines-grid {
                grid-template-columns: 1fr;
            }

            .discipline-card {
                padding: 1.25rem;
            }

            .modal-content {
                margin: 5% auto;
                width: 95%;
            }

            .modal-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .modal-header {
                padding: 1.25rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                padding: 1.25rem;
                flex-direction: column;
            }

            .modal-title {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .filter-tabs {
                flex-direction: column;
            }

            .filter-tab {
                justify-content: center;
            }

            .availability {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .status-badge {
                align-self: flex-start;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const disciplineModal = document.getElementById('disciplineModal');
            const closeModalBtn = document.getElementById('closeModal');
            const closeModalBtn2 = document.getElementById('closeModalBtn');
            const enrollBtn = document.getElementById('enrollBtn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let currentDisciplineId = null;

            // Botones de vista para modal
            document.querySelectorAll('.btn-view').forEach(btn => {
                btn.addEventListener('click', function() {
                    const disciplineId = this.getAttribute('data-discipline-id');
                    openDisciplineModal(disciplineId);
                });
            });

            async function openDisciplineModal(disciplineId) {
                currentDisciplineId = disciplineId;

                try {
                    const response = await fetch(`/personal/disciplinas/${disciplineId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const discipline = data.disciplina;

                        // Llenar modal con datos
                        document.getElementById('modalIcon').className =
                            `fas ${getDisciplineIcon(discipline.nombre, discipline.categoria)}`;
                        document.getElementById('modalDisciplineName').textContent = discipline.nombre;
                        document.getElementById('modalCategory').textContent = discipline.categoria_formateada;
                        document.getElementById('modalDescription').textContent = discipline.descripcion ||
                            'Sin descripción disponible';
                        document.getElementById('modalCapacity').textContent =
                            `${discipline.cupo_maximo} participantes`;
                        document.getElementById('modalGender').textContent = discipline.genero_formateado;
                        document.getElementById('modalCategoryFull').textContent = discipline
                            .categoria_formateada;
                        document.getElementById('modalInstructions').textContent = discipline.instrucciones ||
                            'No hay instrucciones especiales.';

                        // Nueva información de fechas
                        document.getElementById('modalStartDate').textContent =
                            discipline.fecha_inicio_formateada || 'No definida';
                        document.getElementById('modalEndDate').textContent =
                            discipline.fecha_fin_formateada || 'No definida';
                        document.getElementById('modalDateStatus').textContent =
                            discipline.texto_estado_disponibilidad || 'No disponible';

                        // Actualizar disponibilidad
                        document.getElementById('modalSlotsText').textContent =
                            `${discipline.inscripciones_aceptadas}/${discipline.cupo_maximo} cupos ocupados`;
                        document.getElementById('modalSlotsFill').style.width =
                            `${discipline.porcentaje_ocupado}%`;
                        document.getElementById('modalSlotsPercentage').textContent =
                            `${discipline.porcentaje_ocupado}% ocupado`;

                        // Mostrar/ocultar botones según disponibilidad
                        const card = document.querySelector(`[data-discipline-id="${disciplineId}"]`);
                        const isSelected = card.classList.contains('selected');
                        const availability = card.getAttribute('data-availability');

                        enrollBtn.style.display = 'none';
                        upcomingBtn.style.display = 'none';

                        if (!isSelected) {
                            if (availability === 'disponible') {
                                enrollBtn.style.display = 'flex';
                            } else if (availability === 'no_iniciada') {
                                upcomingBtn.style.display = 'flex';
                            }
                        }

                        // Mostrar modal
                        disciplineModal.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    } else {
                        showNotification('Error al cargar la disciplina', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al cargar la información', 'error');
                }
            }

            function getDisciplineIcon(disciplinaNombre, categoria) {
                const iconos = {
                    'Deporte': 'fa-running',
                    'Cultural': 'fa-paint-brush',
                    'Fútbol': 'fa-futbol',
                    'Baloncesto': 'fa-basketball-ball',
                    'Voleibol': 'fa-volleyball-ball',
                    'Tenis': 'fa-table-tennis',
                    'Natación': 'fa-swimmer',
                    'Atletismo': 'fa-running',
                    'Béisbol': 'fa-baseball-ball',
                    'Ajedrez': 'fa-chess',
                    'Pintura': 'fa-palette',
                    'Música': 'fa-music',
                    'Teatro': 'fa-theater-masks',
                    'Danza': 'fa-child'
                };

                return iconos[disciplinaNombre] || iconos[categoria] || 'fa-running';
            }

            // Cerrar modal
            function closeModal() {
                disciplineModal.style.display = 'none';
                document.body.style.overflow = 'auto';
                currentDisciplineId = null;
            }

            closeModalBtn.addEventListener('click', closeModal);
            closeModalBtn2.addEventListener('click', closeModal);

            // Cerrar al hacer click fuera del modal
            disciplineModal.addEventListener('click', function(e) {
                if (e.target === disciplineModal) {
                    closeModal();
                }
            });

            // Botón de inscribirse en el modal
            enrollBtn.addEventListener('click', async function() {
                if (!currentDisciplineId) return;

                await inscribirEnDisciplina(currentDisciplineId);
            });

            // Botones de añadir/remover en las cards
            document.querySelectorAll('.btn-add').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const disciplineId = this.getAttribute('data-discipline-id');
                    await inscribirEnDisciplina(disciplineId);
                });
            });

            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', async function() {
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    const disciplineName = this.getAttribute('data-discipline-name');

                    await cancelarInscripcion(inscripcionId, disciplineName);
                });
            });

            async function inscribirEnDisciplina(disciplineId) {
                try {
                    const response = await fetch('/personal/disciplinas/inscribir', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            id_disciplina: disciplineId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        showNotification(data.message, 'success');
                        closeModal();
                        // Recargar la página para actualizar el estado
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message, 'warning');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al realizar la inscripción', 'error');
                }
            }

            async function cancelarInscripcion(inscripcionId, disciplineName) {
                if (!confirm(`¿Estás seguro de que quieres cancelar tu inscripción en ${disciplineName}?`)) {
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
                        showNotification(data.message, 'success');
                        // Recargar la página para actualizar el estado
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(data.message, 'warning');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al cancelar la inscripción', 'error');
                }
            }

            // Sistema de filtros
            const filterTabs = document.querySelectorAll('.filter-tab');
            const searchInput = document.querySelector('.search-input');
            const disciplinesGrid = document.getElementById('disciplinesGrid');
            const emptyState = document.getElementById('emptyState');
            const disciplineCards = document.querySelectorAll('.discipline-card');
            const btnResetFilters = document.querySelector('.btn-reset-filters');

            let currentFilter = 'all';
            let currentSearch = '';

            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = filter;
                    applyFilters();
                });
            });

            searchInput.addEventListener('input', function() {
                currentSearch = this.value.toLowerCase().trim();
                applyFilters();
            });

            if (btnResetFilters) {
                btnResetFilters.addEventListener('click', function() {
                    filterTabs.forEach(tab => tab.classList.remove('active'));
                    document.querySelector('[data-filter="all"]').classList.add('active');
                    searchInput.value = '';
                    currentFilter = 'all';
                    currentSearch = '';
                    applyFilters();
                });
            }

            function applyFilters() {
                let visibleCount = 0;

                disciplineCards.forEach(card => {
                    const category = card.getAttribute('data-category');
                    const title = card.querySelector('h3').textContent.toLowerCase();
                    const isSelected = card.classList.contains('selected');
                    const isAvailable = card.classList.contains('available');

                    let matchesFilter = false;
                    let matchesSearch = title.includes(currentSearch);

                    switch (currentFilter) {
                        case 'all':
                            matchesFilter = true;
                            break;
                        case 'selected':
                            matchesFilter = isSelected;
                            break;
                        case 'available':
                            matchesFilter = isAvailable;
                            break;
                        case 'deporte':
                            matchesFilter = category === 'deporte';
                            break;
                        case 'cultural':
                            matchesFilter = category === 'cultural';
                            break;
                    }

                    if (matchesFilter && matchesSearch) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                    disciplinesGrid.style.display = 'none';
                } else {
                    emptyState.style.display = 'none';
                    disciplinesGrid.style.display = 'grid';
                }
            }

            function showNotification(message, type) {
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

            // Añadir estilos de animación
            const animationStyle = document.createElement('style');
            animationStyle.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(animationStyle);

            // Aplicar filtros iniciales
            applyFilters();
        });
    </script>
@endsection
