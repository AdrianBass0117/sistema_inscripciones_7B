@extends('participante.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Encabezado del Historial -->
        <div class="history-header">
            <div class="header-content">
                <h1><i class="fas fa-history"></i> Mi Historial de Participación</h1>
                <p>Consulta tu historial de actividades y eventos</p>
            </div>
            <div class="header-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $estadisticas['total_inscripciones'] }}</span>
                    <span class="stat-label">Total Inscripciones</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $estadisticas['total_participaciones'] }}</span>
                    <span class="stat-label">Participaciones</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $estadisticas['disciplinas_diferentes'] }}</span>
                    <span class="stat-label">Disciplinas Diferentes</span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="history-controls">
            <div class="filter-controls">
                <select id="statusFilter" class="filter-select">
                    <option value="all">Todos los estados</option>
                    <option value="participo">Participé</option>
                    <option value="no_participo">No participé</option>
                </select>
                <select id="disciplineFilter" class="filter-select">
                    <option value="all">Todas las disciplinas</option>
                    @foreach ($inscripcionesHistorial->pluck('historialDisciplina.categoria')->unique() as $categoria)
                        <option value="{{ $categoria }}">{{ $categoria }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Grid de Disciplinas -->
        <div class="disciplines-grid" id="disciplinesGrid">
            @forelse($inscripcionesHistorial as $inscripcion)
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

                    $disciplinaNombre = $inscripcion->historialDisciplina->nombre_disciplina;
                    $categoria = $inscripcion->historialDisciplina->categoria;
                    $icono = $iconos[$disciplinaNombre] ?? ($iconos[$categoria] ?? 'fa-running');

                    $statusClass = $inscripcion->participo ? 'participo' : 'no-participo';
                    $statusText = $inscripcion->participo ? 'Participé' : 'No participé';
                @endphp

                <div class="discipline-card" data-status="{{ $statusClass }}"
                    data-discipline="{{ strtolower($categoria) }}"
                    data-inscripcion-id="{{ $inscripcion->id_historial_inscripcion }}">
                    <div class="card-header">
                        <div class="discipline-icon">
                            <i class="fas {{ $icono }}"></i>
                        </div>
                        <div class="discipline-info">
                            <h3>{{ $disciplinaNombre }}</h3>
                            <p class="discipline-meta">
                                {{ $categoria }} •
                                {{ $inscripcion->historialDisciplina->getGeneroFormateado() }} •
                                {{ $inscripcion->historialDisciplina->periodo_inicio->format('M Y') }}
                            </p>
                        </div>
                        <div class="card-actions">
                            <span class="status-badge {{ $statusClass }}">
                                <i class="fas {{ $inscripcion->participo ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $statusText }}
                            </span>
                            <button class="btn-details" data-inscripcion-id="{{ $inscripcion->id_historial_inscripcion }}"
                                title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>No tienes historial de participaciones</h3>
                    <p>Tu historial aparecerá aquí una vez que completes actividades.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal para detalles -->
    <div class="modal" id="detailsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Detalles de Participación</h3>
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>

    <style>
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* === ENCABEZADO DEL HISTORIAL === */
        .history-header {
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .history-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .history-header::after {
            content: "";
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .header-content h1 {
            font-size: 2.25rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1.15rem;
            position: relative;
            z-index: 1;
        }

        .header-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.25rem;
            margin-top: 2rem;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            display: block;
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        /* === CONTROLES Y FILTROS === */
        .history-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-controls {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 0.75rem 1.25rem;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background: var(--bg-white);
            color: var(--text-primary);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            min-width: 180px;
        }

        .filter-select:hover {
            border-color: var(--secondary-color);
            box-shadow: 0 4px 12px rgba(0, 170, 139, 0.15);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.2);
        }

        /* === GRID DE DISCIPLINAS === */
        .disciplines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.75rem;
        }

        .discipline-card {
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid #E2E8F0;
            position: relative;
        }

        .discipline-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .discipline-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .discipline-card:hover::before {
            transform: scaleX(1);
        }

        .card-header {
            padding: 1.75rem;
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
        }

        .discipline-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .discipline-card:hover .discipline-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .discipline-info {
            flex: 1;
        }

        .discipline-info h3 {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            font-size: 1.3rem;
            font-weight: 700;
        }

        .discipline-meta {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .card-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .status-badge.participe {
            background: rgba(56, 161, 105, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .status-badge.no-participe {
            background: rgba(214, 158, 46, 0.15);
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-details {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .btn-details:hover {
            background: linear-gradient(135deg, #003D58, #008c72);
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* === MODAL === */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 550px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.75rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px 16px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.75rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.2s ease;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 1.75rem;
        }

        .detail-section {
            margin-bottom: 2rem;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section h4 {
            margin: 0 0 1.25rem 0;
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 700;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .detail-item {
            margin-bottom: 1.25rem;
            padding: 1rem;
            border-radius: 10px;
            background: #f8fafc;
            transition: background 0.2s ease;
        }

        .detail-item:hover {
            background: #f1f5f9;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-label::before {
            content: "•";
            color: var(--secondary-color);
            font-size: 1.2rem;
        }

        .detail-value {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.5;
        }

        .error-message {
            text-align: center;
            padding: 2rem;
            color: var(--danger);
        }

        .error-message i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.7;
        }

        /* === ESTADO VACÍO === */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            border: 2px dashed #E2E8F0;
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* === RESPONSIVE === */
        @media (max-width: 1024px) {
            .disciplines-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .history-header {
                padding: 2rem 1.5rem;
                border-radius: 16px;
            }

            .header-content h1 {
                font-size: 1.75rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .header-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .history-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-controls {
                flex-direction: column;
                width: 100%;
            }

            .filter-select {
                min-width: 100%;
            }

            .disciplines-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .card-header {
                flex-direction: column;
                text-align: center;
                gap: 1.25rem;
            }

            .card-actions {
                flex-direction: row;
                justify-content: center;
                width: 100%;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-content {
                padding: 0.75rem;
            }

            .history-header {
                padding: 1.5rem 1rem;
            }

            .header-content h1 {
                font-size: 1.5rem;
            }

            .header-stats {
                grid-template-columns: 1fr;
            }

            .stat-item {
                padding: 1rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .discipline-card {
                border-radius: 12px;
            }

            .card-header {
                padding: 1.5rem;
            }

            .discipline-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .modal-header {
                padding: 1.25rem;
            }

            .modal-body {
                padding: 1.25rem;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-icon {
                font-size: 4rem;
            }
        }

        /* === ANIMACIONES ADICIONALES === */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .discipline-card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        .discipline-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .discipline-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .discipline-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .discipline-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .discipline-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .discipline-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .discipline-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .discipline-card:nth-child(8) {
            animation-delay: 0.8s;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const modal = document.getElementById('detailsModal');
            const modalBody = document.getElementById('modalBody');
            const modalClose = document.getElementById('modalClose');

            // Filtros
            const statusFilter = document.getElementById('statusFilter');
            const disciplineFilter = document.getElementById('disciplineFilter');
            const disciplineCards = document.querySelectorAll('.discipline-card');

            function applyFilters() {
                const selectedStatus = statusFilter.value;
                const selectedDiscipline = disciplineFilter.value;

                disciplineCards.forEach(card => {
                    const cardStatus = card.getAttribute('data-status');
                    const cardDiscipline = card.getAttribute('data-discipline');
                    let showCard = true;

                    if (selectedStatus !== 'all' && cardStatus !== selectedStatus) {
                        showCard = false;
                    }

                    if (selectedDiscipline !== 'all' && cardDiscipline !== selectedDiscipline) {
                        showCard = false;
                    }

                    card.style.display = showCard ? 'block' : 'none';
                });
            }

            statusFilter.addEventListener('change', applyFilters);
            disciplineFilter.addEventListener('change', applyFilters);

            // Modal functionality
            document.querySelectorAll('.btn-details').forEach(btn => {
                btn.addEventListener('click', function() {
                    const inscripcionId = this.getAttribute('data-inscripcion-id');
                    cargarDetallesInscripcion(inscripcionId);
                });
            });

            modalClose.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });

            async function cargarDetallesInscripcion(inscripcionId) {
                try {
                    const response = await fetch(`/personal/historial/detalles/${inscripcionId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        mostrarModal(data.data);
                    } else {
                        mostrarError('No se pudieron cargar los detalles');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    mostrarError('Error al cargar los detalles');
                }
            }

            function mostrarModal(datos) {
                const {
                    disciplina,
                    inscripcion
                } = datos;

                modalBody.innerHTML = `
                    <div class="detail-section">
                        <h4>Información de la Disciplina</h4>
                        <div class="detail-item">
                            <div class="detail-label">Nombre</div>
                            <div class="detail-value">${disciplina.nombre}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Categoría</div>
                            <div class="detail-value">${disciplina.categoria}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Género</div>
                            <div class="detail-value">${disciplina.genero}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Descripción</div>
                            <div class="detail-value">${disciplina.descripcion || 'No disponible'}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Período</div>
                            <div class="detail-value">${disciplina.periodo_inicio} - ${disciplina.periodo_fin}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Cupo</div>
                            <div class="detail-value">${disciplina.total_inscritos} / ${disciplina.cupo_maximo} inscritos</div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4>Información de tu Participación</h4>
                        <div class="detail-item">
                            <div class="detail-label">Fecha de Inscripción</div>
                            <div class="detail-value">${inscripcion.fecha_inscripcion}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Estado de Inscripción</div>
                            <div class="detail-value">${inscripcion.estado_inscripcion}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Participación</div>
                            <div class="detail-value">
                                <span class="status-badge ${inscripcion.participo ? 'participe' : 'no-participe'}">
                                    <i class="fas ${inscripcion.participo ? 'fa-check-circle' : 'fa-times-circle'}"></i>
                                    ${inscripcion.participo ? 'Sí participé' : 'No participé'}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Fecha de Finalización</div>
                            <div class="detail-value">${disciplina.fecha_finalizacion}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Estado de Finalización</div>
                            <div class="detail-value">${disciplina.estado_finalizacion}</div>
                        </div>
                    </div>
                `;

                modal.style.display = 'flex';
            }

            function mostrarError(mensaje) {
                modalBody.innerHTML = `
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>${mensaje}</p>
                    </div>
                `;
                modal.style.display = 'flex';
            }
        });
    </script>
@endsection
