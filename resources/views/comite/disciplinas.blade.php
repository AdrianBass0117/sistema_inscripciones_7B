@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-trophy"></i> Gestión de Disciplinas</h1>
                <p>Administra las disciplinas deportivas y culturales del evento</p>
            </div>
        </div>

        <!-- Mostrar mensajes de éxito -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Estadísticas Rápidas -->

        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Total de Disciplinas</h3>
                    <div class="stat-number">{{ $totalDisciplinas }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        {{ $disciplinasActivas }} activas
                    </div>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Inscripciones Totales</h3>
                    <div class="stat-number">{{ number_format($totalInscripciones) }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-arrow-up"></i>
                        Total de participantes
                    </div>
                </div>
            </div>

            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="stat-content">
                    <h3>Disciplinas Expiradas</h3>
                    <div class="stat-number">{{ $disciplinas->where('fecha_fin', '<', now())->count() }}</div>
                    <div class="stat-trend neutral">
                        <i class="fas fa-clock"></i>
                        Período finalizado
                    </div>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Disciplinas Activas</h3>
                    <div class="stat-number">{{ $disciplinasActivas }}/{{ $totalDisciplinas }}</div>
                    <div class="stat-trend {{ $disciplinasActivas == $totalDisciplinas ? 'positive' : 'neutral' }}">
                        <i class="fas fa-{{ $disciplinasActivas == $totalDisciplinas ? 'check' : 'minus' }}"></i>
                        {{ $totalDisciplinas - $disciplinasActivas }} deshabilitadas
                    </div>
                </div>
            </div>

            <div class="stat-card accent">
                <div class="stat-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    <h3>Ocupación Promedio</h3>
                    <div class="stat-number">{{ $ocupacionPromedio }}%</div>
                    <div class="stat-trend {{ $ocupacionPromedio > 50 ? 'positive' : 'neutral' }}">
                        <i class="fas fa-{{ $ocupacionPromedio > 50 ? 'arrow-up' : 'minus' }}"></i>
                        Promedio de ocupación
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Disciplinas -->
        <div class="disciplines-section">
            <div class="section-header">
                <h2><i class="fas fa-list-ul"></i> Todas las Disciplinas</h2>
                <div class="section-filters">
                    <select class="filter-select" id="filterCategory">
                        <option value="">Todas las categorías</option>
                        <option value="Deporte">Deportivas</option>
                        <option value="Cultural">Culturales</option>
                    </select>
                    <select class="filter-select" id="filterGender">
                        <option value="">Todos los géneros</option>
                        <option value="Varonil">Varonil</option>
                        <option value="Femenil">Femenil</option>
                        <option value="Mixto">Mixto</option>
                    </select>
                    <select class="filter-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="active">Activas</option>
                        <option value="inactive">Inactivas</option>
                    </select>
                    <select class="filter-select" id="filterAvailability">
                        <option value="">Extras</option>
                        <option value="cupo_lleno">Cupo Lleno</option>
                        <option value="expirada">Expiradas</option>
                        <option value="no_iniciada">Próximas</option>
                        <option value="sin_fechas">Sin Fechas</option>
                    </select>
                </div>
            </div>

            <div class="disciplines-grid">
                @foreach ($disciplinas as $disciplina)
                    @php
                        $inscritos = $disciplina->inscripciones_aceptadas_count;
                        $porcentajeOcupacion =
                            $disciplina->cupo_maximo > 0
                                ? min(round(($inscritos / $disciplina->cupo_maximo) * 100), 100)
                                : 0;
                        $vacantes = max(0, $disciplina->cupo_maximo - $inscritos);

                        $icono = $disciplina->esDeportiva() ? 'fa-futbol' : 'fa-music';
                        $badgeClass = $disciplina->esDeportiva() ? 'sport' : 'culture';
                        $statusClass = $disciplina->estaActiva() ? 'active' : 'inactive';
                        $estadoDisponibilidad = $disciplina->getEstadoDisponibilidad();
                        $claseEstado = $disciplina->getClaseEstadoDisponibilidad();
                        $textoEstado = $disciplina->getTextoEstadoDisponibilidad();
                        $textoDias = $disciplina->getTextoDiasRestantes();
                    @endphp

                    <div class="discipline-card {{ $statusClass }}" data-category="{{ $disciplina->categoria }}"
                        data-gender="{{ $disciplina->genero }}"
                        data-status="{{ $disciplina->estaActiva() ? 'active' : 'inactive' }}"
                        data-availability="{{ $estadoDisponibilidad }}">
                        <div class="card-header">
                            <div class="discipline-badge {{ $badgeClass }}">
                                {{ $disciplina->getCategoriaFormateada() }}
                            </div>
                            <div class="status-badge {{ $claseEstado }}">
                                {{ $textoEstado }}
                            </div>
                            <div class="card-actions">
                                <div class="card-actions">
                                    <button class="btn-icon toggle-status"
                                        title="{{ $disciplina->estaActiva() ? 'Deshabilitar' : 'Habilitar' }}"
                                        data-id="{{ $disciplina->id_disciplina }}" data-name="{{ $disciplina->nombre }}"
                                        {{ $disciplina->fechaInscripcionExpirada() ? 'disabled' : '' }}>
                                        <i class="fas fa-toggle-{{ $disciplina->estaActiva() ? 'on' : 'off' }}"></i>
                                    </button>
                                    <a href="{{ route('comite.disciplinas-editar', $disciplina->id_disciplina) }}"
                                        class="btn-icon" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('comite.disciplinas-inscritos', $disciplina->id_disciplina) }}"
                                        class="btn-icon" title="Ver inscritos">
                                        <i class="fas fa-users"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="discipline-icon">
                                <i class="fas {{ $icono }}"></i>
                            </div>
                            <div class="discipline-info">
                                <h3>{{ $disciplina->nombre }}</h3>
                                <!-- Agregar información de fechas -->
                                <div class="discipline-dates">
                                    <div class="date-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Inicio:
                                            {{ $disciplina->fecha_inicio ? $disciplina->fecha_inicio->format('d/m/Y') : 'No definida' }}</span>
                                    </div>
                                    <div class="date-item">
                                        <i class="fas fa-calendar-times"></i>
                                        <span>Fin:
                                            {{ $disciplina->fecha_fin ? $disciplina->fecha_fin->format('d/m/Y') : 'No definida' }}</span>
                                    </div>
                                    <div class="date-item highlight">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $disciplina->getTextoDiasRestantes() }}</span>
                                    </div>
                                </div>
                                <p class="discipline-description">{{ $disciplina->descripcion }}</p>

                                <div class="discipline-meta">
                                    <div class="meta-item">
                                        <i class="fas fa-venus-mars"></i>
                                        <span>{{ $disciplina->getGeneroFormateado() }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="fas fa-users"></i>
                                        <span>Cupo máximo: {{ $disciplina->cupo_maximo }}</span>
                                    </div>
                                </div>

                                <div class="capacity-info">
                                    <div class="capacity-bar">
                                        <div class="capacity-fill" style="width: {{ $porcentajeOcupacion }}%"></div>
                                    </div>
                                    <div class="capacity-text">
                                        <span>{{ $inscritos }} / {{ $disciplina->cupo_maximo }} participantes</span>
                                        <span class="percentage">{{ $porcentajeOcupacion }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($disciplinas->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-trophy"></i>
                        <h3>No hay disciplinas registradas</h3>
                        <p>Comienza creando la primera disciplina deportiva o cultural.</p>
                        <a href="{{ route('comite.disciplinas-crear') }}" class="btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            Crear Primera Disciplina
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
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

        /* Estadísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s ease;
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.warning {
            border-left-color: var(--warning);
        }

        .stat-card.info {
            border-left-color: var(--info);
        }

        .stat-card.accent {
            border-left-color: var(--accent-color);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-card.primary .stat-icon {
            background: var(--primary-color);
        }

        .stat-card.success .stat-icon {
            background: var(--success);
        }

        .stat-card.warning .stat-icon {
            background: var(--warning);
        }

        .stat-card.info .stat-icon {
            background: var(--info);
        }

        .stat-card.accent .stat-icon {
            background: var(--accent-color);
        }

        .stat-content h3 {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stat-trend.positive {
            color: var(--success);
        }

        .stat-trend.negative {
            color: var(--danger);
        }

        .stat-trend.neutral {
            color: var(--text-secondary);
        }

        /* Sección de Disciplinas */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-primary);
            font-size: 1.5rem;
        }

        .section-filters {
            display: flex;
            gap: 1rem;
        }

        /* Grid de Disciplinas */
        .disciplines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .discipline-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.3s ease;
            border-top: 4px solid transparent;
        }

        .discipline-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .discipline-card.active {
            border-top-color: var(--success);
        }

        .discipline-card.inactive {
            border-top-color: var(--warning);
            opacity: 0.8;
        }

        .discipline-card .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .discipline-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .discipline-badge.sport {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        .discipline-badge.culture {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .card-actions {
            display: flex;
            gap: 0.5rem;
        }

        .discipline-card .card-body {
            padding: 1.5rem 1.25rem;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .discipline-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .discipline-info {
            flex: 1;
        }

        .discipline-info h3 {
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .discipline-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .discipline-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
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

        .capacity-info {
            margin-top: 1rem;
        }

        .capacity-bar {
            height: 8px;
            background: var(--border-color);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .capacity-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--success));
            transition: width 0.3s ease;
        }

        .capacity-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .percentage {
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Estados de la disciplina */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.active {
            background: rgba(40, 167, 69, 0.1);
            color: var(--success);
        }

        .status-badge.full {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-badge.expired {
            background: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .status-badge.upcoming {
            background: rgba(23, 162, 184, 0.1);
            color: var(--info);
        }

        .status-badge.inactive {
            background: rgba(108, 117, 125, 0.1);
            color: var(--secondary);
        }

        /* Fechas de la disciplina */
        .discipline-dates {
            margin: 1rem 0;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border-left: 3px solid var(--secondary-color);
        }

        .date-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .date-item:last-child {
            margin-bottom: 0;
        }

        .date-item.highlight {
            color: var(--primary-color);
            font-weight: 600;
        }

        .date-item i {
            width: 16px;
            text-align: center;
        }

        /* Estado vacío */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        /* Alertas */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: var(--success);
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

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-icon {
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
            text-decoration: none;
        }

        .btn-icon:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .btn-icon:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Filtros */
        .filter-select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            color: var(--text-primary);
            font-size: 0.9rem;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .section-filters {
                width: 100%;
                justify-content: space-between;
            }

            .disciplines-grid {
                grid-template-columns: 1fr;
            }

            .discipline-card .card-body {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .discipline-meta {
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .section-filters {
                flex-direction: column;
                gap: 0.5rem;
            }

            .card-actions {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const filterCategory = document.getElementById('filterCategory');
            const filterGender = document.getElementById('filterGender');
            const filterStatus = document.getElementById('filterStatus');
            const filterAvailability = document.getElementById('filterAvailability');
            const disciplineCards = document.querySelectorAll('.discipline-card');

            // Aplicar colores a las barras de progreso
            function aplicarColoresBarras() {
                document.querySelectorAll('.progress-fill').forEach(barra => {
                    const porcentaje = parseInt(barra.style.width);
                    const card = barra.closest('.discipline-card');
                    const estadoDisponibilidad = card.getAttribute('data-availability');

                    // Limpiar clases anteriores
                    barra.classList.remove('low', 'medium', 'high', 'full', 'expired', 'upcoming',
                        'no-dates');

                    // Primero verificar estados especiales
                    if (estadoDisponibilidad === 'expirada') {
                        barra.classList.add('expired');
                    } else if (estadoDisponibilidad === 'no_iniciada') {
                        barra.classList.add('upcoming');
                    } else if (estadoDisponibilidad === 'sin_fechas') {
                        barra.classList.add('no-dates');
                    } else {
                        // Colores normales por porcentaje
                        if (porcentaje === 100) {
                            barra.classList.add('full');
                        } else if (porcentaje >= 80) {
                            barra.classList.add('high');
                        } else if (porcentaje >= 50) {
                            barra.classList.add('medium');
                        } else {
                            barra.classList.add('low');
                        }
                    }
                });
            }

            // Aplicar todos los filtros
            function applyFilters() {
                const category = filterCategory.value;
                const gender = filterGender.value;
                const status = filterStatus.value;
                const availability = filterAvailability.value;

                disciplineCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    const cardGender = card.getAttribute('data-gender');
                    const cardStatus = card.getAttribute('data-status');
                    const cardAvailability = card.getAttribute('data-availability');

                    const categoryMatch = !category || cardCategory === category;
                    const genderMatch = !gender || cardGender === gender;
                    const statusMatch = !status || cardStatus === status;
                    const availabilityMatch = !availability || cardAvailability === availability;

                    if (categoryMatch && genderMatch && statusMatch && availabilityMatch) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Event listeners para filtros
            filterCategory.addEventListener('change', applyFilters);
            filterGender.addEventListener('change', applyFilters);
            filterStatus.addEventListener('change', applyFilters);
            filterAvailability.addEventListener('change', applyFilters);

            // Toggle estado activo/inactivo
            document.querySelectorAll('.toggle-status').forEach(btn => {
                btn.addEventListener('click', function() {
                    const disciplineId = this.getAttribute('data-id');
                    const card = this.closest('.discipline-card');
                    const isActive = card.classList.contains('active');

                    // Llamada AJAX
                    fetch(`/Comite/disciplinas/${disciplineId}/toggle-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (isActive) {
                                    card.classList.remove('active');
                                    card.classList.add('inactive');
                                    this.innerHTML = '<i class="fas fa-toggle-off"></i>';
                                    this.title = 'Habilitar';
                                    card.setAttribute('data-status', 'inactive');
                                } else {
                                    card.classList.remove('inactive');
                                    card.classList.add('active');
                                    this.innerHTML = '<i class="fas fa-toggle-on"></i>';
                                    this.title = 'Deshabilitar';
                                    card.setAttribute('data-status', 'active');
                                }
                                showNotification(data.message, 'success');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error al cambiar el estado', 'error');
                        });
                });
            });

            // Mostrar notificación
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            `;

                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : 'var(--danger)'};
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

            // Inicializar colores de barras
            aplicarColoresBarras();

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
            @keyframes progressShine {
                0% { transform: translateX(-100%); }
                100% { transform: translateX(100%); }
            }
        `;
            document.head.appendChild(style);
        });
    </script>
@endsection
