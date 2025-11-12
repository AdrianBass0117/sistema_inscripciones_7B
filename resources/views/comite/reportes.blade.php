@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-chart-bar"></i> Reportes del Comité</h1>
                <p>Genera reportes detallados para la gestión operativa diaria</p>
            </div>
        </div>

        <!-- Filtros Principales -->
        <div class="filters-section">
            <div class="filters-card">
                <h3><i class="fas fa-filter"></i> Filtros de Reporte</h3>
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Género</label>
                        <select class="filter-select" id="generoFilter">
                            <option value="all">Todos los géneros</option>
                            <option value="Varonil">Varonil</option>
                            <option value="Femenil">Femenil</option>
                            <option value="Mixto">Mixto</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Categoría</label>
                        <select class="filter-select" id="categoriaFilter">
                            <option value="all">Todas las categorías</option>
                            <option value="Deporte">Deportiva</option>
                            <option value="Cultural">Cultural</option>
                        </select>
                    </div>
                </div>
                <div class="filter-actions">
                    <button class="btn-secondary" id="limpiarFiltros">
                        <i class="fas fa-undo"></i>
                        Limpiar Filtros
                    </button>
                    <button class="btn-primary" id="aplicarFiltros">
                        <i class="fas fa-search"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtros Aplicados -->
        <div id="applied-filters" class="applied-filters" style="display: none;">
            <div class="filters-summary">
                <h4>Filtros aplicados:</h4>
                <div class="filter-tags" id="filter-tags">
                    <!-- Los tags de filtros se mostrarán aquí -->
                </div>
                <div class="results-count" id="results-count">
                    <!-- El contador de resultados se actualizará aquí -->
                </div>
            </div>
        </div>

        <!-- Reportes por Disciplina -->
        <div class="report-section">
            <div class="section-header">
                <h2><i class="fas fa-trophy"></i> Reportes por Disciplina</h2>
                <p>Listas detalladas de inscritos organizadas por disciplina</p>
            </div>

            <!-- Loading State -->
            <div id="loading-disciplinas" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Cargando disciplinas...</p>
            </div>

            <div class="reports-grid" id="disciplinas-container" style="display: none;">
                <!-- Las disciplinas se cargarán aquí dinámicamente -->
            </div>

            <!-- Empty State -->
            <div id="empty-disciplinas" class="empty-state" style="display: none;">
                <i class="fas fa-inbox"></i>
                <h3>No hay disciplinas disponibles</h3>
                <p>No se encontraron disciplinas para mostrar.</p>
            </div>

            <!-- Error State -->
            <div id="error-disciplinas" class="error-state" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error al cargar disciplinas</h3>
                <p>Ocurrió un problema al cargar las disciplinas. Intenta nuevamente.</p>
                <button class="btn-primary" onclick="cargarDisciplinas()">
                    <i class="fas fa-redo"></i>
                    Reintentar
                </button>
            </div>
        </div>

        <!-- Reportes por Estado -->
        <div class="report-section">
            <div class="section-header">
                <h2><i class="fas fa-clipboard-check"></i> Reportes por Estado</h2>
                <p>Agrupación de participantes según su estado de validación</p>
            </div>

            <!-- Loading State -->
            <div id="loading-estados" class="loading-state">
                <div class="loading-spinner"></div>
                <p>Cargando estadísticas de usuarios...</p>
            </div>

            <div class="status-reports" id="status-reports-container" style="display: none;">
                <!-- Los estados se cargarán aquí dinámicamente -->
            </div>

            <!-- Error State -->
            <div id="error-estados" class="error-state" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Error al cargar estadísticas</h3>
                <p>Ocurrió un problema al cargar las estadísticas de usuarios. Intenta nuevamente.</p>
                <button class="btn-primary" onclick="cargarEstadisticasUsuarios()">
                    <i class="fas fa-redo"></i>
                    Reintentar
                </button>
            </div>
        </div>

        <!-- Exportación Masiva -->
        {{-- <div class="export-section">
            <div class="export-card">
                <div class="export-header">
                    <i class="fas fa-file-export"></i>
                    <h3>Exportación Masiva de Datos</h3>
                </div>
                <p>Descarga conjuntos completos de datos para análisis externos</p>
                <div class="export-options">
                    <div class="export-option">
                        <input type="checkbox" id="exportAll" checked>
                        <label for="exportAll">Todos los participantes</label>
                    </div>
                    <div class="export-option">
                        <input type="checkbox" id="exportDocuments" checked>
                        <label for="exportDocuments">Información de documentos</label>
                    </div>
                    <div class="export-option">
                        <input type="checkbox" id="exportHistory">
                        <label for="exportHistory">Historial de validaciones</label>
                    </div>
                </div>
                <div class="export-actions">
                    <select class="format-select" id="exportFormat">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                    </select>
                    <button class="btn-success">
                        <i class="fas fa-download"></i>
                        Exportar Datos Completos
                    </button>
                </div>
            </div>
        </div> --}}
    </div>

    <style>
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

        /* Secciones */
        .report-section {
            margin-bottom: 3rem;
        }

        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            color: #004F6E;
            margin-bottom: 0.5rem;
        }

        .section-header p {
            color: #718096;
            margin: 0;
        }

        /* Filtros */
        .filters-section {
            margin-bottom: 2rem;
        }

        .filters-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .filters-card h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            color: #2D3748;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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

        .filter-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        /* Filtros Aplicados */
        .applied-filters {
            background: #F7FAFC;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #00AA8B;
        }

        .filters-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .filters-summary h4 {
            margin: 0;
            color: #2D3748;
            font-size: 1rem;
        }

        .filter-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tag {
            background: #004F6E;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .results-count {
            margin-left: auto;
            color: #718096;
            font-weight: 600;
        }

        /* Reportes por Disciplina */
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .report-card.inactive {
            opacity: 0.7;
            background: #F7FAFC;
        }

        .report-card.inactive:hover {
            transform: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .report-header {
            padding: 1.5rem;
            color: white;
        }

        .report-header.active {
            background: #004F6E;
        }

        .report-header.inactive {
            background: #718096;
        }

        .report-header h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }

        .total-inscritos {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .report-body {
            padding: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-item {
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
        }

        .stat-item.success {
            background: rgba(56, 161, 105, 0.1);
        }

        .stat-item.warning {
            background: rgba(214, 158, 46, 0.1);
        }

        .stat-item.danger {
            background: rgba(229, 62, 62, 0.1);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-item.success .stat-value {
            color: #38A169;
        }

        .stat-item.warning .stat-value {
            color: #D69E2E;
        }

        .stat-item.danger .stat-value {
            color: #E53E3E;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #718096;
            font-weight: 500;
        }

        .report-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Reportes por Estado */
        .status-reports {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .status-report-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-left: 4px solid;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .status-report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .status-report-card.pendiente {
            border-left-color: #D69E2E;
        }

        .status-report-card.aceptado {
            border-left-color: #38A169;
        }

        .status-report-card.rechazado {
            border-left-color: #E53E3E;
        }

        .status-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
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
            flex-shrink: 0;
        }

        .status-report-card.pendiente .status-icon {
            background: #D69E2E;
        }

        .status-report-card.aceptado .status-icon {
            background: #38A169;
        }

        .status-report-card.rechazado .status-icon {
            background: #E53E3E;
        }

        .status-info h3 {
            margin: 0 0 0.5rem 0;
            color: #2D3748;
        }

        .status-count {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2D3748;
        }

        .status-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #E2E8F0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-item span {
            color: #718096;
            font-size: 0.9rem;
        }

        .detail-item strong {
            color: #2D3748;
        }

        .status-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Exportación */
        .export-section {
            margin-bottom: 2rem;
        }

        .export-card {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 2px solid #38A169;
        }

        .export-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .export-header i {
            font-size: 2.5rem;
            color: #38A169;
        }

        .export-header h3 {
            margin: 0;
            color: #2D3748;
        }

        .export-card p {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .export-options {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .export-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .export-option input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #004F6E;
        }

        .export-option label {
            color: #2D3748;
            font-weight: 500;
        }

        .export-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .format-select {
            padding: 0.75rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            background: white;
            color: #2D3748;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .format-select:focus {
            outline: none;
            border-color: #004F6E;
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
        }

        /* Botones */
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger,
        .btn-warning,
        .btn-outline {
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

        .btn-primary:hover {
            background: #00957A;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background: #F7FAFC;
            color: #2D3748;
            border: 2px solid #E2E8F0;
        }

        .btn-secondary:hover {
            background: #EDF2F7;
            border-color: #CBD5E1;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #38A169;
            color: white;
        }

        .btn-success:hover {
            background: #2F855A;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-danger {
            background: #E53E3E;
            color: white;
        }

        .btn-danger:hover {
            background: #C53030;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-warning {
            background: #D69E2E;
            color: white;
        }

        .btn-warning:hover {
            background: #B7791F;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-outline {
            background: transparent;
            color: #004F6E;
            border: 2px solid #004F6E;
        }

        .btn-outline:hover {
            background: #004F6E;
            color: white;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .btn-outline:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-outline:disabled:hover {
            background: transparent;
            color: #004F6E;
            transform: none;
            box-shadow: none;
        }

        /* Responsive */
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
                flex-direction: column;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                flex-direction: column;
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }

            .status-reports {
                grid-template-columns: 1fr;
            }

            .filters-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .results-count {
                margin-left: 0;
            }

            .export-actions {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }

            .report-actions {
                flex-direction: column;
            }

            .status-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .filters-card {
                padding: 1.5rem;
            }

            .report-card {
                margin: 0.5rem;
            }

            .status-report-card {
                padding: 1.5rem;
            }

            .export-card {
                padding: 1.5rem;
            }

            .status-header {
                flex-direction: column;
                text-align: center;
            }

            .export-header {
                flex-direction: column;
                text-align: center;
            }
        }

        .info-adicional {
            margin-bottom: 1rem;
            padding: 1rem;
            background: #F7FAFC;
            border-radius: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            font-size: 0.85rem;
            color: #4A5568;
        }

        .info-item strong {
            color: #2D3748;
        }

        .report-card {
            min-height: 320px;
            display: flex;
            flex-direction: column;
        }

        .report-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .report-actions {
            margin-top: auto;
        }

        .estado-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-badge.activo {
            background: rgba(56, 161, 105, 0.1);
            color: #38A169;
        }

        .estado-badge.inactivo {
            background: rgba(113, 128, 150, 0.1);
            color: #718096;
        }

        .report-header.inactive .total-inscritos small {
            font-size: 0.75rem;
            opacity: 0.8;
            display: block;
            margin-top: 0.25rem;
        }

        .loading-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .loading-state p {
            color: #718096;
            margin: 0;
            font-size: 1rem;
        }

        .empty-state,
        .error-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .empty-state i,
        .error-state i {
            font-size: 3rem;
            color: #CBD5E0;
            margin-bottom: 1rem;
        }

        .error-state i {
            color: #E53E3E;
        }

        .empty-state h3,
        .error-state h3 {
            color: #2D3748;
            margin-bottom: 0.5rem;
        }

        .empty-state p,
        .error-state p {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        /* Información simplificada en las cards */
        .info-adicional {
            margin-bottom: 1rem;
            padding: 1rem;
            background: #F7FAFC;
            border-radius: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            font-size: 0.85rem;
            color: #4A5568;
        }

        .info-item strong {
            color: #2D3748;
            font-weight: 600;
        }

        /* Agregar al final de tu CSS existente */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        }

        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            width: 400px;
            max-width: 90vw;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .modal-header h3 {
            margin: 0;
            color: #004F6E;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #718096;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close-btn:hover {
            background: #F7FAFC;
            color: #2D3748;
        }
    </style>

    <script>
        let todasLasDisciplinas = [];
        let filtrosActuales = {
            genero: 'all',
            categoria: 'all'
        };

        document.addEventListener('DOMContentLoaded', function() {
            inicializarFiltros();
            cargarDisciplinas();
            cargarEstadisticasUsuarios();
        });

        function inicializarFiltros() {
            // Configurar event listeners para los filtros
            document.getElementById('aplicarFiltros').addEventListener('click', aplicarFiltros);
            document.getElementById('limpiarFiltros').addEventListener('click', limpiarFiltros);

            // Aplicar filtros al cambiar cualquier select
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', aplicarFiltros);
            });
        }

        function aplicarFiltros() {
            // Obtener valores actuales de los filtros
            filtrosActuales = {
                genero: document.getElementById('generoFilter').value,
                categoria: document.getElementById('categoriaFilter').value
            };

            // Aplicar filtros a las disciplinas
            filtrarDisciplinas();

            // Actualizar la sección de filtros aplicados
            actualizarFiltrosAplicados();
        }

        function limpiarFiltros() {
            // Restablecer todos los filtros a "all"
            document.getElementById('generoFilter').value = 'all';
            document.getElementById('categoriaFilter').value = 'all';

            // Aplicar filtros limpios
            aplicarFiltros();
        }

        function filtrarDisciplinas() {
            if (todasLasDisciplinas.length === 0) return;

            let disciplinasFiltradas = [...todasLasDisciplinas];

            // Aplicar filtro por género
            if (filtrosActuales.genero !== 'all') {
                disciplinasFiltradas = disciplinasFiltradas.filter(disciplina =>
                    disciplina.genero === filtrosActuales.genero
                );
            }

            // Aplicar filtro por categoría
            if (filtrosActuales.categoria !== 'all') {
                disciplinasFiltradas = disciplinasFiltradas.filter(disciplina =>
                    disciplina.categoria === filtrosActuales.categoria
                );
            }

            // Mostrar disciplinas filtradas
            mostrarDisciplinasFiltradas(disciplinasFiltradas);
        }

        function mostrarDisciplinasFiltradas(disciplinasFiltradas) {
            const container = document.getElementById('disciplinas-container');

            if (disciplinasFiltradas.length === 0) {
                showEmptyState();
                return;
            }

            container.innerHTML = '';

            disciplinasFiltradas.forEach(disciplina => {
                container.appendChild(crearTarjetaDisciplina(disciplina));
            });

            // Actualizar contador
            actualizarContadorDisciplinas(disciplinasFiltradas.length);

            // Mostrar container
            container.style.display = 'grid';
            document.getElementById('empty-disciplinas').style.display = 'none';
            document.getElementById('error-disciplinas').style.display = 'none';
        }

        function actualizarFiltrosAplicados() {
            const appliedFiltersSection = document.getElementById('applied-filters');
            const filterTagsContainer = document.getElementById('filter-tags');
            const resultsCount = document.getElementById('results-count');

            // Limpiar tags anteriores
            filterTagsContainer.innerHTML = '';

            let filtrosActivos = false;
            let tags = [];

            // Crear tags solo para los filtros que tenemos
            if (filtrosActuales.genero !== 'all') {
                tags.push(`<span class="filter-tag">Género: ${filtrosActuales.genero}</span>`);
                filtrosActivos = true;
            }

            if (filtrosActuales.categoria !== 'all') {
                tags.push(`<span class="filter-tag">Categoría: ${filtrosActuales.categoria}</span>`);
                filtrosActivos = true;
            }

            // Mostrar/ocultar sección de filtros aplicados
            if (filtrosActivos) {
                filterTagsContainer.innerHTML = tags.join('');
                appliedFiltersSection.style.display = 'block';
            } else {
                appliedFiltersSection.style.display = 'none';
            }

            // Actualizar contador de resultados
            const container = document.getElementById('disciplinas-container');
            const disciplinasVisibles = container.querySelectorAll('.report-card').length;
            resultsCount.textContent = `${disciplinasVisibles} disciplinas encontradas`;
        }

        function cargarEstadisticasUsuarios() {
            // Mostrar loading
            showLoadingEstados();

            fetch('/comite/reportes/usuarios')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Verificar si hay error en la respuesta
                    if (data.error) {
                        throw new Error(data.message || data.error);
                    }
                    mostrarEstadisticasUsuarios(data);
                    hideLoadingEstados();
                })
                .catch(error => {
                    console.error('Error al cargar estadísticas:', error);
                    showErrorEstados(error.message);
                });
        }

        function showErrorEstados(mensaje) {
            const errorDiv = document.getElementById('error-estados');
            const mensajeElement = errorDiv.querySelector('p');

            if (mensaje) {
                mensajeElement.textContent = `Ocurrió un problema al cargar las estadísticas de usuarios: ${mensaje}`;
            }

            document.getElementById('loading-estados').style.display = 'none';
            document.getElementById('status-reports-container').style.display = 'none';
            errorDiv.style.display = 'block';
        }

        function mostrarEstadisticasUsuarios(data) {
            const container = document.getElementById('status-reports-container');

            if (!data.estadisticas) {
                showErrorEstados();
                return;
            }

            const estadisticas = data.estadisticas;

            container.innerHTML = `
        <!-- Estado Pendiente -->
        <div class="status-report-card pendiente">
            <div class="status-header">
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="status-info">
                    <h3>Pendientes de Validación</h3>
                    <div class="status-count">${estadisticas.pendientes} usuarios</div>
                </div>
            </div>
            <div class="status-details">
                <div class="detail-item">
                    <span>Usuarios por validar:</span>
                    <strong>${estadisticas.pendientes}</strong>
                </div>
                <div class="detail-item">
                    <span>Total de usuarios:</span>
                    <strong>${estadisticas.total_usuarios}</strong>
                </div>
                <div class="detail-item">
                    <span>Porcentaje del total:</span>
                    <strong>${calcularPorcentaje(estadisticas.pendientes, estadisticas.total_usuarios)}%</strong>
                </div>
            </div>
            <div class="status-actions">
                <button class="btn-warning" onclick="exportarUsuariosPorEstado('Pendiente')">
                    <i class="fas fa-file-export"></i>
                    Exportar lista
                </button>
            </div>
        </div>

        <!-- Estado Validado -->
        <div class="status-report-card aceptado">
            <div class="status-header">
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="status-info">
                    <h3>Usuarios Validados</h3>
                    <div class="status-count">${estadisticas.validados} usuarios</div>
                </div>
            </div>
            <div class="status-details">
                <div class="detail-item">
                    <span>Usuarios activos:</span>
                    <strong>${estadisticas.validados}</strong>
                </div>
                <div class="detail-item">
                    <span>Porcentaje del total:</span>
                    <strong>${calcularPorcentaje(estadisticas.validados, estadisticas.total_usuarios)}%</strong>
                </div>
                <div class="detail-item">
                    <span>Pueden inscribirse:</span>
                    <strong>Sí</strong>
                </div>
            </div>
            <div class="status-actions">
                <button class="btn-success" onclick="exportarUsuariosPorEstado('Validado')" >
                    <i class="fas fa-file-export"></i>
                    Exportar lista
                </button>
            </div>
        </div>

        <!-- Estado Rechazado -->
        <div class="status-report-card rechazado">
            <div class="status-header">
                <div class="status-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="status-info">
                    <h3>Usuarios Rechazados</h3>
                    <div class="status-count">${estadisticas.rechazados} usuarios</div>
                </div>
            </div>
            <div class="status-details">
                <div class="detail-item">
                    <span>Usuarios rechazados:</span>
                    <strong>${estadisticas.rechazados}</strong>
                </div>
                <div class="detail-item">
                    <span>Porcentaje del total:</span>
                    <strong>${calcularPorcentaje(estadisticas.rechazados, estadisticas.total_usuarios)}%</strong>
                </div>
                <div class="detail-item">
                    <span>Requieren corrección:</span>
                    <strong>Sí</strong>
                </div>
            </div>
            <div class="status-actions">
                <button class="btn-danger" onclick="exportarUsuariosPorEstado('Rechazado')">
                    <i class="fas fa-file-export"></i>
                    Exportar lista
                </button>
            </div>
        </div>

        <!-- Estado Suspendido -->
        <div class="status-report-card suspendido">
            <div class="status-header">
                <div class="status-icon">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="status-info">
                    <h3>Usuarios Suspendidos</h3>
                    <div class="status-count">${estadisticas.suspendidos} usuarios</div>
                </div>
            </div>
            <div class="status-details">
                <div class="detail-item">
                    <span>Usuarios suspendidos:</span>
                    <strong>${estadisticas.suspendidos}</strong>
                </div>
                <div class="detail-item">
                    <span>Porcentaje del total:</span>
                    <strong>${calcularPorcentaje(estadisticas.suspendidos, estadisticas.total_usuarios)}%</strong>
                </div>
                <div class="detail-item">
                    <span>Acceso restringido:</span>
                    <strong>Sí</strong>
                </div>
            </div>
            <div class="status-actions">
                <button class="btn-secondary" onclick="exportarUsuariosPorEstado('Suspendido')">
                    <i class="fas fa-file-export"></i>
                    Exportar lista
                </button>
            </div>
        </div>
    `;

            // Mostrar container
            container.style.display = 'grid';
        }

        function calcularPorcentaje(parcial, total) {
            if (total === 0) return 0;
            return ((parcial / total) * 100).toFixed(1);
        }

        function exportarUsuariosPorEstado(estado) {
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
            <h3 style="margin-bottom: 1rem; color: #004F6E;">Exportar Reporte de Usuarios</h3>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Formato de exportación</label>
                <select id="formatoExportacionUsuarios" style="width: 100%; padding: 0.75rem; border: 2px solid #E2E8F0; border-radius: 8px;">
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="pdf">PDF (.pdf)</option>
                    <option value="ambos">Ambos formatos</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button onclick="cerrarModalUsuarios()" style="padding: 0.75rem 1.5rem; border: 1px solid #E2E8F0; background: white; border-radius: 8px; cursor: pointer;">
                    Cancelar
                </button>
                <button onclick="confirmarExportacionUsuarios('${estado}')" style="padding: 0.75rem 1.5rem; background: #00AA8B; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Exportar
                </button>
            </div>
        </div>
    `;

            document.body.appendChild(modal);

            window.cerrarModalUsuarios = function() {
                document.body.removeChild(modal);
            };

            window.confirmarExportacionUsuarios = function(estado) {
                const formato = document.getElementById('formatoExportacionUsuarios').value;
                exportarReporteUsuarios(estado, formato);
            };
        }

        function exportarReporteUsuarios(estado, formato) {
            console.log(`Exportando usuarios ${estado} en formato ${formato}`);

            // Encontrar el botón correcto basado en el estado
            let btnExportar;
            const estadoLower = estado.toLowerCase();

            if (estadoLower === 'pendiente') {
                btnExportar = document.querySelector('.status-report-card.pendiente .btn-warning');
            } else if (estadoLower === 'validado') {
                btnExportar = document.querySelector('.status-report-card.aceptado .btn-success');
            } else if (estadoLower === 'rechazado') {
                btnExportar = document.querySelector('.status-report-card.rechazado .btn-danger');
            } else if (estadoLower === 'suspendido') {
                btnExportar = document.querySelector('.status-report-card.suspendido .btn-secondary');
            }

            if (!btnExportar) {
                console.error('No se encontró el botón de exportación para el estado:', estado);
                mostrarMensajeError('Error: No se pudo encontrar el botón de exportación');
                return;
            }

            // Guardar texto original y mostrar loading
            const originalText = btnExportar.innerHTML;
            btnExportar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exportando...';
            btnExportar.disabled = true;

            fetch(`/comite/reportes/usuarios/exportar/${estadoLower}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json, application/pdf, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    },
                    body: JSON.stringify({
                        formato: formato
                    })
                })
                .then(response => {
                    console.log('Respuesta del servidor:', response.status, response.statusText);

                    if (!response.ok) {
                        // Si es error 400, probablemente no hay datos
                        if (response.status === 400) {
                            // Intentar leer el mensaje de error como JSON
                            return response.json().then(errorData => {
                                throw new Error(errorData.message ||
                                    `No hay usuarios en estado "${estado}" para exportar.`);
                            }).catch(() => {
                                // Si no se puede leer como JSON, mostrar mensaje genérico
                                throw new Error(`No hay usuarios en estado "${estado}" para exportar.`);
                            });
                        } else {
                            // Para otros errores, intentar leer el mensaje
                            return response.json().then(errorData => {
                                throw new Error(errorData.message ||
                                    `Error ${response.status}: ${response.statusText}`);
                            }).catch(() => {
                                throw new Error(`Error ${response.status}: ${response.statusText}`);
                            });
                        }
                    }

                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type:', contentType);

                    // Verificar si es JSON (error) o un archivo
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => {
                            // Si es JSON y tiene success: false, es un error
                            if (data.success === false) {
                                throw new Error(data.message || 'Error desconocido del servidor');
                            }
                            return data;
                        });
                    } else {
                        // Es un archivo para descargar
                        return response.blob().then(blob => {
                            if (blob.size === 0) {
                                throw new Error('El archivo recibido está vacío');
                            }

                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;

                            // Obtener el nombre del archivo del header Content-Disposition
                            const contentDisposition = response.headers.get('content-disposition');
                            let filename = `usuarios_${estadoLower}_${new Date().toISOString().split('T')[0]}`;

                            if (contentDisposition) {
                                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                                if (filenameMatch) {
                                    filename = filenameMatch[1];
                                } else {
                                    // Si no hay filename, asignar extensión según formato
                                    const extension = formato === 'pdf' ? '.pdf' : '.xlsx';
                                    filename += extension;
                                }
                            } else {
                                // Si no hay content-disposition, asignar extensión según formato
                                const extension = formato === 'pdf' ? '.pdf' : '.xlsx';
                                filename += extension;
                            }

                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);

                            return {
                                success: true,
                                message: 'Archivo descargado exitosamente'
                            };
                        });
                    }
                })
                .then(data => {
                    console.log('Datos procesados:', data);

                    if (data && data.success !== false) {
                        mostrarMensajeExito(`Reporte de usuarios ${estado} exportado exitosamente`);
                    } else if (data && data.message) {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al exportar:', error);

                    // Mostrar mensaje específico para "no hay datos"
                    if (error.message.includes('No hay usuarios') ||
                        error.message.includes('no hay datos') ||
                        error.message.includes('400') ||
                        error.message.toLowerCase().includes('bad request')) {

                        mostrarMensajeError(`No hay usuarios en estado "${estado}" para exportar.`);
                    } else {
                        mostrarMensajeError(error.message || 'Error al exportar el reporte. Intenta nuevamente.');
                    }
                })
                .finally(() => {
                    // Restaurar el botón
                    btnExportar.innerHTML = originalText;
                    btnExportar.disabled = false;
                });
        }

        // Funciones para mostrar mensajes toast
        function mostrarMensajeExito(mensaje) {
            mostrarToast(mensaje, 'success');
        }

        function mostrarMensajeError(mensaje) {
            mostrarToast(mensaje, 'error');
        }

        function mostrarToast(mensaje, tipo = 'info') {
            // Crear elemento toast
            const toast = document.createElement('div');
            toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10001;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(400px);
        transition: transform 0.3s ease;
        max-width: 400px;
        word-wrap: break-word;
    `;

            // Estilos según el tipo
            if (tipo === 'success') {
                toast.style.background = '#38A169';
                toast.innerHTML = `<i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> ${mensaje}`;
            } else if (tipo === 'error') {
                toast.style.background = '#E53E3E';
                toast.innerHTML = `<i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> ${mensaje}`;
            } else {
                toast.style.background = '#004F6E';
                toast.innerHTML = `<i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i> ${mensaje}`;
            }

            document.body.appendChild(toast);

            // Animación de entrada
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Auto-remover después de 5 segundos
            setTimeout(() => {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 5000);

            // Permitir cerrar haciendo clic
            toast.addEventListener('click', function() {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            });
        }

        function exportarUsuariosPorEstado(estado) {
            // Crear modal de exportación
            const modal = document.createElement('div');
            modal.id = 'modalExportacionUsuarios';
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
        z-index: 10000;
    `;

            modal.innerHTML = `
        <div style="background: white; padding: 2rem; border-radius: 12px; width: 400px; max-width: 90vw; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <h3 style="margin-bottom: 1rem; color: #004F6E; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-file-export"></i>
                Exportar Reporte de Usuarios
            </h3>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2D3748;">Formato de exportación</label>
                <select id="formatoExportacionUsuarios" style="width: 100%; padding: 0.75rem; border: 2px solid #E2E8F0; border-radius: 8px; font-size: 0.9rem;">
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="pdf">PDF (.pdf)</option>
                    <option value="ambos">Ambos formatos</option>
                </select>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button onclick="cerrarModalUsuarios()" style="padding: 0.75rem 1.5rem; border: 1px solid #E2E8F0; background: white; border-radius: 8px; cursor: pointer; font-weight: 600; color: #4A5568;">
                    Cancelar
                </button>
                <button onclick="confirmarExportacionUsuarios('${estado}')" style="padding: 0.75rem 1.5rem; background: #00AA8B; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-download"></i>
                    Exportar
                </button>
            </div>
        </div>
    `;

            document.body.appendChild(modal);

            // Prevenir que el modal se cierre al hacer clic dentro del contenido
            modal.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        function cerrarModalUsuarios() {
            const modal = document.getElementById('modalExportacionUsuarios');
            if (modal) {
                document.body.removeChild(modal);
            }
        }

        function confirmarExportacionUsuarios(estado) {
            const formatoSelect = document.getElementById('formatoExportacionUsuarios');
            if (!formatoSelect) {
                mostrarMensajeError('Error: No se pudo encontrar el selector de formato');
                return;
            }

            const formato = formatoSelect.value;
            cerrarModalUsuarios();
            exportarReporteUsuarios(estado, formato);
        }

        // Estados de la UI para estadísticas
        function showLoadingEstados() {
            document.getElementById('loading-estados').style.display = 'block';
            document.getElementById('status-reports-container').style.display = 'none';
            document.getElementById('error-estados').style.display = 'none';
        }

        function hideLoadingEstados() {
            document.getElementById('loading-estados').style.display = 'none';
        }

        function showErrorEstados() {
            document.getElementById('loading-estados').style.display = 'none';
            document.getElementById('status-reports-container').style.display = 'none';
            document.getElementById('error-estados').style.display = 'block';
        }

        function cargarDisciplinas() {
            showLoadingState();

            fetch('/comite/reportes/disciplinas')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    todasLasDisciplinas = data.disciplinas || [];

                    // Mostrar todas las disciplinas inicialmente
                    mostrarDisciplinas(data);
                    hideLoadingState();
                })
                .catch(error => {
                    console.error('Error al cargar disciplinas:', error);
                    showErrorState();
                });
        }


        function llenarSelectDisciplinas(disciplinas) {
            const select = document.getElementById('disciplinaFilter');

            // Limpiar opciones excepto la primera
            while (select.options.length > 1) {
                select.remove(1);
            }

            // Agregar todas las disciplinas al select
            disciplinas.forEach(disciplina => {
                const option = document.createElement('option');
                option.value = disciplina.id_disciplina;
                option.textContent = disciplina.nombre;
                select.appendChild(option);
            });
        }

        function mostrarDisciplinas(data) {
            todasLasDisciplinas = data.disciplinas || [];

            if (!data.disciplinas || data.disciplinas.length === 0) {
                showEmptyState();
                return;
            }

            // Mostrar todas las disciplinas inicialmente
            mostrarDisciplinasFiltradas(todasLasDisciplinas);

            // Actualizar contador
            actualizarContadorDisciplinas(todasLasDisciplinas.length);
        }

        function crearTarjetaDisciplina(disciplina) {
            const activa = disciplina.activa;
            const card = document.createElement('div');
            card.className = `report-card ${!activa ? 'inactive' : ''}`;

            // Formatear fechas
            const fechaInicio = disciplina.fecha_inicio ?
                new Date(disciplina.fecha_inicio).toLocaleDateString() :
                'No disponible';

            const fechaFin = disciplina.fecha_fin ?
                new Date(disciplina.fecha_fin).toLocaleDateString() :
                'No disponible';

            card.innerHTML = `
        <div class="report-header ${activa ? 'active' : 'inactive'}">
            <h3>${disciplina.nombre}</h3>
            <div class="total-inscritos">
                ${disciplina.inscripciones_aceptadas_count} inscritos aceptados
                ${!activa ? '<br><small>Disciplina inactiva</small>' : ''}
            </div>
        </div>
        <div class="report-body">
            <div class="stats-grid">
                <div class="stat-item success">
                    <div class="stat-value">${disciplina.inscripciones_aceptadas_count}</div>
                    <div class="stat-label">Aceptados</div>
                </div>
                <div class="stat-item warning">
                    <div class="stat-value">${disciplina.inscripciones_pendientes_count}</div>
                    <div class="stat-label">Pendientes</div>
                </div>
                <div class="stat-item ${disciplina.tiene_cupo_disponible ? 'success' : 'danger'}">
                    <div class="stat-value">${disciplina.cupos_disponibles}</div>
                    <div class="stat-label">Cupos disponibles</div>
                </div>
            </div>
            <div class="info-adicional">
                <div class="info-item">
                    <strong>Categoría:</strong> ${disciplina.categoria_formateada}
                </div>
                <div class="info-item">
                    <strong>Género:</strong> ${disciplina.genero_formateado}
                </div>
                <div class="info-item">
                    <strong>Inicia:</strong> ${fechaInicio}
                </div>
                <div class="info-item">
                    <strong>Finaliza:</strong> ${fechaFin}
                </div>
            </div>
            <div class="report-actions">
                <button class="btn-outline btn-sm" onclick="generarReporteDisciplina(${disciplina.id_disciplina})">
                    <i class="fas fa-list"></i>
                    Generar Reporte
                </button>
            </div>
        </div>
    `;

            return card;
        }

        function actualizarContadorDisciplinas(total) {
            const resultsCount = document.getElementById('results-count');
            if (resultsCount) {
                resultsCount.textContent = `${total} disciplinas encontradas`;
            }
        }

        // Estados de la UI
        function showLoadingState() {
            document.getElementById('loading-disciplinas').style.display = 'block';
            document.getElementById('disciplinas-container').style.display = 'none';
            document.getElementById('empty-disciplinas').style.display = 'none';
            document.getElementById('error-disciplinas').style.display = 'none';
        }

        function hideLoadingState() {
            document.getElementById('loading-disciplinas').style.display = 'none';
        }

        function showEmptyState() {
            document.getElementById('loading-disciplinas').style.display = 'none';
            document.getElementById('disciplinas-container').style.display = 'none';
            document.getElementById('empty-disciplinas').style.display = 'block';
            document.getElementById('error-disciplinas').style.display = 'none';
        }

        function showErrorState() {
            document.getElementById('loading-disciplinas').style.display = 'none';
            document.getElementById('disciplinas-container').style.display = 'none';
            document.getElementById('empty-disciplinas').style.display = 'none';
            document.getElementById('error-disciplinas').style.display = 'block';
        }

        function generarReporteDisciplina(idDisciplina) {
            window.location.href = `/comite/reportes/detalle/${idDisciplina}`;
        }
    </script>
@endsection
