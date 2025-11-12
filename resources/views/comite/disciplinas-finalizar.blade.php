@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-flag-checkered"></i> Finalizar {{ $disciplina->nombre }}</h1>
                <p>Registra la participación de los inscritos y finaliza la disciplina</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('comite.disciplinas-inscritos', $disciplina->id_disciplina) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Inscritos
                </a>
            </div>
        </div>

        <!-- Información de la disciplina -->
        <div class="info-card">
            <div class="info-content">
                <h3><i class="fas fa-info-circle"></i> Información de la Disciplina</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Categoría:</span>
                        <span class="info-value">{{ $disciplina->getCategoriaFormateada() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género:</span>
                        <span class="info-value">{{ $disciplina->getGeneroFormateado() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Total Inscritos:</span>
                        <span class="info-value">{{ $disciplina->contarInscripcionesAceptadas() }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Período:</span>
                        <span class="info-value">
                            {{ $disciplina->fecha_inicio->format('d/m/Y') }} - {{ $disciplina->fecha_fin->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Finalización -->
        <form action="{{ route('comite.disciplinas-finalizar-store', $disciplina->id_disciplina) }}" method="POST" id="finalizarForm">
            @csrf

            <div class="finalizar-section">
                <div class="section-header">
                    <h2><i class="fas fa-list-check"></i> Registro de Participación</h2>
                    <div class="section-actions">
                        <button type="button" class="btn-secondary" onclick="marcarTodos(false)">
                            <i class="fas fa-times"></i>
                            Marcar Todos como No Participó
                        </button>
                        <button type="button" class="btn-primary" onclick="marcarTodos(true)">
                            <i class="fas fa-check"></i>
                            Marcar Todos como Participó
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="data-table" id="participacionTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Número de Trabajador</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Fecha de Inscripción</th>
                                <th class="text-center">¿Participó?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscritos as $index => $inscripcion)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $inscripcion->usuario->numero_trabajador }}</td>
                                    <td>{{ $inscripcion->usuario->nombre_completo }}</td>
                                    <td>{{ $inscripcion->usuario->email }}</td>
                                    <td>{{ $inscripcion->fecha_inscripcion->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <label class="toggle-switch">
                                            <input type="checkbox"
                                                   name="participaciones[{{ $inscripcion->id_inscripcion }}]"
                                                   value="1"
                                                   checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-users-slash"></i>
                                            <h3>No hay inscritos en esta disciplina</h3>
                                            <p>No se pueden finalizar disciplinas sin participantes.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($inscritos->count() > 0)
                <div class="finalizar-actions">
                    <div class="action-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Acción irreversible:</strong> Al finalizar, todos los datos serán archivados
                            en el historial y la disciplina será desactivada.
                        </div>
                    </div>
                    <button type="submit" class="btn-danger btn-large" onclick="confirmarFinalizacion(event)">
                        <i class="fas fa-flag-checkered"></i>
                        Finalizar Disciplina
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>

    <style>
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Header (mantener estilos existentes) */
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

        /* Info Card */
        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .info-content {
            padding: 1.5rem;
        }

        .info-content h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2D3748;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #F7FAFC;
        }

        .info-label {
            font-weight: 600;
            color: #4A5568;
        }

        .info-value {
            color: #2D3748;
            font-weight: 500;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #E53E3E;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #00AA8B;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        /* Sección de Finalización */
        .finalizar-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #2D3748;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }

        .section-actions {
            display: flex;
            gap: 1rem;
        }

        /* Tabla */
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        .data-table thead {
            background: #F7FAFC;
            position: sticky;
            top: 0;
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
        }

        .data-table tbody tr:hover td {
            background: #F7FAFC;
        }

        .text-center {
            text-align: center;
        }

        /* Acciones de Finalización */
        .finalizar-actions {
            padding: 1.5rem;
            border-top: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #FEF5F5;
        }

        .action-warning {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #E53E3E;
            font-size: 0.9rem;
        }

        .btn-large {
            padding: 1rem 2rem;
            font-size: 1rem;
        }

        /* Botones (mantener estilos existentes) */
        .btn-primary, .btn-secondary, .btn-danger {
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

        .btn-danger {
            background: #E53E3E;
            color: white;
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

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(229, 62, 62, 0.3);
            background: #C53030;
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
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .section-actions {
                width: 100%;
                flex-direction: column;
            }

            .finalizar-actions {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
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
        function marcarTodos(participaron) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name^="participaciones"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = participaron;
            });
        }

        function confirmarFinalizacion(event) {
            if (!confirm('¿Estás seguro de que deseas finalizar esta disciplina? Esta acción no se puede deshacer.')) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        // Búsqueda en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'search-input';
            searchInput.placeholder = 'Buscar por nombre o número...';
            searchInput.style.marginBottom = '1rem';
            searchInput.style.width = '100%';

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#participacionTable tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            const tableContainer = document.querySelector('.table-container');
            tableContainer.parentNode.insertBefore(searchInput, tableContainer);
        });
    </script>
@endsection
