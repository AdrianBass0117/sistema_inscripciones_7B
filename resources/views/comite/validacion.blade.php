@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-users"></i> Validación de documentos y datos</h1>
                <p>Administra las solicitudes de los aspirantes</p>
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
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar por nombre..." class="search-input" id="searchName">
                    </div>
                    <!-- Estado -->
                    <div class="filter-group">
                        <select class="filter-select" id="filterStatus">
                            <option value="">Todos los estados</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Validado">Validado</option>
                            <option value="Rechazado">Rechazado</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contador de Resultados -->
        <div class="results-header">
            <h3 id="resultsCount">Cargando aspirantes...</h3>
            <div class="view-options">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        <!-- Grid de Aspirantes -->
        <div class="aspirants-grid" id="aspirantsView">
            <!-- Los aspirantes se cargarán aquí dinámicamente -->
            <div class="no-results" id="noResults" style="display: none;">
                <i class="fas fa-users-slash"></i>
                <h3>No se encontraron aspirantes</h3>
                <p>Intenta ajustar los filtros de búsqueda</p>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="loading-spinner" style="text-align: center; padding: 2rem;">
            <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary-color);"></i>
            <p style="margin-top: 1rem;">Cargando aspirantes...</p>
        </div>

        <!-- Paginación -->
        <div class="pagination" id="paginationContainer" style="display: none;">
            <!-- La paginación se generará dinámicamente si es necesaria -->
        </div>
    </div>

    <style>
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

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
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background: white;
            color: var(--text-primary);
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: 2px solid #E2E8F0;
            width: 100%;
            box-sizing: border-box;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
        }

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

        .aspirants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
            min-height: 200px;
        }

        .aspirants-grid.list-view {
            grid-template-columns: 1fr;
        }

        .aspirants-grid.list-view .aspirant-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 1rem;
        }

        .aspirants-grid.list-view .aspirant-card .card-header {
            border-bottom: none;
            padding: 0;
            margin-right: 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
            min-width: 120px;
        }

        .aspirants-grid.list-view .aspirant-card .card-body {
            flex-direction: row;
            align-items: center;
            flex: 1;
            padding: 0;
            text-align: left;
        }

        .aspirants-grid.list-view .aspirant-photo {
            margin-right: 1rem;
            margin-bottom: 0;
            width: 60px;
            height: 60px;
            font-size: 1.2rem;
        }

        .aspirants-grid.list-view .aspirant-info {
            flex: 1;
        }

        .aspirants-grid.list-view .aspirant-info h3 {
            margin-bottom: 0.5rem;
        }

        .aspirants-grid.list-view .aspirant-meta {
            flex-direction: row;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .aspirants-grid.list-view .card-footer {
            margin-left: auto;
            min-width: 200px;
            padding: 0;
            border-top: none;
            background: transparent;
        }

        .aspirant-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            transition: all 0.3s ease;
            border-top: 4px solid transparent;
            display: flex;
            flex-direction: column;
        }

        .aspirant-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .aspirant-card.pending {
            border-top-color: var(--warning);
        }

        .aspirant-card.validated {
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

        .status-badge.validated {
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
            flex: 1;
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

        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger,
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

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-danger {
            background-color: rgb(185, 49, 49);
            color: white;
        }

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-danger:hover,
        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

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

        .status-message.rejected {
            color: var(--danger);
            background: rgba(229, 62, 62, 0.05);
        }

        .search-box {
            position: relative;
            width: 100%;
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
            box-sizing: border-box;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
            grid-column: 1 / -1;
        }

        .no-results i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--border-color);
        }

        .btn-success:disabled,
        .btn-danger:disabled,
        .btn-warning:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1rem;
            }

            .header-content h1 {
                font-size: 1.5rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }

            .aspirants-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .aspirants-grid.list-view .aspirant-card {
                flex-direction: column;
                padding: 1rem;
            }

            .aspirants-grid.list-view .aspirant-card .card-header {
                flex-direction: row;
                width: 100%;
                margin-right: 0;
                margin-bottom: 1rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid var(--border-color);
            }

            .aspirants-grid.list-view .aspirant-card .card-body {
                flex-direction: column;
                text-align: center;
                width: 100%;
                margin-bottom: 1rem;
            }

            .aspirants-grid.list-view .aspirant-photo {
                margin-right: 0;
                margin-bottom: 1rem;
                width: 80px;
                height: 80px;
            }

            .aspirants-grid.list-view .aspirant-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .aspirants-grid.list-view .card-footer {
                margin-left: 0;
                min-width: auto;
                width: 100%;
            }

            .results-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .view-options {
                align-self: flex-end;
            }

            .card-footer {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .pagination {
                flex-wrap: wrap;
            }

            .aspirant-meta {
                font-size: 0.8rem;
            }

            .aspirants-grid.list-view .aspirant-card .card-body {
                padding: 0;
            }

            .btn-primary,
            .btn-secondary,
            .btn-success,
            .btn-danger,
            .btn-warning {
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 360px) {
            .aspirants-grid.list-view .aspirant-card .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .card-actions {
                align-self: flex-end;
            }

            .filters-card .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .btn-text {
                align-self: flex-end;
            }
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
            overflow: hidden;
            position: relative;
        }

        .aspirant-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        /* Para la vista de lista */
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let aspirantsData = [];
            let currentFilteredData = [];

            // Cargar usuarios al iniciar
            loadUsers();

            // Cambio de vista (Grid/Lista)
            const viewButtons = document.querySelectorAll('.view-btn');
            const aspirantsView = document.getElementById('aspirantsView');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const viewType = this.getAttribute('data-view');

                    viewButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    if (viewType === 'list') {
                        aspirantsView.classList.add('list-view');
                    } else {
                        aspirantsView.classList.remove('list-view');
                    }
                });
            });

            // Filtros y búsqueda
            const filterSelects = document.querySelectorAll('.filter-select');
            const searchInput = document.getElementById('searchName');
            const clearFiltersBtn = document.getElementById('clearFilters');
            const resultsCount = document.getElementById('resultsCount');

            function applyFilters() {
                const status = document.getElementById('filterStatus').value;
                const searchTerm = searchInput.value.toLowerCase();

                // Si no hay datos cargados, no hacer nada
                if (aspirantsData.length === 0) return;

                currentFilteredData = aspirantsData.filter(aspirant => {
                    const statusMatch = !status || aspirant.estado_cuenta === status;
                    const nameMatch = !searchTerm || aspirant.nombre_completo.toLowerCase().includes(
                        searchTerm);

                    return statusMatch && nameMatch;
                });

                renderAspirants(currentFilteredData);
            }

            function updateResultsCount(count) {
                if (resultsCount) {
                    resultsCount.textContent =
                        `${count} aspirante${count !== 1 ? 's' : ''} encontrado${count !== 1 ? 's' : ''}`;
                }
            }

            if (filterSelects.length > 0) {
                filterSelects.forEach(select => {
                    select.addEventListener('change', applyFilters);
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    if (filterSelects.length > 0) {
                        filterSelects.forEach(select => {
                            select.value = '';
                        });
                    }
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    currentFilteredData = [...aspirantsData];
                    renderAspirants(currentFilteredData);
                });
            }

            // Cargar usuarios desde el servidor
            function loadUsers() {
                const loadingSpinner = document.getElementById('loadingSpinner');
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'block';
                }

                fetch('{{ route('comite.obtener.usuarios') }}')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (loadingSpinner) {
                            loadingSpinner.style.display = 'none';
                        }

                        if (data.success) {
                            aspirantsData = data.usuarios || [];
                            currentFilteredData = [...aspirantsData];
                            renderAspirants(currentFilteredData);
                        } else {
                            showNotification('Error al cargar los usuarios: ' + (data.message ||
                                'Error desconocido'), 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (loadingSpinner) {
                            loadingSpinner.style.display = 'none';
                        }
                        showNotification('Error al cargar los usuarios: ' + error.message, 'danger');
                    });
            }

            // Renderizar aspirantes
            function renderAspirants(aspirants) {
                const aspirantsView = document.getElementById('aspirantsView');
                const noResults = document.getElementById('noResults');

                if (!aspirantsView) return;

                updateResultsCount(aspirants.length);

                if (aspirants.length === 0) {
                    aspirantsView.innerHTML = '';
                    if (noResults) {
                        noResults.style.display = 'block';
                        aspirantsView.appendChild(noResults);
                    } else {
                        aspirantsView.innerHTML = `
                        <div class="no-results">
                            <i class="fas fa-users-slash"></i>
                            <h3>No se encontraron aspirantes</h3>
                            <p>Intenta ajustar los filtros de búsqueda</p>
                        </div>
                    `;
                    }
                    return;
                }

                if (noResults) {
                    noResults.style.display = 'none';
                }

                aspirantsView.innerHTML = aspirants.map(aspirant => {
                    // Asegurarse de que los datos existan
                    const nombre = aspirant.nombre_completo || 'Nombre no disponible';
                    const estado = aspirant.estado_cuenta || 'Desconocido';
                    const id = aspirant.id_usuario || '';
                    const tieneErroresCorregidos = aspirant.tiene_errores_corregidos || false;

                    const statusClass = getStatusClass(estado);
                    const statusText = getStatusText(estado);

                    return `
                    <div class="aspirant-card ${statusClass}" data-status="${estado}" data-name="${nombre}">
                        <div class="card-header">
                            <div class="status-badge ${statusClass}">${statusText}</div>
                            <div class="card-actions">
                               <a href="${getAspirantUrl(id)}">
                                    <button class="btn-icon" title="Ver documentos">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="aspirant-photo">
                                ${getAspirantPhoto(aspirant)}
                            </div>
                            <div class="aspirant-info">
                                <h3>${nombre}</h3>
                            </div>
                        </div>
                        <div class="card-footer">
                            ${getActionButtons(aspirant, tieneErroresCorregidos)}
                        </div>
                    </div>
                `;
                }).join('');

                // Agregar event listeners a los botones después de renderizar
                attachButtonListeners();
            }

            function getAspirantPhoto(aspirant) {
                const tieneFoto = aspirant.tiene_foto || false;
                const idDocumentoFoto = aspirant.id_documento_foto;
                const nombre = aspirant.nombre_completo || 'Usuario';

                if (tieneFoto && idDocumentoFoto) {
                    // Usar la URL directa en lugar de la helper route de Laravel
                    const fotoUrl = `/Comite/documentos/${idDocumentoFoto}/ver`;

                    return `
            <img src="${fotoUrl}"
                 alt="Foto de ${nombre}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <i class="fas fa-user" style="display: none;"></i>
        `;
                } else {
                    return `<i class="fas fa-user"></i>`;
                }
            }

            function getAspirantUrl(id) {
                return `{{ url('/Comite/Validaciones/Usuario') }}/${id}`;
            }

            function getStatusClass(status) {
                switch (status) {
                    case 'Pendiente':
                        return 'pending';
                    case 'Validado':
                        return 'validated';
                    case 'Rechazado':
                        return 'rejected';
                    default:
                        return '';
                }
            }

            function getStatusText(status) {
                switch (status) {
                    case 'Pendiente':
                        return 'Pendiente';
                    case 'Validado':
                        return 'Validado';
                    case 'Rechazado':
                        return 'Rechazado';
                    default:
                        return status;
                }
            }

            function getActionButtons(aspirant, tieneErroresCorregidos) {
                const estado = aspirant.estado_cuenta || '';
                const id = aspirant.id_usuario || '';
                const tieneInfoPersonalPendiente = aspirant.tiene_info_personal_pendiente || false;

                switch (estado) {
                    case 'Pendiente':
                        return `
            <div class="full-width">
                <div class="status-message pending">
                    <i class="fas fa-clock"></i>
                    Revisaremos esta solicitud en cuanto tengamos tiempo
                </div>
            </div>
        `;
                    case 'Validado':
                        return `
            <div class="full-width">
                <div class="status-message">
                    <i class="fas fa-check-circle"></i>
                    Aspirante validado correctamente
                </div>
            </div>
        `;
                    case 'Rechazado':
                        let buttons = '';

                        // Botón para reconsiderar documentos
                        if (tieneErroresCorregidos) {
                            buttons += `
                <button class="btn-warning full-width" data-action="reconsiderar" data-id="${id}" data-type="documentos">
                    <i class="fas fa-redo"></i>
                    Reconsiderar Documentos
                </button>
                `;
                        }

                        // Botón para reconsiderar información personal
                        if (tieneInfoPersonalPendiente) {
                            buttons += `
                <button class="btn-warning full-width" data-action="reconsiderar" data-id="${id}" data-type="informacion" style="margin-top: 0.5rem;">
                    <i class="fas fa-user-edit"></i>
                    Reconsiderar Información Personal
                </button>
                `;
                        }

                        // Si no hay nada que reconsiderar
                        if (!tieneErroresCorregidos && !tieneInfoPersonalPendiente) {
                            return `
                <div class="full-width">
                    <div class="status-message rejected">
                        <i class="fas fa-times-circle"></i>
                        Esperando corrección del aspirante
                    </div>
                </div>
                `;
                        }

                        return buttons;
                    default:
                        return '';
                }
            }

            function attachButtonListeners() {
                document.querySelectorAll('[data-action="reconsiderar"]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const userId = this.getAttribute('data-id');
                        const type = this.getAttribute('data-type'); // 'documentos' o 'informacion'
                        const aspirantCard = this.closest('.aspirant-card');

                        if (!aspirantCard) return;

                        const aspirantNameElement = aspirantCard.querySelector('h3');
                        if (!aspirantNameElement) return;

                        const aspirantName = aspirantNameElement.textContent;

                        handleReconsiderar(userId, aspirantName, type);
                    });
                });
            }

            function handleReconsiderar(userId, aspirantName, type) {
                if (!userId) {
                    showNotification('Error: ID de usuario no válido', 'danger');
                    return;
                }

                const actionText = type === 'informacion' ?
                    'reconsiderar la información personal' :
                    'reconsiderar los documentos';

                const confirmMessage = `¿Estás seguro de ${actionText} de ${aspirantName}?`;

                if (confirm(confirmMessage)) {
                    let url;

                    if (type === 'informacion') {
                        url = `/Comite/informacion-personal/nueva-validacion`;
                    } else {
                        url = `/Comite/usuarios/${userId}/reconsiderar`;
                    }

                    // Deshabilitar botones durante la acción
                    document.querySelectorAll(`[data-id="${userId}"]`).forEach(btn => {
                        btn.disabled = true;
                    });

                    const requestData = type === 'informacion' ? {
                        id_usuario: userId
                    } : {};

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(requestData)
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showNotification(data.message, 'success');
                                // Recargar los usuarios para reflejar los cambios
                                loadUsers();
                            } else {
                                showNotification(data.message, 'danger');
                                // Re-habilitar botones en caso de error
                                document.querySelectorAll(`[data-id="${userId}"]`).forEach(btn => {
                                    btn.disabled = false;
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showNotification('Error al procesar la solicitud: ' + error.message, 'danger');
                            // Re-habilitar botones en caso de error
                            document.querySelectorAll(`[data-id="${userId}"]`).forEach(btn => {
                                btn.disabled = false;
                            });
                        });
                }
            }

            function showNotification(message, type) {
                // Remover notificaciones existentes
                document.querySelectorAll('.notification-toast').forEach(notification => {
                    notification.remove();
                });

                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'danger' ? 'times' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            `;

                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'danger' ? 'var(--danger)' : 'var(--warning)'};
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
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }, 3000);
            }

            // Añadir estilos de animación si no existen
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
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
            }
        });
    </script>
@endsection
