@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-users"></i> Inscritos en {{ $disciplina->nombre }}</h1>
                <p>Lista de participantes inscritos en esta disciplina</p>
            </div>
            <div class="header-actions">
                <!-- Botón Finalizar -->
                <div class="finalizar-section">
                    <button class="btn-danger" onclick="mostrarModalFinalizar()">
                        <i class="fas fa-flag-checkered"></i>
                        Finalizar Disciplina
                    </button>
                </div>
                <a href="{{ route('comite.disciplinas') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Disciplinas
                </a>
            </div>
        </div>

        <!-- Estadísticas de la disciplina -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Inscritos</h3>
                    <div class="stat-number">{{ $disciplina->contarInscripcionesAceptadas() }}</div>
                    <div class="stat-trend positive">
                        <i class="fas fa-user-check"></i>
                        Participantes aceptados
                    </div>
                </div>
            </div>

            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-content">
                    <h3>Cupo Máximo</h3>
                    <div class="stat-number">{{ $disciplina->cupo_maximo }}</div>
                    <div class="stat-trend {{ $disciplina->tieneCupoDisponible() ? 'positive' : 'neutral' }}">
                        <i class="fas fa-{{ $disciplina->tieneCupoDisponible() ? 'check' : 'times' }}"></i>
                        {{ $disciplina->getCuposDisponibles() }} cupos disponibles
                    </div>
                </div>
            </div>

            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Período</h3>
                    <div class="stat-number">{{ $disciplina->getDiasRestantes() }}d</div>
                    <div class="stat-trend {{ $disciplina->fechaInscripcionVigente() ? 'positive' : 'neutral' }}">
                        <i class="fas fa-{{ $disciplina->fechaInscripcionVigente() ? 'play' : 'stop' }}"></i>
                        {{ $disciplina->getTextoDiasRestantes() }}
                    </div>
                </div>
            </div>

            <div class="stat-card accent">
                <div class="stat-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    <h3>Ocupación</h3>
                    <div class="stat-number">
                        {{ $disciplina->cupo_maximo > 0 ? round(($disciplina->contarInscripcionesAceptadas() / $disciplina->cupo_maximo) * 100) : 0 }}%
                    </div>
                    <div class="stat-trend {{ $disciplina->contarInscripcionesAceptadas() > 0 ? 'positive' : 'neutral' }}">
                        <i class="fas fa-{{ $disciplina->contarInscripcionesAceptadas() > 0 ? 'arrow-up' : 'minus' }}"></i>
                        Tasa de ocupación
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Inscritos -->
        <div class="inscritos-section">
            <div class="section-header">
                <h2><i class="fas fa-list-ul"></i> Lista de Participantes</h2>
                <div class="section-filters">
                    <input type="text" class="search-input" id="searchInscritos"
                        placeholder="Buscar por nombre o número...">
                    <button class="btn-secondary" onclick="clearSearch()">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </button>
                </div>
            </div>

            <div class="table-container">
                <table class="data-table" id="inscritosTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Número de Trabajador</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha de Inscripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inscritos as $index => $inscripcion)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $inscripcion->usuario->numero_trabajador }}</td>
                                <td>{{ $inscripcion->usuario->nombre_completo }}</td>
                                <td>{{ $inscripcion->usuario->email }}</td>
                                <td>{{ $inscripcion->usuario->telefono }}</td>
                                <td>{{ $inscripcion->fecha_inscripcion->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('comite.cuentas-aspirantes', $inscripcion->usuario->id_usuario) }}"
                                            class="btn-icon" title="Ver perfil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn-icon" title="Contactar"
                                            onclick="contactUser('{{ $inscripcion->usuario->email }}')">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash"></i>
                                        <h3>No hay inscritos en esta disciplina</h3>
                                        <p>No se han registrado participantes aún.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="modalFinalizar" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirmar Finalización</h3>
                <button type="button" class="modal-close" onclick="cerrarModalFinalizar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="warning-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <h4>¿Estás seguro de que deseas finalizar esta disciplina?</h4>
                        <p>Esta acción:</p>
                        <ul>
                            <li>Archivara todos los datos en el historial</li>
                            <li>Limpiará las inscripciones actuales</li>
                            <li>Desactivará la disciplina</li>
                            <li>Enviará una notificación a todos los usuarios</li>
                        </ul>
                        <p><strong>Esta acción no se puede deshacer.</strong></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModalFinalizar()">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
                <a href="{{ route('comite.disciplinas-finalizar', $disciplina->id_disciplina) }}" class="btn-danger">
                    <i class="fas fa-flag-checkered"></i>
                    Continuar con la Finalización
                </a>
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-card.primary {
            border-left-color: #004F6E;
        }

        .stat-card.success {
            border-left-color: #00AA8B;
        }

        .stat-card.warning {
            border-left-color: #0077B6;
        }

        .stat-card.accent {
            border-left-color: #0077B6;
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
            flex-shrink: 0;
        }

        .stat-card.primary .stat-icon {
            background: #004F6E;
        }

        .stat-card.success .stat-icon {
            background: #00AA8B;
        }

        .stat-card.warning .stat-icon {
            background: #0077B6;
        }

        .stat-card.accent .stat-icon {
            background: #0077B6;
        }

        .stat-content h3 {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2D3748;
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
            color: #00AA8B;
        }

        .stat-trend.negative {
            color: #E53E3E;
        }

        .stat-trend.neutral {
            color: #718096;
        }

        /* Sección de Inscritos */
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

        /* Campo de búsqueda */
        .search-input {
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23718096' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cpath d='m21 21-4.3-4.3'%3E%3C/path%3E%3C/svg%3E") no-repeat 12px center;
            min-width: 300px;
        }

        .search-input:focus {
            outline: none;
            border-color: #004F6E;
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
            transform: translateY(-1px);
        }

        /* Tabla */
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
            border-bottom: 1px solid #E2E8F0;
        }

        .data-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #E2E8F0;
            color: #4A5568;
            transition: background-color 0.2s ease;
        }

        .data-table tbody tr:hover td {
            background: #F7FAFC;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            color: #CBD5E0;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #2D3748;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 0;
        }

        .text-center {
            text-align: center;
        }

        /* Botones de acción en tabla */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
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

            .stats-grid {
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

            .table-container {
                overflow-x: auto;
            }

            .data-table {
                min-width: 800px;
            }

            .action-buttons {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header-actions {
                flex-direction: column;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .section-filters {
                gap: 0.5rem;
            }

            .data-table th,
            .data-table td {
                padding: 0.75rem 1rem;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card,
        .table-container {
            animation: fadeIn 0.5s ease-out;
        }

        /* Estilos para la sección de finalizar */
        .finalizar-section {
            border-top: 2px solid #E2E8F0;
            text-align: center;
        }

        .btn-danger {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            text-decoration: none;
            background: #E53E3E;
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(229, 62, 62, 0.3);
            background: #C53030;
        }

        /* Estilos del modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .modal-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2D3748;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #718096;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            background: #F7FAFC;
            color: #2D3748;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .warning-message {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            background: #FEF5F5;
            border: 1px solid #FED7D7;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .warning-message i {
            color: #E53E3E;
            font-size: 1.5rem;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .warning-message h4 {
            color: #2D3748;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .warning-message ul {
            margin: 0.75rem 0;
            padding-left: 1.5rem;
            color: #4A5568;
        }

        .warning-message li {
            margin-bottom: 0.25rem;
        }

        .modal-footer {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 1.5rem;
            border-top: 1px solid #E2E8F0;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 1rem;
            }

            .modal-footer {
                flex-direction: column;
            }

            .warning-message {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        function mostrarModalFinalizar() {
            document.getElementById('modalFinalizar').style.display = 'flex';
        }

        function cerrarModalFinalizar() {
            document.getElementById('modalFinalizar').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera del contenido
        document.getElementById('modalFinalizar').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalFinalizar();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalFinalizar();
            }
        });

        function exportToExcel() {
            // Implementar exportación a Excel
            alert('Funcionalidad de exportación a Excel - Por implementar');
        }

        function contactUser(email) {
            window.location.href = `mailto:${email}`;
        }

        // Búsqueda en tiempo real
        document.getElementById('searchInscritos').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#inscritosTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function clearSearch() {
            document.getElementById('searchInscritos').value = '';
            const rows = document.querySelectorAll('#inscritosTable tbody tr');
            rows.forEach(row => row.style.display = '');
        }
    </script>
@endsection
