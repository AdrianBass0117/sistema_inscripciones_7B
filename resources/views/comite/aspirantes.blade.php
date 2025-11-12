@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-users"></i> Gestión de Aspirantes</h1>
                <p>Administra las solicitudes de participación en las disciplinas</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary" id="btnActualizar">
                    <i class="fas fa-sync-alt"></i>
                    Actualizar
                </button>
                <a href="{{ route('comite.verificacion-constancias') }}" class="btn-secondary">
                    <i class="fas fa-shield-check"></i>
                    Verificar Constancias
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-card">
            <div class="card-header">
                <h3><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
                <button class="btn-text" id="clearFilters">
                    <i class="fas fa-times"></i>
                    Limpiar Filtros
                </button>
            </div>
            <div class="card-body">
                <div class="filters-grid">
                    <!-- Estado -->
                    <div class="filter-group">
                        <label class="filter-label">Estado</label>
                        <select class="filter-select" id="filterStatus">
                            <option value="">Todos los estados</option>
                            <option value="{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}">Pendiente</option>
                            <option value="{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}">Aceptado</option>
                            <option value="{{ \App\Models\Inscripcion::ESTADO_RECHAZADO }}">Rechazado</option>
                        </select>
                    </div>

                    <!-- Disciplina -->
                    <div class="filter-group">
                        <label class="filter-label">Disciplina</label>
                        <select class="filter-select" id="filterDiscipline">
                            <option value="">Todas las disciplinas</option>
                            @foreach ($disciplinas as $disciplina)
                                <option value="{{ $disciplina->id_disciplina }}">{{ $disciplina->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Género -->
                    <div class="filter-group">
                        <label class="filter-label">Género</label>
                        <select class="filter-select" id="filterGender">
                            <option value="">Todos los géneros</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contador de Resultados -->
        <div class="results-header">
            <h3 id="resultsCount">{{ $contadores['total'] }} aspirantes encontrados</h3>
            <div class="view-options">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="quick-stats">
            <div class="stat-item pendientes">
                <span class="stat-number">{{ $contadores['pendientes'] }}</span>
                <span class="stat-label">Pendientes</span>
            </div>
            <div class="stat-item aceptados">
                <span class="stat-number">{{ $contadores['aceptados'] }}</span>
                <span class="stat-label">Aceptados</span>
            </div>
            <div class="stat-item rechazados">
                <span class="stat-number">{{ $contadores['rechazados'] }}</span>
                <span class="stat-label">Rechazados</span>
            </div>
        </div>

        <!-- Grid de Aspirantes -->
        <div class="aspirants-grid" id="aspirantsView">
            @forelse($inscripciones as $inscripcion)
                @php
                    $usuario = $inscripcion->usuario;
                    $disciplina = $inscripcion->disciplina;

                    // Determinar clases según estado
                    $cardClass = '';
                    $statusClass = '';
                    $statusText = $inscripcion->getEstadoFormateado();

                    switch ($inscripcion->estado) {
                        case \App\Models\Inscripcion::ESTADO_PENDIENTE:
                            $cardClass = 'pending';
                            $statusClass = 'pending';
                            break;
                        case \App\Models\Inscripcion::ESTADO_ACEPTADO:
                            $cardClass = 'accepted';
                            $statusClass = 'accepted';
                            break;
                        case \App\Models\Inscripcion::ESTADO_RECHAZADO:
                            $cardClass = 'rejected';
                            $statusClass = 'rejected';
                            break;
                    }

                    // Calcular antigüedad (ejemplo)
                    $antiguedad = $usuario->created_at->diffInYears(now());
                    $rangoAntiguedad =
                        $antiguedad <= 2 ? '0-2' : ($antiguedad <= 5 ? '3-5' : ($antiguedad <= 10 ? '6-10' : '10+'));
                @endphp

                <div class="aspirant-card {{ $cardClass }}" data-status="{{ $inscripcion->estado }}"
                    data-discipline="{{ $disciplina->id_disciplina }}" data-gender="{{ $disciplina->genero }}"
                    data-seniority="{{ $rangoAntiguedad }}" data-date="{{ $inscripcion->created_at->timestamp }}"
                    data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                    <div class="card-header">
                        <div class="status-badge {{ $statusClass }}">{{ $statusText }}</div>
                        <div class="card-actions">
                            <a href="{{ route('comite.cuentas-aspirantes', ['id' => $usuario->id_usuario, 'inscripcion' => $inscripcion->id_inscripcion]) }}"
                                title="Ver perfil completo">
                                <button class="btn-icon">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="aspirant-photo">
                            @php
                                // Buscar la fotografía del usuario
                                $fotografia = $usuario
                                    ->documentos()
                                    ->where('tipo_documento', 'Fotografía')
                                    ->where('estado', 'Aprobado')
                                    ->first();

                                // Si no hay aprobada, buscar cualquier fotografía
                                if (!$fotografia) {
                                    $fotografia = $usuario
                                        ->documentos()
                                        ->where('tipo_documento', 'Fotografía')
                                        ->first();
                                }
                            @endphp

                            @if ($fotografia)
                                <img src="{{ route('comite.documentos.ver', $fotografia->id_documento) }}"
                                    alt="Foto de {{ $usuario->nombre_completo }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <i class="fas fa-user" style="display: none;"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                        <div class="aspirant-info">
                            <h3>{{ $usuario->nombre_completo }}</h3>
                            <div class="aspirant-meta">
                                <div class="meta-item">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ $disciplina->nombre }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-venus-mars"></i>
                                    <span>{{ $disciplina->getGeneroFormateado() }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>Inscrito: {{ $inscripcion->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($inscripcion->estaPendiente())
                            <div class="full-width">
                                <div class="status-message pending">
                                    <i class="fas fa-clock"></i>
                                    Revisaremos esta solicitud en cuanto tengamos tiempo
                                </div>
                            </div>
                        @elseif($inscripcion->estaAceptada())
                            <button class="btn-secondary full-width" disabled>
                                <i class="fas fa-check-circle"></i>
                                Aceptado
                            </button>
                        @elseif($inscripcion->estaRechazada())
                            <button class="btn-warning full-width btn-reconsiderar"
                                data-inscripcion-id="{{ $inscripcion->id_inscripcion }}"
                                data-nombre="{{ $usuario->nombre_completo }}">
                                <i class="fas fa-redo"></i>
                                Reconsiderar
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>No hay inscripciones</h3>
                    <p>No se encontraron inscripciones de aspirantes.</p>
                </div>
            @endforelse
        </div>

        <!-- Estado vacío para filtros -->
        <div class="empty-state filtered-empty" id="filteredEmptyState" style="display: none;">
            <div class="empty-icon">
                <i class="fas fa-search"></i>
            </div>
            <h3>No se encontraron resultados</h3>
            <p>No hay aspirantes que coincidan con los filtros aplicados.</p>
            <button class="btn btn-primary" id="resetEmptyFilters">
                <i class="fas fa-redo"></i>
                Mostrar todos los aspirantes
            </button>
        </div>
    </div>

    <style>
        .aspirant-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            border: 3px solid white;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            position: relative;
        }

        .aspirant-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .aspirants-grid.list-view .aspirant-photo {
            width: 60px;
            height: 60px;
            font-size: 1.2rem;
            margin-bottom: 0;
            margin-right: 1rem;
        }

        .aspirants-grid.list-view .aspirant-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* === ESTILOS GENERALES === */
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
        }

        .header-content h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-content p {
            color: var(--text-secondary);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Filtros */
        .filters-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .filters-card .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filters-card .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin: 0;
        }

        .btn-text {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-text:hover {
            background: rgba(0, 170, 139, 0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.1);
        }

        /* Resultados Header */
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .results-header h3 {
            color: var(--text-primary);
            font-size: 1.1rem;
            margin: 0;
        }

        .view-options {
            display: flex;
            gap: 0.5rem;
            background: var(--bg-light);
            padding: 0.25rem;
            border-radius: 8px;
        }

        .view-btn {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .view-btn.active {
            background: white;
            color: var(--primary-color);
            box-shadow: var(--shadow-sm);
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

        .stat-item.pendientes {
            border-left-color: var(--warning);
        }

        .stat-item.aceptados {
            border-left-color: var(--success);
        }

        .stat-item.rechazados {
            border-left-color: var(--danger);
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

        /* Grid de Aspirantes */
        .aspirants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .aspirants-grid.list-view {
            grid-template-columns: 1fr;
        }

        .aspirants-grid.list-view .aspirant-card {
            flex-direction: row;
            align-items: center;
        }

        .aspirants-grid.list-view .aspirant-card .card-body {
            flex-direction: row;
            align-items: center;
            flex: 1;
        }

        .aspirants-grid.list-view .aspirant-photo {
            margin-right: 1rem;
            margin-bottom: 0;
        }

        .aspirant-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.3s ease;
            border-top: 4px solid transparent;
        }

        .aspirant-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .aspirant-card.pending {
            border-top-color: var(--warning);
        }

        .aspirant-card.accepted {
            border-top-color: var(--success);
        }

        .aspirant-card.rejected {
            border-top-color: var(--danger);
        }

        .aspirant-card .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-badge.pending {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .status-badge.accepted {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .status-badge.rejected {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .aspirant-card .card-body {
            padding: 1.5rem 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .aspirant-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            border: 3px solid white;
            box-shadow: var(--shadow-sm);
        }

        .aspirant-info h3 {
            font-size: 1.1rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .aspirant-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            width: 100%;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .meta-item i {
            width: 16px;
            color: var(--primary-color);
        }

        .aspirant-card .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 0.75rem;
            background: var(--bg-light);
        }

        .full-width {
            width: 100%;
        }

        /* Mensajes de estado */
        .status-message {
            font-size: 0.8rem;
            color: var(--text-secondary);
            text-align: center;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 6px;
            margin-top: 0.5rem;
        }

        .status-message.pending {
            color: var(--warning);
            background: rgba(214, 158, 46, 0.05);
        }

        /* Botones */
        .btn-primary,
        .btn-secondary,
        .btn-warning {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-primary:hover,
        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .filters-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .aspirants-grid {
                grid-template-columns: 1fr;
            }

            .aspirants-grid.list-view .aspirant-card {
                flex-direction: column;
            }

            .aspirants-grid.list-view .aspirant-card .card-body {
                flex-direction: column;
                text-align: center;
            }

            .aspirant-card .card-footer {
                flex-direction: column;
            }

            .results-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .view-options {
                align-self: flex-end;
            }

            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .header-actions {
                flex-direction: column;
            }

            .aspirant-meta {
                font-size: 0.8rem;
            }

            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Cambio de vista (Grid/Lista)
            const viewButtons = document.querySelectorAll('.view-btn');
            const aspirantsView = document.getElementById('aspirantsView');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const viewType = this.getAttribute('data-view');
                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    aspirantsView.classList.toggle('list-view', viewType === 'list');
                });
            });

            // Filtros
            const filterSelects = document.querySelectorAll('.filter-select');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const aspirantCards = document.querySelectorAll('.aspirant-card');
            const resetEmptyFiltersBtn = document.getElementById('resetEmptyFilters');

            function applyFilters() {
                const status = document.getElementById('filterStatus').value;
                const discipline = document.getElementById('filterDiscipline').value;
                const gender = document.getElementById('filterGender').value;

                let visibleCount = 0;

                aspirantCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');
                    const cardDiscipline = card.getAttribute('data-discipline');
                    const cardGender = card.getAttribute('data-gender');

                    const statusMatch = !status || cardStatus === status;
                    const disciplineMatch = !discipline || cardDiscipline === discipline;
                    const genderMatch = !gender || cardGender === gender;

                    if (statusMatch && disciplineMatch && genderMatch) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                updateResultsCount(visibleCount);
            }

            function updateResultsCount(count) {
                document.getElementById('resultsCount').textContent = `${count} aspirantes encontrados`;

                const emptyState = document.getElementById('filteredEmptyState');
                const mainGrid = document.getElementById('aspirantsView');

                if (count === 0) {
                    emptyState.style.display = 'block';
                    mainGrid.style.display = 'none';
                } else {
                    emptyState.style.display = 'none';
                    mainGrid.style.display = 'grid';
                }
            }

            filterSelects.forEach(select => {
                select.addEventListener('change', applyFilters);
            });

            clearFiltersBtn.addEventListener('click', function() {
                filterSelects.forEach(select => {
                    select.value = '';
                });
                applyFilters();
            });

            resetEmptyFiltersBtn.addEventListener('click', function() {
                filterSelects.forEach(select => {
                    select.value = '';
                });
                applyFilters();
            });

            // Acciones de aceptar/rechazar/reconsiderar
            document.addEventListener('click', function(e) {
                if (e.target.closest('.btn-aceptar')) {
                    const btn = e.target.closest('.btn-aceptar');
                    aceptarInscripcion(btn);
                } else if (e.target.closest('.btn-rechazar')) {
                    const btn = e.target.closest('.btn-rechazar');
                    rechazarInscripcion(btn);
                } else if (e.target.closest('.btn-reconsiderar')) {
                    const btn = e.target.closest('.btn-reconsiderar');
                    reconsiderarInscripcion(btn);
                }
            });

            async function aceptarInscripcion(btn) {
                const inscripcionId = btn.getAttribute('data-inscripcion-id');
                const nombre = btn.getAttribute('data-nombre');

                if (!confirm(`¿Estás seguro de aceptar a ${nombre}?`)) {
                    return;
                }

                try {
                    const response = await fetch(`/comite/inscripciones/${inscripcionId}/aceptar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        actualizarEstadoInscripcion(inscripcionId, data.nuevo_estado, data.estado_formateado);
                        showNotification(`Inscripción de ${nombre} aceptada correctamente`, 'success');
                    } else {
                        showNotification(data.message, 'danger');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al aceptar la inscripción', 'danger');
                }
            }

            async function rechazarInscripcion(btn) {
                const inscripcionId = btn.getAttribute('data-inscripcion-id');
                const nombre = btn.getAttribute('data-nombre');

                if (!confirm(`¿Estás seguro de rechazar a ${nombre}?`)) {
                    return;
                }

                try {
                    const response = await fetch(`/comite/inscripciones/${inscripcionId}/rechazar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        actualizarEstadoInscripcion(inscripcionId, data.nuevo_estado, data.estado_formateado);
                        showNotification(`Inscripción de ${nombre} rechazada correctamente`, 'danger');
                    } else {
                        showNotification(data.message, 'danger');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al rechazar la inscripción', 'danger');
                }
            }

            async function reconsiderarInscripcion(btn) {
                const inscripcionId = btn.getAttribute('data-inscripcion-id');
                const nombre = btn.getAttribute('data-nombre');

                if (!confirm(`¿Estás seguro de reconsiderar a ${nombre}?`)) {
                    return;
                }

                try {
                    const response = await fetch(`/comite/inscripciones/${inscripcionId}/reconsiderar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        actualizarEstadoInscripcion(inscripcionId, data.nuevo_estado, data.estado_formateado);
                        showNotification(`Inscripción de ${nombre} puesta en reconsideración`, 'warning');
                    } else {
                        showNotification(data.message, 'danger');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al reconsiderar la inscripción', 'danger');
                }
            }

            function actualizarEstadoInscripcion(inscripcionId, nuevoEstado, estadoFormateado) {
                const card = document.querySelector(`[data-inscripcion-id="${inscripcionId}"]`);
                const statusBadge = card.querySelector('.status-badge');
                const footer = card.querySelector('.card-footer');
                const nombre = card.querySelector('h3').textContent;

                // Actualizar clases y atributos
                card.classList.remove('pending', 'accepted', 'rejected');
                card.setAttribute('data-status', nuevoEstado);

                // Actualizar badge de estado
                statusBadge.textContent = estadoFormateado;
                statusBadge.className = 'status-badge ' +
                    (nuevoEstado === '{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}' ? 'pending' :
                        nuevoEstado === '{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}' ? 'accepted' : 'rejected'
                    );

                // Actualizar footer según nuevo estado
                if (nuevoEstado === '{{ \App\Models\Inscripcion::ESTADO_PENDIENTE }}') {
                    footer.innerHTML = `
                    <button class="btn-success full-width btn-aceptar"
                            data-inscripcion-id="${inscripcionId}"
                            data-nombre="${nombre}">
                        <i class="fas fa-check"></i>
                        Aceptar
                    </button>
                    <button class="btn-danger full-width btn-rechazar"
                            data-inscripcion-id="${inscripcionId}"
                            data-nombre="${nombre}">
                        <i class="fas fa-times"></i>
                        Rechazar
                    </button>
                `;
                } else if (nuevoEstado === '{{ \App\Models\Inscripcion::ESTADO_ACEPTADO }}') {
                    footer.innerHTML = `
                    <button class="btn-secondary full-width" disabled>
                        <i class="fas fa-check-circle"></i>
                        Aceptado
                    </button>
                `;
                } else if (nuevoEstado === '{{ \App\Models\Inscripcion::ESTADO_RECHAZADO }}') {
                    footer.innerHTML = `
                    <button class="btn-warning full-width btn-reconsiderar"
                            data-inscripcion-id="${inscripcionId}"
                            data-nombre="${nombre}">
                        <i class="fas fa-redo"></i>
                        Reconsiderar
                    </button>
                `;
                }

                // Actualizar contadores
                actualizarEstadisticas();
            }

            function actualizarEstadisticas() {
                // Aquí podrías hacer una llamada AJAX para actualizar las estadísticas
                // o recalcularlas localmente
                console.log('Estadísticas actualizadas');
            }

            // Botones de header
            document.getElementById('btnActualizar').addEventListener('click', function() {
                location.reload();
            });

            document.getElementById('btnExportar').addEventListener('click', function() {
                showNotification('Función de exportación en desarrollo', 'info');
            });

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle"></i>
                <span>${message}</span>
            `;

                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'danger' ? 'var(--danger)' : type === 'warning' ? 'var(--warning)' : 'var(--accent-color)'};
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
