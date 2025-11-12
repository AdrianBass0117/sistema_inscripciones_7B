@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-user-circle"></i> Perfil del Aspirante</h1>
                <p>Información personal y documentos del participante</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('comite.aspirantes') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Aspirantes
                </a>
            </div>
        </div>

        <div class="profile-content">
            <!-- Información Principal -->
            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Información Personal</h3>
                    <div
                        class="status-badge {{ $inscripcion->estado === 'Pendiente' ? 'pending' : ($inscripcion->estado === 'Aceptado' ? 'accepted' : 'rejected') }}">
                        {{ $inscripcion->getEstadoFormateado() }}
                    </div>
                </div>
                <div class="card-body">
                    <div class="profile-header">
                        <div class="profile-photo-large">
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
                        <div class="profile-info">
                            <h2>{{ $usuario->nombre_completo }}</h2>
                            <p class="profile-meta">Número de Trabajador: {{ $usuario->numero_trabajador }}</p>
                            <div class="profile-stats">
                                <div class="stat">
                                    <i class="fas fa-trophy"></i>
                                    <span>{{ $inscripcion->disciplina->nombre }}</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-venus-mars"></i>
                                    <span>{{ $inscripcion->disciplina->getGeneroFormateado() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-fingerprint"></i>
                                CURP
                            </label>
                            <div class="info-value">{{ $usuario->curp }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-birthday-cake"></i>
                                Fecha de Nacimiento
                            </label>
                            <div class="info-value">
                                {{ $usuario->fecha_nacimiento->format('d/m/Y') }}
                                ({{ $usuario->fecha_nacimiento->age }} años)
                            </div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-calendar-alt"></i>
                                Antigüedad
                            </label>
                            <div class="info-value">{{ $usuario->antiguedad }} años en la institución</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-envelope"></i>
                                Correo Electrónico
                            </label>
                            <div class="info-value">{{ $usuario->email }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-phone"></i>
                                Teléfono
                            </label>
                            <div class="info-value">{{ $usuario->telefono }}</div>
                        </div>

                        <div class="info-group">
                            <label class="info-label">
                                <i class="fas fa-calendar-check"></i>
                                Fecha de Inscripción
                            </label>
                            <div class="info-value">{{ $inscripcion->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="documents-section">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-file-alt"></i> Documentos Cargados</h3>
                        <span class="documents-count">{{ $usuario->documentos->count() }} documentos</span>
                    </div>
                    <div class="card-body">
                        <div class="documents-grid">
                            @forelse($usuario->documentos as $documento)
                                <div class="document-card">
                                    <div class="document-icon">
                                        @if ($documento->tipo_documento === 'Constancia Laboral')
                                            <i class="fas fa-file-pdf"></i>
                                        @elseif($documento->tipo_documento === 'CFDI/Recibo')
                                            <i class="fas fa-file-invoice"></i>
                                        @else
                                            <i class="fas fa-camera"></i>
                                        @endif
                                    </div>
                                    <div class="document-info">
                                        <h4>{{ $documento->getTipoDocumentoFormateado() }}</h4>
                                        <p>{{ $documento->descripcion ?? 'Documento cargado por el usuario' }}</p>
                                        <div class="document-meta">
                                            <span
                                                class="file-status {{ $documento->estado === 'Aprobado' ? 'approved' : ($documento->estado === 'Rechazado' ? 'rejected' : 'pending') }}">
                                                {{ $documento->getEstadoFormateado() }}
                                            </span>
                                            <span class="upload-date">Subido:
                                                {{ $documento->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ route('comite.documentos.ver', $documento->id_documento) }}"
                                            target="_blank" class="btn-icon" title="Vista previa">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('comite.documentos.descargar', $documento->id_documento) }}"
                                            class="btn-icon" title="Descargar">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-documents">
                                    <i class="fas fa-folder-open"></i>
                                    <p>No hay documentos cargados</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            @if ($inscripcion->estaPendiente())
                <div class="actions-section">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-clipboard-check"></i> Validación</h3>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                <button class="btn-success large" id="acceptBtn"
                                    data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                                    <i class="fas fa-check-circle"></i>
                                    Aceptar Inscripción
                                </button>
                                <button class="btn-danger large" id="rejectBtn"
                                    data-inscripcion-id="{{ $inscripcion->id_inscripcion }}">
                                    <i class="fas fa-times-circle"></i>
                                    Rechazar Inscripción
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="actions-section">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-info-circle"></i> Estado Actual</h3>
                        </div>
                        <div class="card-body">
                            <div class="current-status">
                                <p>Esta inscripción ya ha sido
                                    <strong>{{ strtolower($inscripcion->getEstadoFormateado()) }}</strong>.
                                </p>
                                @if ($inscripcion->estaValidada())
                                    <p class="validation-date">Fecha de validación:
                                        {{ $inscripcion->fecha_validacion->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-question-circle"></i> Confirmar Acción</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalMessage">¿Estás seguro de que deseas realizar esta acción?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button class="btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>

    <style>
        .profile-photo-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            position: relative;
        }

        .profile-photo-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .dashboard-content {
            max-width: 1000px;
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

        /* Layout Principal */
        .profile-content {
            display: grid;
            gap: 2rem;
        }

        /* Tarjetas */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Información del Perfil */
        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-photo-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
        }

        .profile-info h2 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .profile-meta {
            color: var(--text-secondary);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .profile-stats {
            display: flex;
            gap: 1.5rem;
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .stat i {
            color: var(--primary-color);
            width: 16px;
        }

        /* Grid de Información */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .info-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .info-label i {
            color: var(--secondary-color);
            width: 16px;
        }

        .info-value {
            padding: 0.75rem 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.95rem;
            border: 1px solid var(--border-color);
        }

        /* Documentos */
        .documents-count {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .documents-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .document-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .document-card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .document-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .document-info {
            flex: 1;
        }

        .document-info h4 {
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .document-info p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .document-meta {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .file-status {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .file-status.approved {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .file-status.rejected {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
        }

        .file-status.pending {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .document-actions {
            display: flex;
            gap: 0.5rem;
        }

        .empty-documents {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-documents i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Acciones de Validación */
        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .current-status {
            text-align: center;
            padding: 1rem;
        }

        .current-status p {
            margin-bottom: 0.5rem;
        }

        .validation-date {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Botones */
        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
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

        .btn-success.large,
        .btn-danger.large {
            padding: 1.25rem 2rem;
            font-size: 1.1rem;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
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

        /* Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
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

        /* Modal */
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
            margin: 5% auto;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
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
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body p {
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            background: var(--bg-light);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
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
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .profile-stats {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .document-card {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .document-actions {
                justify-content: center;
            }

            .modal-content {
                margin: 10% auto;
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .header-actions {
                flex-direction: column;
            }

            .profile-photo-large {
                width: 100px;
                height: 100px;
                font-size: 2rem;
            }

            .profile-info h2 {
                font-size: 1.25rem;
            }

            .modal-footer {
                flex-direction: column;
            }

            .btn-success.large,
            .btn-danger.large {
                padding: 1rem 1.5rem;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const acceptBtn = document.getElementById('acceptBtn');
            const rejectBtn = document.getElementById('rejectBtn');
            const modal = document.getElementById('confirmationModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const confirmAction = document.getElementById('confirmAction');

            let currentAction = '';
            let currentInscripcionId = '';

            if (acceptBtn) {
                acceptBtn.addEventListener('click', function() {
                    currentAction = 'accept';
                    currentInscripcionId = this.getAttribute('data-inscripcion-id');
                    modalTitle.innerHTML = '<i class="fas fa-check-circle"></i> Aceptar Inscripción';
                    modalMessage.textContent =
                        '¿Estás seguro de aceptar la inscripción de {{ $usuario->nombre_completo }}?';
                    openModal();
                });
            }

            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    currentAction = 'reject';
                    currentInscripcionId = this.getAttribute('data-inscripcion-id');
                    modalTitle.innerHTML = '<i class="fas fa-times-circle"></i> Rechazar Inscripción';
                    modalMessage.textContent =
                        '¿Estás seguro de rechazar la inscripción de {{ $usuario->nombre_completo }}?';
                    openModal();
                });
            }

            confirmAction.addEventListener('click', function() {
                if (currentAction && currentInscripcionId) {
                    procesarAccion(currentAction, currentInscripcionId);
                }
            });

            async function procesarAccion(accion, inscripcionId) {
                const url = accion === 'accept' ?
                    `/Comite/inscripciones/${inscripcionId}/aceptar` :
                    `/Comite/inscripciones/${inscripcionId}/rechazar`;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showNotification(data.message, 'success');
                        // Redirigir después de 2 segundos
                        setTimeout(() => {
                            window.location.href = "{{ route('comite.aspirantes') }}";
                        }, 2000);
                    } else {
                        showNotification(data.message, 'danger');
                        closeModal();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error al procesar la acción', 'danger');
                    closeModal();
                }
            }

            function openModal() {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // Cerrar modal al hacer click fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Cerrar con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeModal();
                }
            });

            function showNotification(message, type) {
                // Remover notificaciones existentes
                document.querySelectorAll('.notification-toast').forEach(notif => notif.remove());

                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'times'}-circle"></i>
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
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }, 3000);
            }

            // Añadir estilos de animación
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
