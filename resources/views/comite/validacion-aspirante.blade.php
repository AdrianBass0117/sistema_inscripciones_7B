@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-user-circle"></i> Perfil del Aspirante a validar</h1>
                <p>Información personal y documentos del aspirante</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('comite.validacion') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Validación
                </a>
            </div>
        </div>

        <div class="profile-content">
            <!-- Información Principal -->
            <div class="profile-card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Información Personal</h3>
                    <div class="status-badge {{ strtolower($usuario->estado_cuenta) }}">{{ $usuario->estado_cuenta }}</div>
                </div>
                <div class="card-body">
                    <div class="profile-header">
                        <div class="profile-photo-large">
                            @php
                                // Buscar la fotografía aprobada del usuario
                                $fotografia = $usuario
                                    ->documentos()
                                    ->where('tipo_documento', 'Fotografía')
                                    ->where('estado', 'Aprobado')
                                    ->first();

                                // Si no hay aprobada, buscar cualquier fotografía (incluyendo pendientes)
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
                            <div class="profile-meta">
                                <div class="meta-item">
                                    <i class="fas fa-id-card"></i>
                                    <span><strong>No. Trabajador:</strong> {{ $usuario->numero_trabajador }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-fingerprint"></i>
                                    <span><strong>CURP:</strong> {{ $usuario->curp }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><strong>Email:</strong> {{ $usuario->email }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-phone"></i>
                                    <span><strong>Teléfono:</strong> {{ $usuario->telefono ?? 'No especificado' }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span><strong>Fecha Nacimiento:</strong>
                                        {{ \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span><strong>Antigüedad:</strong> {{ $usuario->antiguedad }} años</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span><strong>Registrado:</strong>
                                        {{ \Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <!-- Estado de Validación de Información Personal -->
                            @php
                                $validacionInfoPersonal = $usuario->validacionInformacionPersonalActual();
                                $estadoInfoPersonal = $validacionInfoPersonal
                                    ? $validacionInfoPersonal->estado
                                    : 'Pendiente';
                            @endphp

                            <div class="validacion-info-personal">
                                <div class="validacion-status {{ strtolower($estadoInfoPersonal) }}">
                                    <strong>Estado de Información Personal:</strong>
                                    <span class="status-text">{{ $estadoInfoPersonal }}</span>

                                    @if ($estadoInfoPersonal === 'Rechazado' && $validacionInfoPersonal && $validacionInfoPersonal->motivo_rechazo)
                                        <div class="motivo-rechazo">
                                            <strong>Motivo:</strong> {{ $validacionInfoPersonal->motivo_rechazo }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Botones de Acción para Información Personal -->
                                @if ($estadoInfoPersonal === 'Pendiente')
                                    <div class="info-personal-actions">
                                        <button class="btn-success"
                                            onclick="aprobarInformacionPersonal({{ $usuario->id_usuario }})">
                                            <i class="fas fa-check"></i>
                                            Aprobar Información
                                        </button>
                                        <button class="btn-danger"
                                            onclick="rechazarInformacionPersonal({{ $usuario->id_usuario }})">
                                            <i class="fas fa-times"></i>
                                            Rechazar Información
                                        </button>
                                    </div>
                                @elseif($estadoInfoPersonal === 'Aceptada')
                                    <div class="status-message success">
                                        <i class="fas fa-check-circle"></i>
                                        Información personal aprobada
                                    </div>
                                @elseif($estadoInfoPersonal === 'Rechazada')
                                    <div class="status-message error">
                                        <i class="fas fa-times-circle"></i>
                                        Información personal rechazada
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentos -->
            <div class="documents-section">
                <div class="section-header">
                    <h3><i class="fas fa-file-alt"></i> Documentos del Aspirante</h3>
                    <p>Revisa y valida cada documento individualmente</p>
                </div>

                <div class="documents-grid">
                    @foreach ($documentos as $documento)
                        <div class="document-card {{ strtolower($documento->estado) }}">
                            <div class="document-header">
                                <div class="document-icon">
                                    @if ($documento->tipo_documento == 'Constancia Laboral')
                                        <i class="fas fa-file-contract"></i>
                                    @elseif($documento->tipo_documento == 'CFDI/Recibo')
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    @else
                                        <i class="fas fa-camera"></i>
                                    @endif
                                </div>
                                <div class="document-info">
                                    <h4>{{ $documento->tipo_documento }}</h4>
                                    <span class="document-status {{ strtolower($documento->estado) }}">
                                        {{ $documento->estado }}
                                    </span>
                                    <div class="document-meta">
                                        <small>Subido: {{ $documento->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="document-actions">
                                @if ($documento->estado === 'Pendiente')
                                    <button class="btn-success" onclick="validarDocumento({{ $documento->id_documento }})">
                                        <i class="fas fa-check"></i>
                                        Aprobar
                                    </button>
                                    <button class="btn-danger" onclick="rechazarDocumento({{ $documento->id_documento }})">
                                        <i class="fas fa-times"></i>
                                        Rechazar
                                    </button>
                                @elseif($documento->estado === 'Aprobado')
                                    <div class="status-message success">
                                        <i class="fas fa-check-circle"></i>
                                        Documento aprobado
                                    </div>
                                @elseif($documento->estado === 'Rechazado')
                                    <div class="status-message error">
                                        <i class="fas fa-times-circle"></i>
                                        Documento rechazado
                                    </div>
                                @endif

                                <button class="btn-secondary" onclick="verDocumento({{ $documento->id_documento }})">
                                    <i class="fas fa-eye"></i>
                                    Ver
                                </button>
                                <a href="{{ route('comite.documentos.descargar', $documento->id_documento) }}"
                                    class="btn-secondary" target="_blank">
                                    <i class="fas fa-download"></i>
                                    Descargar
                                </a>

                            </div>

                            @if ($documento->estado === 'Rechazado' && $documento->errores->count() > 0)
                                <div class="errores-section">
                                    <h5>Errores reportados:</h5>
                                    <ul class="errores-list">
                                        @foreach ($documento->errores as $error)
                                            <li class="error-item {{ $error->corregido ? 'corregido' : 'pendiente' }}">
                                                <i
                                                    class="fas fa-{{ $error->corregido ? 'check' : 'exclamation' }}-circle"></i>
                                                <span>{{ $error->descripcion_error }}</span>
                                                @if ($error->corregido)
                                                    <span class="corregido-badge">Corregido</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Acciones Globales -->
            @if ($usuario->estado_cuenta === 'Pendiente')
                <div class="global-actions">
                    <div class="action-card">
                        <h4><i class="fas fa-tasks"></i> Acciones Globales</h4>
                        <p>Puedes aprobar o rechazar todos los documentos pendientes</p>
                        <div class="action-buttons">
                            <button class="btn-success" onclick="validarTodosDocumentos()">
                                <i class="fas fa-check-double"></i>
                                Aprobar Todos
                            </button>
                            <button class="btn-danger" onclick="rechazarTodosDocumentos()">
                                <i class="fas fa-ban"></i>
                                Rechazar Todos
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para Rechazar Documento -->
    <div id="rechazarModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Rechazar Documento</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Describe los errores encontrados en el documento:</p>
                <textarea id="motivoRechazo" placeholder="Ejemplo: La foto no es clara, falta información en el campo X, etc."
                    rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Cancelar</button>
                <button class="btn-danger" onclick="confirmarRechazo()">Rechazar Documento</button>
            </div>
        </div>
    </div>

    <!-- Modal para Rechazar Información Personal-->
    <div id="rechazarInfoPersonalModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Rechazar Información Personal</h3>
                <button class="close-modal" onclick="closeInfoPersonalModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Describe los errores encontrados en la información personal:</p>
                <textarea id="motivoRechazoInfoPersonal"
                    placeholder="Ejemplo: Datos incompletos, información incorrecta, documentos no coinciden, etc." rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeInfoPersonalModal()">Cancelar</button>
                <button class="btn-danger" onclick="confirmarRechazoInfoPersonal()">Rechazar Información</button>
            </div>
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

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .profile-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .profile-card,
        .documents-section,
        .global-actions {
            background: white;
            border-radius: 16px;
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

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.validado {
            background: #d1edff;
            color: #004f6e;
        }

        .status-badge.rechazado {
            background: #f8d7da;
            color: #721c24;
        }

        .profile-header {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .profile-photo-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            border: 4px solid white;
            box-shadow: var(--shadow-md);
        }

        .profile-info h2 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .profile-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary);
            padding: 0.5rem;
            background: var(--bg-light);
            border-radius: 8px;
        }

        .meta-item i {
            color: var(--primary-color);
            width: 20px;
        }

        .validacion-info-personal {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
        }

        .validacion-status {
            margin-bottom: 1rem;
        }

        .validacion-status.aceptada {
            color: #155724;
        }

        .validacion-status.rechazada {
            color: #721c24;
        }

        .validacion-status.pendiente {
            color: #856404;
        }

        .motivo-rechazo {
            margin-top: 0.5rem;
            padding: 0.75rem;
            background: #f8d7da;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .info-personal-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .section-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .section-header p {
            color: var(--text-secondary);
            margin: 0;
        }

        .documents-grid {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .document-card {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .document-card.pendiente {
            border-color: #ffc107;
        }

        .document-card.aprobado {
            border-color: #28a745;
        }

        .document-card.rechazado {
            border-color: #dc3545;
        }

        .document-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .document-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .document-icon {
            width: 60px;
            height: 60px;
            background: var(--bg-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .document-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
        }

        .document-status {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .document-status.pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .document-status.aprobado {
            background: #d4edda;
            color: #155724;
        }

        .document-status.rechazado {
            background: #f8d7da;
            color: #721c24;
        }

        .document-meta {
            margin-top: 0.5rem;
        }

        .document-meta small {
            color: var(--text-secondary);
        }

        .document-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .status-message {
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .status-message.success {
            background: #d4edda;
            color: #155724;
        }

        .status-message.error {
            background: #f8d7da;
            color: #721c24;
        }

        .errores-section {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .errores-section h5 {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .errores-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 6px;
            font-size: 0.85rem;
        }

        .error-item.pendiente {
            background: #f8d7da;
            color: #721c24;
        }

        .error-item.corregido {
            background: #d4edda;
            color: #155724;
        }

        .corregido-badge {
            margin-left: auto;
            padding: 0.25rem 0.5rem;
            background: #28a745;
            color: white;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .global-actions {
            padding: 1.5rem;
        }

        .action-card {
            text-align: center;
            padding: 2rem;
        }

        .action-card h4 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .action-card p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-primary,
        .btn-secondary,
        .btn-success,
        .btn-danger {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
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
            background: var(--danger);
            color: white;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .btn-danger:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary:hover {
            background: var(--border-color);
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: var(--shadow-lg);
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
            margin: 0;
            color: var(--text-primary);
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-body textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .profile-meta {
                grid-template-columns: 1fr;
            }

            .documents-grid {
                grid-template-columns: 1fr;
            }

            .document-actions {
                flex-direction: column;
            }

            .action-buttons {
                flex-direction: column;
            }

            .modal-content {
                width: 95%;
                margin: 1rem;
            }
        }

        .profile-photo-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
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
    </style>

    <script>
        let documentoIdRechazar = null;

        function validarDocumento(documentoId) {
            if (!confirm('¿Estás seguro de aprobar este documento?')) {
                return;
            }

            fetch(`/Comite/documentos/${documentoId}/aceptar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Documento aprobado correctamente', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al aprobar el documento', 'danger');
                });
        }

        function rechazarDocumento(documentoId) {
            documentoIdRechazar = documentoId;
            document.getElementById('rechazarModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('rechazarModal').style.display = 'none';
            document.getElementById('motivoRechazo').value = '';
            documentoIdRechazar = null;
        }

        function confirmarRechazo() {
            const motivo = document.getElementById('motivoRechazo').value.trim();

            if (!motivo) {
                showNotification('Por favor, describe los errores encontrados', 'warning');
                return;
            }

            fetch(`/Comite/documentos/${documentoIdRechazar}/rechazar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        motivo: motivo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Documento rechazado correctamente', 'success');
                        closeModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al rechazar el documento', 'danger');
                });
        }

        function verDocumento(documentoId) {
            // Abrir en nueva pestaña usando la ruta de ver documento
            const url = `/Comite/documentos/${documentoId}/ver`;
            window.open(url, '_blank');
        }

        function validarTodosDocumentos() {
            if (!confirm('¿Estás seguro de aprobar todos los documentos pendientes?')) {
                return;
            }

            const documentosPendientes = document.querySelectorAll('.document-card.pendiente');
            let validados = 0;
            const total = documentosPendientes.length;

            if (total === 0) {
                showNotification('No hay documentos pendientes para aprobar', 'info');
                return;
            }

            documentosPendientes.forEach(card => {
                const documentoId = card.querySelector('.btn-success')?.getAttribute('onclick')?.match(/\d+/)?.[0];
                if (documentoId) {
                    fetch(`/Comite/documentos/${documentoId}/aceptar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            validados++;
                            if (validados === total) {
                                showNotification('Todos los documentos han sido aprobados', 'success');
                                setTimeout(() => location.reload(), 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            validados++;
                        });
                }
            });
        }

        function rechazarTodosDocumentos() {
            if (!confirm(
                    '¿Estás seguro de rechazar todos los documentos pendientes? Esto cambiará el estado del usuario a "Rechazado".'
                )) {
                return;
            }

            const motivo = prompt('Describe el motivo general del rechazo de todos los documentos:');
            if (!motivo) {
                showNotification('Debes proporcionar un motivo para el rechazo', 'warning');
                return;
            }

            const documentosPendientes = document.querySelectorAll('.document-card.pendiente');
            let rechazados = 0;
            const total = documentosPendientes.length;

            if (total === 0) {
                showNotification('No hay documentos pendientes para rechazar', 'info');
                return;
            }

            documentosPendientes.forEach(card => {
                const documentoId = card.querySelector('.btn-danger')?.getAttribute('onclick')?.match(/\d+/)?.[0];
                if (documentoId) {
                    fetch(`/Comite/documentos/${documentoId}/rechazar`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                motivo: motivo
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            rechazados++;
                            if (rechazados === total) {
                                showNotification(
                                    'Todos los documentos han sido rechazados. El estado del usuario ha cambiado a "Rechazado".',
                                    'success');
                                setTimeout(() => location.reload(), 1000);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            rechazados++;
                        });
                }
            });
        }

        function showNotification(message, type) {
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
                background: ${type === 'success' ? '#28a745' : type === 'danger' ? '#dc3545' : '#ffc107'};
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

        // Cerrar modal al hacer clic fuera
        document.getElementById('rechazarModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        let usuarioIdRechazarInfo = null;

        function aprobarInformacionPersonal(usuarioId) {
            if (!confirm('¿Estás seguro de aprobar la información personal de este usuario?')) {
                return;
            }

            fetch(`/Comite/informacion-personal/${usuarioId}/aprobar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_usuario: usuarioId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Información personal aprobada correctamente', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al aprobar la información personal', 'danger');
                });
        }

        function rechazarInformacionPersonal(usuarioId) {
            usuarioIdRechazarInfo = usuarioId;
            document.getElementById('rechazarInfoPersonalModal').style.display = 'flex';
        }

        function closeInfoPersonalModal() {
            document.getElementById('rechazarInfoPersonalModal').style.display = 'none';
            document.getElementById('motivoRechazoInfoPersonal').value = '';
            usuarioIdRechazarInfo = null;
        }

        function confirmarRechazoInfoPersonal() {
            const motivo = document.getElementById('motivoRechazoInfoPersonal').value.trim();

            if (!motivo) {
                showNotification('Por favor, describe los errores encontrados en la información personal', 'warning');
                return;
            }

            if (!confirm(
                    '¿Estás seguro de rechazar la información personal? El estado del usuario cambiará a "Rechazado".')) {
                return;
            }

            fetch(`/Comite/informacion-personal/${usuarioIdRechazarInfo}/rechazar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id_usuario: usuarioIdRechazarInfo, // AÑADE ESTA LÍNEA
                        motivo_rechazo: motivo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Información personal rechazada correctamente', 'success');
                        closeInfoPersonalModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al rechazar la información personal', 'danger');
                });
        }

        // Cerrar modal de información personal al hacer clic fuera
        document.getElementById('rechazarInfoPersonalModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeInfoPersonalModal();
            }
        });
    </script>
@endsection
