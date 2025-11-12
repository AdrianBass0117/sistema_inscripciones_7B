<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Constancia de Inscripción - {{ $numero_constancia }}</title>
    <style>
        /* Estilos para PDF */
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.4;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 50px;
            position: relative;
            min-height: 1100px;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 25px;
            border-bottom: 4px solid #004F6E;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #004F6E;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            color: #004F6E;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 20px;
            color: #7f8c8d;
            margin-bottom: 30px;
            font-style: italic;
        }

        .section {
            margin: 40px 0;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            color: #004F6E;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 3px solid #004F6E;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
        }

        .info-item {
            margin-bottom: 18px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #004F6E;
        }

        .info-label {
            font-weight: bold;
            color: #004F6E;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            color: #2c3e50;
            font-weight: 500;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .description-box {
            background: #ffffff;
            border: 2px solid #004F6E;
            border-radius: 10px;
            padding: 25px;
            margin: 20px 0;
            line-height: 1.6;
        }

        .footer {
            margin-top: 80px;
            padding-top: 25px;
            border-top: 2px solid #bdc3c7;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            position: absolute;
            bottom: 40px;
            left: 50px;
            right: 50px;
        }

        .verification-box {
            background: #ffffff;
            border: 3px solid #27ae60;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .signature-area {
            margin-top: 100px;
            text-align: center;
        }

        .signature-line {
            width: 400px;
            border-top: 2px solid #2c3e50;
            margin: 60px auto 20px;
            padding-top: 20px;
        }

        .watermark {
            position: absolute;
            opacity: 0.03;
            font-size: 120px;
            transform: rotate(-45deg);
            top: 40%;
            left: 10%;
            color: #004F6E;
            pointer-events: none;
            font-weight: bold;
            z-index: -1;
        }

        .status-badge {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-left: 10px;
            text-transform: uppercase;
        }

        .certification-text {
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            margin: 40px 0;
            padding: 0 10px;
            color: #2c3e50;
        }

        .qr-area {
            text-align: center;
            margin: 40px 0;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px solid #ddd;
        }

        .qr-placeholder {
            width: 180px;
            height: 180px;
            background: #e0e0e0;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            font-size: 12px;
            color: #7f8c8d;
            flex-direction: column;
        }

        .page-number {
            position: absolute;
            bottom: 20px;
            right: 50px;
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Responsive para PDF */
        @media print {
            .page {
                padding: 30px 40px;
            }

            .no-print {
                display: none;
            }

            .status-badge {
                background: #27ae60 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .info-item {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Página 1: Información del Participante -->
    <div class="page">
        <!-- Marca de agua -->
        <div class="watermark">CONSTANCIA OFICIAL</div>

        <!-- Encabezado -->
        <div class="header">
            <div class="logo">Sistema de Inscripciones</div>
            <h1 class="title">Constancia de Inscripción</h1>
            <div class="subtitle">Certificado Oficial de Participación</div>
        </div>

        <!-- Información del Participante -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-user"></i> Datos del Participante
            </h2>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre Completo</span>
                    <span class="info-value">{{ $inscripcion->usuario->nombre_completo }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Número de Trabajador</span>
                    <span class="info-value">{{ $inscripcion->usuario->numero_trabajador }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Correo Electrónico</span>
                    <span class="info-value">{{ $inscripcion->usuario->email }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Teléfono</span>
                    <span class="info-value">{{ $inscripcion->usuario->telefono ?? 'No especificado' }}</span>
                </div>

                <div class="info-item full-width">
                    <span class="info-label">Número de Constancia</span>
                    <span class="info-value" style="font-size: 18px; font-weight: bold; color: #004F6E;">
                        {{ $numero_constancia }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Información de la Inscripción -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-clipboard-check"></i> Información de la Inscripción
            </h2>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Fecha de Inscripción</span>
                    <span class="info-value">{{ $inscripcion->fecha_inscripcion->format('d/m/Y') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Hora de Inscripción</span>
                    <span class="info-value">{{ $inscripcion->fecha_inscripcion->format('h:i A') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Fecha de Aceptación</span>
                    <span class="info-value">{{ $inscripcion->fecha_validacion->format('d/m/Y') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Estado Actual</span>
                    <span class="info-value">
                        Aceptado <span class="status-badge">Activo</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Texto de Certificación -->
        <div class="section">
            <div class="certification-text">
                <p>
                    Por medio de la presente, el <strong>Sistema de Inscripciones</strong> certifica que
                    <strong>{{ $inscripcion->usuario->nombre_completo }}</strong>
                    con número de trabajador <strong>{{ $inscripcion->usuario->numero_trabajador }}</strong>
                    se encuentra oficialmente inscrito(a) y aceptado(a) en el sistema.
                </p>

                <p>
                    La solicitud de inscripción fue registrada el
                    <strong>{{ $inscripcion->fecha_inscripcion->format('d \\d\\e F \\d\\e Y') }}</strong>
                    y fue aceptada oficialmente el
                    <strong>{{ $inscripcion->fecha_validacion->format('d \\d\\e F \\d\\e Y') }}</strong>.
                </p>
            </div>
        </div>

        <!-- Código de Verificación -->
        <div class="verification-box">
            <div style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #004F6E;">
                <i class="fas fa-shield-alt"></i> CÓDIGO DE VERIFICACIÓN
            </div>
            <div style="font-size: 22px; font-weight: bold; letter-spacing: 3px; color: #27ae60; margin-bottom: 10px; font-family: 'Courier New', monospace;">
                {{ $codigo_verificacion }}
            </div>
            <div style="font-size: 12px; color: #7f8c8d;">
                Utilice este código para verificar la autenticidad de este documento en el sistema oficial
            </div>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>
                <strong>Página 1 de 2</strong> |
                <strong>Constancia:</strong> {{ $numero_constancia }} |
                <strong>Emisión:</strong> {{ $fecha_emision }} |
                <strong>Válido hasta:</strong> {{ $fecha_vencimiento }}
            </p>
        </div>

        <div class="page-number">1</div>
    </div>

    <!-- Página 2: Detalles de la Disciplina -->
    <div class="page">
        <!-- Marca de agua -->
        <div class="watermark">DISCIPLINA</div>

        <!-- Encabezado -->
        <div class="header">
            <div class="logo">Sistema de Inscripciones</div>
            <h1 class="title">Detalles de la Disciplina</h1>
            <div class="subtitle">Información Específica de la Actividad</div>
        </div>

        <!-- Información General de la Disciplina -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-info-circle"></i> Información General
            </h2>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre de la Disciplina</span>
                    <span class="info-value" style="font-weight: bold; font-size: 18px; color: #004F6E;">
                        {{ $inscripcion->disciplina->nombre }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Categoría</span>
                    <span class="info-value">
                        <strong>{{ $inscripcion->disciplina->getCategoriaFormateada() }}</strong>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Género</span>
                    <span class="info-value">
                        <strong>{{ $inscripcion->disciplina->getGeneroFormateado() }}</strong>
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Cupo Máximo</span>
                    <span class="info-value">{{ $inscripcion->disciplina->cupo_maximo }} participantes</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Cupos Disponibles</span>
                    <span class="info-value">{{ $inscripcion->disciplina->getCuposDisponibles() }} cupos</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Estado de la Disciplina</span>
                    <span class="info-value">
                        {{ $inscripcion->disciplina->getEstadoFormateado() }}
                        <span class="status-badge" style="background: #27ae60;">
                            {{ $inscripcion->disciplina->estaActiva() ? 'Activa' : 'Inactiva' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Fechas y Períodos -->
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-calendar-alt"></i> Fechas y Períodos
            </h2>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Fecha de Inicio</span>
                    <span class="info-value">
                        {{ $inscripcion->disciplina->fecha_inicio ? $inscripcion->disciplina->fecha_inicio->format('d/m/Y') : 'No definida' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Fecha de Fin</span>
                    <span class="info-value">
                        {{ $inscripcion->disciplina->fecha_fin ? $inscripcion->disciplina->fecha_fin->format('d/m/Y') : 'No definida' }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Días Restantes</span>
                    <span class="info-value">
                        {{ $inscripcion->disciplina->getTextoDiasRestantes() }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Estado de Disponibilidad</span>
                    <span class="info-value">
                        {{ $inscripcion->disciplina->getTextoEstadoDisponibilidad() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Descripción de la Disciplina -->
        @if($inscripcion->disciplina->descripcion)
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-file-alt"></i> Descripción
            </h2>

            <div class="description-box">
                <div style="font-size: 16px; line-height: 1.6; color: #2c3e50;">
                    {{ $inscripcion->disciplina->descripcion }}
                </div>
            </div>
        </div>
        @endif

        <!-- Instrucciones -->
        @if($inscripcion->disciplina->instrucciones)
        <div class="section">
            <h2 class="section-title">
                <i class="fas fa-list-alt"></i> Instrucciones
            </h2>

            <div class="description-box">
                <div style="font-size: 15px; line-height: 1.6; color: #2c3e50;">
                    {{ $inscripcion->disciplina->instrucciones }}
                </div>
            </div>
        </div>
        @endif

        <!-- Código QR de Verificación -->
        <div class="section">
            <div class="qr-area">
                <div style="font-size: 16px; font-weight: bold; margin-bottom: 20px; color: #004F6E;">
                    <i class="fas fa-qrcode"></i> CÓDIGO QR DE VERIFICACIÓN
                </div>
                <div class="qr-placeholder">
                    <div style="font-size: 14px; font-weight: bold; margin-bottom: 10px;">QR CODE</div>
                    <div style="font-size: 10px; text-align: center;">
                        Escanee para verificar<br>
                        {{ $codigo_verificacion }}
                    </div>
                </div>
                <div style="font-size: 12px; color: #7f8c8d; margin-top: 15px;">
                    Utilice un lector de QR para verificar la autenticidad de este documento
                </div>
            </div>
        </div>

        <!-- Firma y Validación -->
        <div class="signature-area">
            <div class="signature-line"></div>
            <p style="font-size: 16px; font-weight: bold; color: #2c3e50; margin-bottom: 5px;">
                Sistema de Inscripciones
            </p>
            <p style="font-size: 14px; color: #7f8c8d; margin: 0;">
                Documento generado automáticamente - Constancia oficial válida
            </p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>
                <strong>Página 2 de 2</strong> |
                <strong>Constancia:</strong> {{ $numero_constancia }} |
                <strong>Código:</strong> {{ $codigo_verificacion }} |
                <strong>Emisión:</strong> {{ $fecha_emision }}
            </p>
            <p style="font-size: 11px; margin-top: 5px;">
                Este documento es válido por 6 meses a partir de la fecha de emisión.
                Para verificar autenticidad: sistema-de-inscripciones.com/verificar
            </p>
        </div>

        <div class="page-number">2</div>
    </div>
</body>
</html>
