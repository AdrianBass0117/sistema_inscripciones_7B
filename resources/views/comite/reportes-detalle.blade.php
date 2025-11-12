@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-file-alt"></i> Reporte Detallado de Disciplina</h1>
                <p>Información completa de participantes por período</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('comite.reportes') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Reportes
                </a>
                <button class="btn-primary">
                    <i class="fas fa-download"></i>
                    Exportar
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-reporte" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Cargando información de la disciplina...</p>
        </div>

        <!-- Main Content -->
        <div id="reporte-content" style="display: none;">
            <!-- Información de la Disciplina -->
            <div class="info-section">
                <div class="info-card">
                    <div class="info-header">
                        <h2 id="disciplina-nombre"></h2>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Categoría:</strong>
                            <span id="disciplina-categoria"></span>
                        </div>
                        <div class="info-item">
                            <strong>Género:</strong>
                            <span id="disciplina-genero"></span>
                        </div>
                        <div class="info-item">
                            <strong>Cupo máximo:</strong>
                            <span id="disciplina-cupo"></span>
                        </div>
                        <div class="info-item">
                            <strong>Estado:</strong>
                            <span id="disciplina-estado-texto"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtro de Períodos -->
            <div id="filtro-periodos-section" class="filters-section" style="display: none;">
                <div class="filters-card">
                    <h3><i class="fas fa-calendar-alt"></i> Seleccionar Período</h3>
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label class="filter-label">Período</label>
                            <select class="filter-select" id="periodoFilter">
                                <option value="actual">Período Actual</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensaje para disciplinas desactivadas -->
            <div id="disciplina-desactivada" class="warning-message" style="display: none;">
                <div class="warning-content">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <h3>Disciplina Desactivada</h3>
                        <p>Esta disciplina se encuentra actualmente desactivada. Solo puedes ver información de períodos
                            anteriores.</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div id="estadisticas-section" class="stats-grid" style="display: none;">
                <!-- Las estadísticas se cargarán dinámicamente -->
            </div>

            <!-- Lista de Participantes -->
            <div class="participantes-section">
                <div class="section-header">
                    <h2><i class="fas fa-users"></i> Lista de Participantes</h2>
                    <div class="section-filters">
                        <input type="text" class="search-input" id="searchParticipantes"
                            placeholder="Buscar por nombre o email...">
                        <button class="btn-secondary" onclick="clearSearch()">
                            <i class="fas fa-times"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table" id="participantesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Estado Inscripción</th>
                                <th>Participó</th>
                                <th>Fecha Inscripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="participantes-body">
                            <!-- Los participantes se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div id="empty-participantes" class="empty-state" style="display: none;">
                    <i class="fas fa-users-slash"></i>
                    <h3>No hay participantes</h3>
                    <p>No se encontraron participantes para el período seleccionado.</p>
                </div>
            </div>
        </div>

        <!-- Error State -->
        <div id="error-reporte" class="error-state" style="display: none;">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error al cargar el reporte</h3>
            <p id="error-message">Ocurrió un problema al cargar la información. Intenta nuevamente.</p>
            <button class="btn-primary" onclick="cargarReporte()">
                <i class="fas fa-redo"></i>
                Reintentar
            </button>
        </div>
    </div>

    <div id="exportModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Exportar Reporte</h3>
                <button type="button" class="close-btn" onclick="closeExportModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Formato de exportación:</label>
                    <select class="form-select" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF (.pdf)</option>
                        <option value="ambos">Ambos formatos</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeExportModal()">Cancelar</button>
                    <button type="button" class="btn-primary" onclick="confirmExport()">Exportar</button>
                </div>
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .header-content h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #004F6E;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-content p {
            color: #718096;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Información de la Disciplina */
        .info-section {
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-left: 4px solid #004F6E;
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .info-header h2 {
            color: #2D3748;
            margin: 0;
            font-size: 1.5rem;
        }

        .estado-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-badge.activa {
            background: rgba(56, 161, 105, 0.1);
            color: #38A169;
            border: 1px solid #38A169;
        }

        .estado-badge.inactiva {
            background: rgba(113, 128, 150, 0.1);
            color: #718096;
            border: 1px solid #718096;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #F7FAFC;
            border-radius: 8px;
        }

        .info-item strong {
            color: #2D3748;
            font-weight: 600;
        }

        .info-item span {
            color: #4A5568;
        }

        /* Filtros */
        .filters-section {
            margin-bottom: 2rem;
        }

        .filters-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .filters-card h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: #2D3748;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-label {
            font-weight: 600;
            color: #2D3748;
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 0.75rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            background: white;
            color: #2D3748;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filter-select:focus {
            outline: none;
            border-color: #004F6E;
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
        }

        /* Mensaje de advertencia */
        .warning-message {
            background: #FEF5F5;
            border: 1px solid #FED7D7;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .warning-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .warning-content i {
            color: #D69E2E;
            font-size: 1.5rem;
            margin-top: 0.25rem;
        }

        .warning-content h3 {
            color: #2D3748;
            margin-bottom: 0.5rem;
        }

        .warning-content p {
            color: #4A5568;
            margin: 0;
        }

        /* Estadísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            text-align: center;
            border-top: 4px solid;
        }

        .stat-card.primary {
            border-top-color: #004F6E;
        }

        .stat-card.success {
            border-top-color: #38A169;
        }

        .stat-card.warning {
            border-top-color: #D69E2E;
        }

        .stat-card.info {
            border-top-color: #3182CE;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2D3748;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #718096;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Tabla */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2D3748;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .section-filters {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23718096' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.3-4.3'%3E%3C/path%3E%3C/svg%3E") no-repeat 12px center;
            min-width: 300px;
        }

        .table-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .data-table thead {
            background: #F7FAFC;
            border-bottom: 2px solid #E2E8F0;
        }

        .data-table th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-weight: 600;
            color: #2D3748;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #E2E8F0;
            color: #4A5568;
        }

        .data-table tbody tr:hover td {
            background: #F7FAFC;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges de estado */
        .estado-inscripcion {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-inscripcion.aceptada {
            background: rgba(56, 161, 105, 0.1);
            color: #38A169;
        }

        .estado-inscripcion.pendiente {
            background: rgba(214, 158, 46, 0.1);
            color: #D69E2E;
        }

        .estado-inscripcion.rechazada {
            background: rgba(229, 62, 62, 0.1);
            color: #E53E3E;
        }

        /* Botones de acción */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #718096;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .btn-icon:hover {
            background: #F7FAFC;
            color: #004F6E;
            transform: scale(1.1);
        }

        /* Estados de la UI */
        .loading-state,
        .empty-state,
        .error-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #004F6E;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .empty-state i,
        .error-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state i {
            color: #CBD5E0;
        }

        .error-state i {
            color: #E53E3E;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: #00AA8B;
            color: white;
        }

        .btn-secondary {
            background: #F7FAFC;
            color: #2D3748;
            border: 1px solid #E2E8F0;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 170, 139, 0.3);
            background: #00957A;
        }

        .btn-secondary:hover {
            background: #EDF2F7;
            border-color: #CBD5E0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .section-filters {
                width: 100%;
                flex-direction: column;
            }

            .search-input {
                min-width: auto;
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }

            .data-table {
                min-width: 800px;
            }
        }
    </style>

    <script>
        let disciplinaData = null;
        let participantesActuales = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el ID de la disciplina de la URL
            const idDisciplina = {{ $idDisciplina }};
            cargarReporte(idDisciplina);

            // Configurar búsqueda
            document.getElementById('searchParticipantes').addEventListener('input', function(e) {
                filtrarParticipantes(e.target.value);
            });

            // Configurar filtro de períodos
            document.getElementById('periodoFilter').addEventListener('change', function(e) {
                cambiarPeriodo(e.target.value);
            });
        });

        function cargarReporte(idDisciplina) {
            showLoading();

            // Usar el nuevo endpoint que combina datos actuales e históricos
            fetch(`/comite/reportes/datos-detalle/${idDisciplina}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    disciplinaData = data;
                    mostrarReporte(data);
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error al cargar reporte:', error);
                    showError(error.message);
                });
        }

        function mostrarReporte(data) {
            const disciplina = data.disciplina;

            // Mostrar información básica de la disciplina
            document.getElementById('disciplina-nombre').textContent = disciplina.nombre;
            document.getElementById('disciplina-categoria').textContent = disciplina.categoria_formateada;
            document.getElementById('disciplina-genero').textContent = disciplina.genero_formateado;
            document.getElementById('disciplina-cupo').textContent = disciplina.cupo_maximo || 'No definido';
            document.getElementById('disciplina-estado-texto').textContent = disciplina.estado_formateado;

            // Mostrar mensaje si está desactivada
            if (!disciplina.activa) {
                document.getElementById('disciplina-desactivada').style.display = 'block';
            }

            // Configurar filtro de períodos
            configurarFiltroPeriodos(data);

            // Mostrar participantes según el tipo de datos
            if (data.tipo === 'activa' && data.inscritos_actuales) {
                // Disciplina activa - mostrar participantes actuales
                mostrarParticipantes(data.inscritos_actuales);
                mostrarEstadisticasActuales(disciplina, data.inscritos_actuales.length);
            } else if (data.historial && data.historial.length > 0) {
                // Tiene historial - mostrar el primer período histórico
                mostrarParticipantesHistorial(data.historial[0]);
            } else {
                // Sin datos
                mostrarParticipantes([]);
            }

            // Mostrar contenido principal
            document.getElementById('reporte-content').style.display = 'block';
        }

        function configurarFiltroPeriodos(data) {
            const select = document.getElementById('periodoFilter');
            const filtroSection = document.getElementById('filtro-periodos-section');

            // Limpiar opciones excepto "Actual"
            while (select.options.length > 1) {
                select.remove(1);
            }

            // Agregar períodos históricos si existen
            if (data.historial && data.historial.length > 0) {
                data.historial.forEach((historial, index) => {
                    const inicio = new Date(historial.periodo.inicio).toLocaleDateString();
                    const fin = new Date(historial.periodo.fin).toLocaleDateString();

                    const option = document.createElement('option');
                    option.value = historial.id_historial;
                    option.textContent = `${inicio} - ${fin}`;
                    select.appendChild(option);
                });

                // Mostrar sección de filtros
                filtroSection.style.display = 'block';
            }
        }

        function cambiarPeriodo(periodoId) {
            if (periodoId === 'actual') {
                // Mostrar participantes actuales
                mostrarParticipantesActuales(disciplinaData.disciplina);
            } else {
                // Buscar el período histórico seleccionado
                const historial = disciplinaData.historial.find(h => h.id_historial == periodoId);
                if (historial) {
                    mostrarParticipantesHistorial(historial);
                }
            }
        }

        function mostrarParticipantesActuales(disciplina) {
            // En una implementación real, aquí harías una llamada al endpoint de inscripciones actuales
            // Por ahora, simulamos que no hay participantes actuales si la disciplina está inactiva
            if (!disciplina.activa) {
                mostrarParticipantes([]);
                return;
            }

            // Para disciplinas activas, cargaríamos los participantes actuales
            // Esto requeriría un endpoint adicional o modificar el existente
            fetch(`/comite/disciplinas/${disciplina.id_disciplina}/inscritos/data`)
                .then(response => response.json())
                .then(data => {
                    participantesActuales = data.inscritos || [];
                    mostrarParticipantes(participantesActuales);
                    mostrarEstadisticasActuales(disciplina, participantesActuales.length);
                })
                .catch(error => {
                    console.error('Error al cargar participantes actuales:', error);
                    mostrarParticipantes([]);
                });
        }

        function mostrarParticipantesHistorial(historial) {
            const participantes = historial.participantes || [];
            mostrarParticipantes(participantes);
            mostrarEstadisticasHistorial(historial);
        }

        function mostrarParticipantes(participantes) {
            const tbody = document.getElementById('participantes-body');
            const emptyState = document.getElementById('empty-participantes');

            tbody.innerHTML = '';

            if (participantes.length === 0) {
                tbody.style.display = 'none';
                emptyState.style.display = 'block';
                return;
            }

            tbody.style.display = '';
            emptyState.style.display = 'none';

            participantes.forEach((participante, index) => {
                const row = document.createElement('tr');

                const fechaInscripcion = participante.fecha_inscripcion_original ?
                    new Date(participante.fecha_inscripcion_original).toLocaleDateString() :
                    'No disponible';

                const participoBadge = participante.participo ?
                    '<span class="estado-inscripcion aceptada">Sí</span>' :
                    '<span class="estado-inscripcion rechazada">No</span>';

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${participante.nombre_usuario}</td>
                    <td>${participante.email_usuario}</td>
                    <td><span class="estado-inscripcion ${participante.estado_inscripcion}">${participante.estado_inscripcion_formateado}</span></td>
                    <td>${participoBadge}</td>
                    <td>${fechaInscripcion}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="/Comite/Cuenta-Aspirante/${participante.id_usuario}" class="btn-icon" title="Ver perfil">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn-icon" title="Contactar" onclick="contactarUsuario('${participante.email_usuario}')">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(row);
            });
        }

        function mostrarEstadisticasActuales(disciplina, totalParticipantes) {
            const estadisticasSection = document.getElementById('estadisticas-section');

            estadisticasSection.innerHTML = `
                <div class="stat-card primary">
                    <div class="stat-number">${totalParticipantes}</div>
                    <div class="stat-label">Total Inscritos</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">${disciplina.cupo_maximo || 'N/A'}</div>
                    <div class="stat-label">Cupo Máximo</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">${disciplina.cupos_disponibles}</div>
                    <div class="stat-label">Cupos Disponibles</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">${disciplina.inscripciones_pendientes_count}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
            `;

            estadisticasSection.style.display = 'grid';
        }

        function mostrarEstadisticasHistorial(historial) {
            const estadisticasSection = document.getElementById('estadisticas-section');
            const inicio = new Date(historial.periodo.inicio).toLocaleDateString();
            const fin = new Date(historial.periodo.fin).toLocaleDateString();

            estadisticasSection.innerHTML = `
                <div class="stat-card primary">
                    <div class="stat-number">${historial.total_inscritos}</div>
                    <div class="stat-label">Total Inscritos</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">${historial.tasa_participacion}%</div>
                    <div class="stat-label">Tasa de Participación</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">${inicio}</div>
                    <div class="stat-label">Fecha Inicio</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">${fin}</div>
                    <div class="stat-label">Fecha Fin</div>
                </div>
            `;

            estadisticasSection.style.display = 'grid';
        }

        function filtrarParticipantes(termino) {
            const filas = document.querySelectorAll('#participantesTable tbody tr');
            const terminoLower = termino.toLowerCase();

            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                if (texto.includes(terminoLower)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        function clearSearch() {
            document.getElementById('searchParticipantes').value = '';
            const filas = document.querySelectorAll('#participantesTable tbody tr');
            filas.forEach(fila => fila.style.display = '');
        }

        function contactarUsuario(email) {
            window.location.href = `mailto:${email}`;
        }


        document.querySelector('.btn-primary').addEventListener('click', mostrarModalExportacion);


        function mostrarModalExportacion() {
            // Crear modal de exportación
            const modal = document.createElement('div');
            modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    `;

            modal.innerHTML = `
        <div style="background: white; padding: 2rem; border-radius: 12px; width: 400px; max-width: 90vw;">
            <h3 style="margin-bottom: 1rem; color: #004F6E;">Exportar Reporte</h3>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Formato de exportación</label>
                <select id="formatoExportacion" style="width: 100%; padding: 0.75rem; border: 2px solid #E2E8F0; border-radius: 8px;">
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="pdf">PDF (.pdf)</option>
                    <option value="ambos">Ambos formatos</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button onclick="cerrarModal()" style="padding: 0.75rem 1.5rem; border: 1px solid #E2E8F0; background: white; border-radius: 8px; cursor: pointer;">
                    Cancelar
                </button>
                <button onclick="confirmarExportacion()" style="padding: 0.75rem 1.5rem; background: #00AA8B; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Exportar
                </button>
            </div>
        </div>
    `;

            document.body.appendChild(modal);

            window.cerrarModal = function() {
                document.body.removeChild(modal);
            };

            window.confirmarExportacion = function() {
                const formato = document.getElementById('formatoExportacion').value;
                exportarReporte(formato);
            };
        }

        function exportarReporte(formato) {
            const periodoId = document.getElementById('periodoFilter').value;
            const idDisciplina = {{ $idDisciplina }};

            // Mostrar loading
            const btnExportar = document.querySelector('.btn-primary');
            const originalText = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exportando...';
            btnExportar.disabled = true;

            fetch(`/comite/reportes/exportar/${idDisciplina}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        formato: formato,
                        periodo_id: periodoId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Error del servidor');
                        });
                    }

                    // Verificar el tipo de contenido para saber si es JSON o un archivo
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // Es un archivo para descargar
                        return response.blob().then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;

                            // Obtener el nombre del archivo del header Content-Disposition
                            const contentDisposition = response.headers.get('content-disposition');
                            let filename = 'reporte';

                            if (contentDisposition) {
                                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                                if (filenameMatch) {
                                    filename = filenameMatch[1];
                                }
                            }

                            // Si no se encontró nombre, crear uno basado en el formato
                            if (filename === 'reporte') {
                                const extension = formato === 'pdf' ? '.pdf' : '.xlsx';
                                filename = `Reporte_Disciplina_${idDisciplina}${extension}`;
                            }

                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);

                            return {
                                success: true
                            };
                        });
                    }
                })
                .then(data => {
                    if (data && data.success === false) {
                        throw new Error(data.message);
                    }

                    if (data && data.success !== undefined) {
                        mostrarMensajeExito('Reporte exportado exitosamente');
                    }
                    cerrarModal();
                })
                .catch(error => {
                    console.error('Error al exportar:', error);
                    mostrarMensajeError(error.message || 'Error al exportar el reporte');
                })
                .finally(() => {
                    btnExportar.innerHTML = originalText;
                    btnExportar.disabled = false;
                });
        }

        function mostrarMensajeExito(mensaje) {
            // Implementar toast de éxito
            alert('Éxito: ' + mensaje);
        }

        function mostrarMensajeError(mensaje) {
            // Implementar toast de error
            alert('Error: ' + mensaje);
        }

        // Estados de la UI
        function showLoading() {
            document.getElementById('loading-reporte').style.display = 'block';
            document.getElementById('reporte-content').style.display = 'none';
            document.getElementById('error-reporte').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loading-reporte').style.display = 'none';
        }

        function showError(mensaje) {
            document.getElementById('loading-reporte').style.display = 'none';
            document.getElementById('reporte-content').style.display = 'none';
            document.getElementById('error-reporte').style.display = 'block';

            if (mensaje) {
                document.getElementById('error-message').textContent = mensaje;
            }
        }
    </script>
@endsection
