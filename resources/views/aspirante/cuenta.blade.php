@extends('aspirante.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Encabezado -->
        <div class="account-header">
            <div class="header-content">
                <h1><i class="fas fa-edit"></i> Corrección de Documentos</h1>
                <p class="warning-text"><i class="fas fa-exclamation-triangle"></i> Solo modifica los documentos señalados con
                    observaciones</p>
            </div>
            <div class="header-illustration">
                <i class="fas fa-file-edit"></i>
            </div>
        </div>

        <!-- Alertas de Observaciones -->
        @if ($documentosRechazados->count() > 0)
            <div class="observations-alert">
                <div class="alert-content">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h3>Documentos con Observaciones</h3>
                        <p>Se han detectado errores en los siguientes documentos. Por favor, corrígelos y vuelve a subirlos.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenido Principal -->
        <div class="account-content">
            <!-- Formulario de Edición -->
            <div class="form-section">
                <!-- Información Personal (Solo lectura o editable si fue rechazada) -->
                <div
                    class="form-card {{ $usuario->validacionInformacionPersonalActual() && $usuario->validacionInformacionPersonalActual()->estaRechazada() ? 'correction-card' : 'readonly-card' }}">
                    <div class="card-header">
                        <h2>
                            <i class="fas fa-user-circle"></i> Información Personal
                            @if ($usuario->validacionInformacionPersonalActual() && $usuario->validacionInformacionPersonalActual()->estaRechazada())
                                <span class="correction-badge">Requiere Corrección</span>
                            @else
                                <span class="readonly-badge">Solo lectura</span>
                            @endif
                        </h2>
                    </div>
                    <div class="card-body">
                        @if ($usuario->validacionInformacionPersonalActual() && $usuario->validacionInformacionPersonalActual()->estaRechazada())
                            <!-- Mostrar observaciones de rechazo -->
                            <div class="observation-details">
                                <strong>Observación:</strong>
                                <span>{{ $usuario->validacionInformacionPersonalActual()->motivo_rechazo }}</span>
                                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary);">
                                    Fecha:
                                    {{ $usuario->validacionInformacionPersonalActual()->updated_at->format('d/m/Y H:i') }}
                                </small>
                            </div>

                            <!-- Botón para corregir información -->
                            <div class="correction-action" style="margin-top: 1.5rem;">
                                <a href="{{ route('aspirante.correccion-informacion') }}" class="btn-primary">
                                    <i class="fas fa-edit"></i>
                                    Corregir Información Personal
                                </a>
                                <p style="margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
                                    Tu información personal fue rechazada. Haz clic aquí para corregirla.
                                </p>
                            </div>
                        @else
                            <!-- Información en solo lectura -->
                            <div class="readonly-info">
                                <div class="info-item">
                                    <label>Número de Trabajador:</label>
                                    <span>{{ $usuario->numero_trabajador }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Nombre Completo:</label>
                                    <span>{{ $usuario->nombre_completo }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Fecha de Nacimiento:</label>
                                    <span>{{ $usuario->fecha_nacimiento->format('d/m/Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <label>CURP:</label>
                                    <span>{{ $usuario->curp }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Antigüedad:</label>
                                    <span>{{ $usuario->antiguedad }} años</span>
                                </div>
                                <div class="info-item">
                                    <label>Correo Electrónico:</label>
                                    <span>{{ $usuario->email }}</span>
                                </div>
                                <div class="info-item">
                                    <label>Teléfono:</label>
                                    <span>{{ $usuario->telefono ?? 'No especificado' }}</span>
                                </div>
                            </div>

                            @if ($usuario->validacionInformacionPersonalActual() && $usuario->validacionInformacionPersonalActual()->estaAceptada())
                                <div class="no-changes-notice">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Tu información personal ha sido verificada y aprobada. No requiere
                                        modificaciones.</span>
                                </div>
                            @else
                                <div class="readonly-notice">
                                    <i class="fas fa-clock"></i>
                                    <span>Tu información personal está en proceso de revisión. No requiere acción por el
                                        momento.</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Documentos con Observaciones -->
                @if ($documentosRechazados->count() > 0)
                    <div class="form-card correction-card">
                        <div class="card-header">
                            <h2><i class="fas fa-exclamation-circle"></i> Documentos que Requieren Corrección</h2>
                        </div>
                        <div class="card-body">
                            @foreach ($documentosRechazados as $documento)
                                <div class="document-correction">
                                    <div class="document-header">
                                        <div class="document-info">
                                            <h3>{{ $documento->getTipoDocumentoFormateado() }}</h3>
                                            <p class="document-status error">Documento rechazado</p>
                                            @if ($documento->errores->count() > 0)
                                                @php
                                                    // Obtener el error más reciente
                                                    $errorReciente = $documento->errores
                                                        ->sortByDesc('created_at')
                                                        ->first();
                                                @endphp
                                                <div class="observation-details">
                                                    <strong>Observación:</strong>
                                                    <span>{{ $errorReciente->descripcion_error }}</span>
                                                    <small
                                                        style="display: block; margin-top: 0.5rem; color: var(--text-secondary);">
                                                        Fecha: {{ $errorReciente->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="document-required">
                                            <span class="correction-badge">Requiere Corrección</span>
                                        </div>
                                    </div>
                                    <div class="current-document">
                                        <h4>Documento Actual:</h4>
                                        <div class="current-file">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>{{ basename($documento->url_archivo) }}</span>
                                            <small>Subido el {{ $documento->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="upload-container">
                                        <!-- FORMULARIO INDIVIDUAL PARA CADA DOCUMENTO RECHAZADO -->
                                        <form action="{{ route('documento.subir-correccion', $documento->id_documento) }}"
                                            method="POST" enctype="multipart/form-data" class="upload-form"
                                            id="uploadForm{{ $documento->id_documento }}">
                                            @csrf
                                            <div class="upload-area" id="uploadArea{{ $documento->id_documento }}">
                                                <div class="upload-placeholder">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <p>Subir nueva versión corregida</p>
                                                    <small>Reemplace solo si es necesario. Formatos: PDF, JPG, PNG (Máx.
                                                        5MB)</small>
                                                </div>
                                                <input type="file" name="documento" class="file-input"
                                                    accept=".pdf,.jpg,.jpeg,.png"
                                                    data-document-id="{{ $documento->id_documento }}" required>
                                            </div>
                                            <div id="preview{{ $documento->id_documento }}" class="file-preview"></div>
                                            <div id="actions{{ $documento->id_documento }}" class="upload-actions"
                                                style="display: none; margin-top: 1rem;">
                                                <button type="submit" class="btn-primary">
                                                    <i class="fas fa-upload"></i>
                                                    Confirmar Subida
                                                </button>
                                                <button type="button" class="btn-secondary"
                                                    onclick="cancelUpload({{ $documento->id_documento }})">
                                                    <i class="fas fa-times"></i>
                                                    Cancelar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Documentos Sujetos a Revisión -->
                @if ($documentosPendientes->count() > 0)
                    <div class="form-card pending-card">
                        <div class="card-header">
                            <h2><i class="fas fa-clock"></i> Documentos Sujetos a Revisión</h2>
                        </div>
                        <div class="card-body">
                            @foreach ($documentosPendientes as $documento)
                                <div class="document-pending">
                                    <div class="document-header">
                                        <div class="document-info">
                                            <h3>{{ $documento->getTipoDocumentoFormateado() }}</h3>
                                            <p class="document-status pending">En proceso de revisión</p>
                                        </div>
                                        <div class="document-required">
                                            <span class="pending-badge">Sujeto a revisión</span>
                                        </div>
                                    </div>
                                    <div class="current-document">
                                        <div class="current-file">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>{{ basename($documento->url_archivo) }}</span>
                                            <small>Subido el {{ $documento->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    <div class="pending-notice">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Este documento está siendo revisado. No requiere acción por el
                                            momento.</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Documentos Aprobados -->
                @if ($documentosAprobados->count() > 0)
                    <div class="form-card approved-card">
                        <div class="card-header">
                            <h2><i class="fas fa-check-circle"></i> Documentos Aprobados</h2>
                        </div>
                        <div class="card-body">
                            <div class="approved-documents">
                                @foreach ($documentosAprobados as $documento)
                                    <div class="approved-item">
                                        <i class="fas fa-file-invoice approved-icon"></i>
                                        <div class="approved-info">
                                            <h4>{{ $documento->getTipoDocumentoFormateado() }}</h4>
                                            <p>Documento aprobado - No requiere cambios</p>
                                            <small>{{ basename($documento->url_archivo) }} - Subido el
                                                {{ $documento->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <span class="approved-badge">Aprobado</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="no-changes-notice">
                                <i class="fas fa-check-circle"></i>
                                <span>Estos documentos han sido verificados y aprobados. No los modifique.</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Instrucciones de Envío -->
                <div class="instructions-card">
                    <div class="card-header">
                        <h2><i class="fas fa-list-alt"></i> Instrucciones para la Corrección</h2>
                    </div>
                    <div class="card-body">
                        <ol class="instructions-list">
                            <li><strong>Revise las observaciones</strong> detalladas para cada documento rechazado</li>
                            <li><strong>Suba únicamente los documentos señalados</strong> con observaciones</li>
                            <li><strong>No modifique los documentos aprobados</strong> a menos que se le indique</li>
                            <li><strong>Verifique que los nuevos archivos</strong> cumplan con los formatos requeridos
                            </li>
                            <li><strong>Una vez enviado</strong>, el sistema revisará solo los documentos corregidos
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Acciones del Formulario -->
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="window.history.back()">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </div>

            <!-- Resumen de Correcciones -->
            <div class="progress-section">
                <div class="progress-card">
                    <div class="card-header">
                        <h2><i class="fas fa-tasks"></i> Estado de Correcciones</h2>
                    </div>
                    <div class="card-body">
                        <div class="correction-summary">
                            <div class="summary-item pending">
                                <div class="summary-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="summary-content">
                                    <h4>Por Corregir</h4>
                                    <p>{{ $documentosRechazados->count() }} documentos con observaciones</p>
                                </div>
                            </div>
                            <div class="summary-item review">
                                <div class="summary-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="summary-content">
                                    <h4>En Revisión</h4>
                                    <p>{{ $documentosPendientes->count() }} documentos en proceso</p>
                                </div>
                            </div>
                            <div class="summary-item completed">
                                <div class="summary-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="summary-content">
                                    <h4>Aprobados</h4>
                                    <p>{{ $documentosAprobados->count() }} documentos verificados</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        /* === VARIABLES Y ESTILOS GENERALES === */
        :root {
            --primary-color: #004F6E;
            --secondary-color: #00AA8B;
            --accent-color: #0077B6;
            --success: #38A169;
            --warning: #D69E2E;
            --danger: #E53E3E;
            --bg-white: #FFFFFF;
            --bg-light: #F7FAFC;
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dashboard-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* === ENCABEZADO === */
        .account-header {
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-lg);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .account-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .header-content h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-content p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .header-illustration {
            font-size: 5rem;
            opacity: 0.7;
            z-index: 1;
        }

        /* Agregar estilos para los nuevos estados */
        .pending-card {
            border-left: 4px solid var(--warning);
        }

        .pending-badge {
            background: var(--warning);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .document-pending {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .document-status.pending {
            color: var(--warning);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0.25rem 0;
        }

        .pending-notice {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: rgba(214, 158, 46, 0.1);
            border-radius: 6px;
            margin-top: 1rem;
            color: var(--warning);
            font-size: 0.9rem;
        }

        .summary-item.review {
            background: rgba(214, 158, 46, 0.1);
            border: 1px solid rgba(214, 158, 46, 0.2);
        }

        .summary-item.review .summary-icon {
            background: var(--warning);
            color: white;
        }

        /* === CONTENIDO PRINCIPAL === */
        .account-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        /* === FORMULARIOS === */
        .form-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .form-card {
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-light);
        }

        .card-header h2 {
            margin: 0;
            color: var(--primary-color);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .account-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* === ESTILOS ESPECÍFICOS PARA CORRECCIÓN === */
        .warning-text {
            background: rgba(214, 158, 46, 0.1);
            border: 1px solid var(--warning);
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .warning-text i {
            color: var(--warning);
        }

        .observations-alert {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffecb5;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .alert-content i {
            color: var(--warning);
            font-size: 1.5rem;
            margin-top: 0.25rem;
        }

        .alert-content h3 {
            margin: 0 0 0.5rem 0;
            color: #856404;
            font-size: 1.1rem;
        }

        .alert-content p {
            margin: 0;
            color: #856404;
            font-size: 0.9rem;
        }

        /* Tarjetas de solo lectura */
        .readonly-card {
            border-left: 4px solid var(--text-secondary);
        }

        .readonly-badge {
            background: var(--text-secondary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: auto;
        }

        .readonly-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--bg-light);
            border-radius: 6px;
        }

        .info-item label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .info-item span {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .readonly-notice {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: rgba(113, 128, 150, 0.1);
            border-radius: 6px;
            margin-top: 1rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .readonly-notice i {
            color: var(--text-secondary);
        }

        /* Tarjetas de corrección */
        .correction-card {
            border-left: 4px solid var(--warning);
        }

        .correction-badge {
            background: var(--warning);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .document-correction {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
        }

        .document-correction:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .document-status.error {
            color: var(--danger);
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0.25rem 0;
        }

        .observation-details {
            background: rgba(229, 62, 62, 0.05);
            border: 1px solid rgba(229, 62, 62, 0.2);
            border-radius: 6px;
            padding: 1rem;
            margin-top: 0.75rem;
        }

        .observation-details strong {
            color: var(--danger);
            font-size: 0.9rem;
        }

        .observation-details span {
            color: var(--text-primary);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .current-document {
            margin: 1.5rem 0;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
        }

        .current-document h4 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .current-file {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: white;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .current-file i {
            color: var(--danger);
            font-size: 1.5rem;
        }

        .current-file span {
            flex: 1;
            color: var(--text-primary);
            font-weight: 600;
        }

        .current-file small {
            color: var(--text-secondary);
        }

        .current-photo {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .photo-preview.small {
            width: 80px;
            height: 100px;
            font-size: 0.7rem;
        }

        .photo-preview.small i {
            font-size: 1.5rem;
        }

        /* Subida de fotografía corregida */
        .photo-upload-correction {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        .photo-requirements h4 {
            color: var(--warning);
            margin-bottom: 1rem;
        }

        .photo-requirements li strong {
            color: var(--text-primary);
        }

        .photo-preview.new-photo {
            width: 150px;
            height: 180px;
            margin-top: 1rem;
        }

        /* Documentos aprobados */
        .approved-card {
            border-left: 4px solid var(--success);
        }

        .approved-documents {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .approved-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .approved-icon {
            color: var(--success);
            font-size: 1.5rem;
        }

        .approved-info {
            flex: 1;
        }

        .approved-info h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .approved-info p {
            margin: 0 0 0.25rem 0;
            color: var(--success);
            font-size: 0.8rem;
            font-weight: 600;
        }

        .approved-info small {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        .approved-badge {
            background: var(--success);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .no-changes-notice {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: rgba(56, 161, 105, 0.1);
            border-radius: 6px;
            margin-top: 1rem;
            color: var(--success);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .no-changes-notice i {
            color: var(--success);
        }

        /* Instrucciones */
        .instructions-card {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #90caf9;
        }

        .instructions-list {
            padding-left: 1.5rem;
            margin: 0;
        }

        .instructions-list li {
            margin-bottom: 1rem;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .instructions-list li:last-child {
            margin-bottom: 0;
        }

        .instructions-list strong {
            color: var(--primary-color);
        }

        /* === SUBIDA DE DOCUMENTOS === */
        .document-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .document-info h3 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .document-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .upload-container {
            margin-top: 1.5rem;
        }

        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .upload-area:hover {
            border-color: var(--secondary-color);
            background: rgba(0, 170, 139, 0.05);
        }

        .upload-area.dragover {
            border-color: var(--secondary-color);
            background: rgba(0, 170, 139, 0.1);
        }

        .upload-placeholder i {
            font-size: 2rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .upload-placeholder p {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
        }

        .browse-link {
            color: var(--secondary-color);
            text-decoration: underline;
            cursor: pointer;
        }

        .upload-placeholder small {
            color: var(--text-secondary);
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-preview {
            margin-top: 1rem;
            display: none;
        }

        .file-preview.show {
            display: block;
        }

        .preview-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .preview-icon {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .preview-info {
            flex: 1;
        }

        .preview-info h4 {
            margin: 0 0 0.25rem 0;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .preview-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .preview-actions {
            display: flex;
            gap: 0.5rem;
        }

        .preview-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: color 0.2s ease;
        }

        .preview-btn:hover {
            color: var(--primary-color);
        }

        /* === FOTOGRAFÍA === */
        .photo-upload {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .photo-preview-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .photo-preview {
            width: 200px;
            height: 250px;
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--bg-light);
            color: var(--text-secondary);
            text-align: center;
        }

        .photo-preview.has-photo {
            border: none;
            background-size: cover;
            background-position: center;
        }

        .photo-preview i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .photo-requirements h4 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .photo-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .photo-requirements li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .photo-requirements i {
            color: var(--success);
            font-size: 0.7rem;
        }

        .photo-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: center;
            align-items: center;
        }

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
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #009975;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #E2E8F0;
        }

        /* === PROGRESO === */
        .progress-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .progress-card {
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .progress-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .progress-item:last-child {
            border-bottom: none;
        }

        .progress-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .progress-item.completed .progress-icon {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .progress-item.pending .progress-icon {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .progress-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .progress-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* Resumen de correcciones */
        .correction-summary {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 8px;
        }

        .summary-item.pending {
            background: rgba(214, 158, 46, 0.1);
            border: 1px solid rgba(214, 158, 46, 0.2);
        }

        .summary-item.completed {
            background: rgba(56, 161, 105, 0.1);
            border: 1px solid rgba(56, 161, 105, 0.2);
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .summary-item.pending .summary-icon {
            background: var(--warning);
            color: white;
        }

        .summary-item.completed .summary-icon {
            background: var(--success);
            color: white;
        }

        .summary-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .summary-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* Timeline */
        .correction-timeline h4 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-marker {
            position: absolute;
            left: -2rem;
            top: 0.25rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            background: white;
        }

        .timeline-item.completed .timeline-marker {
            background: var(--success);
            border-color: var(--success);
        }

        .timeline-item.current .timeline-marker {
            background: var(--warning);
            border-color: var(--warning);
            animation: pulse 2s infinite;
        }

        .timeline-item.pending .timeline-marker {
            background: white;
            border-color: var(--border-color);
        }

        .timeline-content h5 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 0.8rem;
        }

        .timeline-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.7rem;
        }

        .timeline-item.completed .timeline-content h5,
        .timeline-item.completed .timeline-content p {
            color: var(--success);
        }

        .timeline-item.current .timeline-content h5,
        .timeline-item.current .timeline-content p {
            color: var(--warning);
        }

        /* === ACCIONES DEL FORMULARIO === */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        /* === CORRECCIONES RESPONSIVE === */
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
                width: 100%;
                overflow-x: hidden;
                box-sizing: border-box;
            }

            .account-header {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .header-content h1 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .header-illustration {
                font-size: 3rem;
                display: none;
                /* Ocultar ilustración en móviles para ahorrar espacio */
            }

            .account-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                width: 100%;
            }

            .form-section {
                width: 100%;
                min-width: 0;
                /* Previene desbordamiento */
            }

            .form-card {
                width: 100%;
                box-sizing: border-box;
                margin: 0;
            }

            .card-body {
                padding: 1rem;
                overflow: hidden;
                /* Previene desbordamiento de contenido */
            }

            /* Corrección para información personal */
            .readonly-info {
                gap: 0.75rem;
            }

            .info-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
                padding: 0.5rem;
                width: 100%;
                box-sizing: border-box;
            }

            .info-item label {
                font-size: 0.8rem;
            }

            .info-item span {
                font-size: 0.8rem;
                word-break: break-word;
            }

            /* Corrección para documentos */
            .document-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
                width: 100%;
            }

            .document-required {
                align-self: flex-start;
            }

            .observation-details {
                padding: 0.75rem;
                font-size: 0.8rem;
            }

            /* Corrección para subida de fotografía */
            .photo-upload-correction {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                width: 100%;
            }

            .photo-actions {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .photo-preview.new-photo {
                width: 120px;
                height: 150px;
                margin: 1rem auto;
            }

            .upload-area {
                padding: 1.5rem;
                min-height: 100px;
            }

            .upload-placeholder p {
                font-size: 0.9rem;
            }

            .upload-placeholder small {
                font-size: 0.7rem;
            }

            /* Corrección para documentos aprobados */
            .approved-item {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
                padding: 0.75rem;
            }

            .approved-info {
                text-align: center;
            }

            /* Corrección para acciones del formulario */
            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
                padding: 1rem;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
                padding: 0.875rem 1rem;
            }

            /* Corrección para timeline */
            .timeline {
                padding-left: 1.5rem;
            }

            .timeline-marker {
                left: -1.5rem;
                width: 0.75rem;
                height: 0.75rem;
            }

            .correction-summary {
                gap: 0.75rem;
            }

            .summary-item {
                padding: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-content {
                padding: 0.25rem;
            }

            .account-header {
                padding: 1rem;
                border-radius: 12px;
            }

            .header-content h1 {
                font-size: 1.25rem;
            }

            .header-content p {
                font-size: 0.9rem;
            }

            .observations-alert {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .alert-content {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
            }

            .card-header {
                padding: 1rem;
            }

            .card-header h2 {
                font-size: 1.1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .readonly-badge,
            .correction-badge,
            .approved-badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.5rem;
            }

            .current-document {
                padding: 0.75rem;
                margin: 1rem 0;
            }

            .current-file {
                flex-direction: column;
                text-align: center;
                gap: 0.5rem;
                padding: 0.75rem;
            }

            .photo-requirements ul {
                gap: 0.5rem;
                padding-left: 0.5rem;
            }

            .photo-requirements li {
                font-size: 0.75rem;
                align-items: flex-start;
            }

            .instructions-list {
                padding-left: 1rem;
            }

            .instructions-list li {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }

            /* Asegurar que no haya overflow horizontal */
            body {
                overflow-x: hidden;
            }

            .dashboard-content,
            .account-content,
            .form-section,
            .form-card,
            .card-body {
                max-width: 100vw;
                overflow-x: hidden;
            }
        }

        /* Prevenir scroll horizontal global */
        body {
            overflow-x: hidden;
        }

        .dashboard-content {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* === ANIMACIONES === */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(214, 158, 46, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(214, 158, 46, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(214, 158, 46, 0);
            }
        }

        /* Agregar estilos para las acciones de upload */
        .upload-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            align-items: center;
        }

        .upload-actions .btn-primary,
        .upload-actions .btn-secondary {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }

        .notification {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Mejorar estilos de vista previa */
        .preview-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .preview-icon img {
            border-radius: 6px;
            object-fit: cover;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejo de subida de archivos para documentos rechazados
            const uploadAreas = document.querySelectorAll('.upload-area');

            uploadAreas.forEach(area => {
                const fileInput = area.querySelector('.file-input');
                const documentId = fileInput.getAttribute('data-document-id');
                const preview = document.getElementById('preview' + documentId);
                const actions = document.getElementById('actions' + documentId);

                // Click en el área
                area.addEventListener('click', function(e) {
                    if (e.target !== fileInput) {
                        fileInput.click();
                    }
                });

                // Cambio de archivo
                fileInput.addEventListener('change', function(e) {
                    handleFileUpload(this, preview, actions, documentId);
                });

                // Drag and drop
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    area.classList.add('dragover');
                });

                area.addEventListener('dragleave', function() {
                    area.classList.remove('dragover');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    area.classList.remove('dragover');

                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        handleFileUpload(fileInput, preview, actions, documentId);
                    }
                });
            });

            // Manejar envío de formularios
            const uploadForms = document.querySelectorAll('.upload-form');
            uploadForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const documentId = this.id.replace('uploadForm', '');
                    const submitBtn = this.querySelector('button[type="submit"]');

                    // Mostrar loading
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subiendo...';
                    submitBtn.disabled = true;

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor');
                            }
                            return response.text();
                        })
                        .then(html => {
                            // Recargar la página para mostrar los cambios
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(
                                'Error al subir el documento. Por favor, intente nuevamente.'
                            );
                            submitBtn.innerHTML =
                                '<i class="fas fa-upload"></i> Confirmar Subida';
                            submitBtn.disabled = false;
                        });
                });
            });
        });

        function handleFileUpload(input, preview, actions, documentId) {
            const file = input.files[0];
            if (!file) return;

            // Validar tamaño (5MB máximo)
            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('El archivo es demasiado grande. El tamaño máximo permitido es 5MB.');
                input.value = '';
                return;
            }

            // Validar tipo de archivo
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Tipo de archivo no válido. Solo se permiten PDF, JPG y PNG.');
                input.value = '';
                return;
            }

            // Mostrar vista previa
            const fileName = file.name;
            const fileSize = formatFileSize(file.size);
            const fileType = getFileType(fileName);

            preview.innerHTML = `
            <div class="preview-item">
                <div class="preview-icon">
                    <i class="fas fa-${fileType.icon}"></i>
                </div>
                <div class="preview-info">
                    <h4>${fileName}</h4>
                    <p>${fileSize} - ${fileType.name}</p>
                    <small>Listo para subir</small>
                </div>
                <div class="preview-actions">
                    <button type="button" class="preview-btn" onclick="cancelUpload(${documentId})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
            preview.classList.add('show');

            // Mostrar acciones
            actions.style.display = 'flex';
            actions.style.gap = '1rem';

            // Si es una imagen, mostrar vista previa
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.style.maxWidth = '100px';
                    imgElement.style.maxHeight = '100px';
                    imgElement.style.borderRadius = '6px';

                    const previewIcon = preview.querySelector('.preview-icon');
                    previewIcon.innerHTML = '';
                    previewIcon.appendChild(imgElement);
                };
                reader.readAsDataURL(file);
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function getFileType(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                return {
                    name: 'Imagen',
                    icon: 'image'
                };
            } else if (ext === 'pdf') {
                return {
                    name: 'Documento PDF',
                    icon: 'file-pdf'
                };
            } else {
                return {
                    name: 'Archivo',
                    icon: 'file'
                };
            }
        }

        // Función para cancelar la subida
        window.cancelUpload = function(documentId) {
            const preview = document.getElementById('preview' + documentId);
            const actions = document.getElementById('actions' + documentId);
            const fileInput = document.querySelector(`input[data-document-id="${documentId}"]`);
            const uploadArea = document.getElementById('uploadArea' + documentId);

            // Limpiar todo
            fileInput.value = '';
            preview.classList.remove('show');
            preview.innerHTML = '';
            actions.style.display = 'none';
            uploadArea.classList.remove('dragover');
        };

        // Mostrar mensajes de éxito/error
        @if (session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif

        function showNotification(message, type) {
            // Crear notificación
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease;
        `;

            if (type === 'success') {
                notification.style.background = 'var(--success)';
            } else {
                notification.style.background = 'var(--danger)';
            }

            notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
            ${message}
        `;

            document.body.appendChild(notification);

            // Remover después de 5 segundos
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
@endsection
