@extends('layouts.guest')

@section('title', 'Crear Cuenta')

@section('content')
    <div class="register-container">
        <!-- Panel Izquierdo - Formulario -->
        <div class="form-panel">
            <div class="form-content">
                <div class="auth-header">
                    <h2 class="auth-title">Únete a Nosotros</h2>
                    <p class="auth-subtitle">Crea tu cuenta y participa en el evento</p>
                </div>

                <form method="POST" action="{{ route('register.submit') }}" class="auth-form" data-scroll
                    enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h4>Errores de validación:</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Información Personal -->
                    <div class="form-section" data-scroll data-scroll-delay="0">
                        <h3 class="section-title">
                            <i class="fas fa-user-circle"></i>
                            Información Personal
                        </h3>

                        <div class="form-group">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user"></i>
                                Nombre Completo
                            </label>
                            <div class="input-container">
                                <input type="text" class="form-control" id="nombre" name="nombre" required
                                    placeholder="Juan Pérez García" value="{{ old('nombre') }}">
                                <div class="input-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                            </div>
                            @error('nombre')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="numero_trabajador" class="form-label">
                                    <i class="fas fa-id-badge"></i>
                                    Número de Trabajador
                                </label>
                                <div class="input-container">
                                    <input type="text" class="form-control" id="numero_trabajador"
                                        name="numero_trabajador" required placeholder="Ej: 12345"
                                        value="{{ old('numero_trabajador') }}">
                                    <div class="input-icon">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                </div>
                                @error('numero_trabajador')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fecha_nacimiento" class="form-label">
                                    <i class="fas fa-birthday-cake"></i>
                                    Fecha de Nacimiento
                                </label>
                                <div class="input-container">
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                        required value="{{ old('fecha_nacimiento') }}">
                                    <div class="input-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </div>
                                @error('fecha_nacimiento')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group full-width">
                            <label for="curp" class="form-label">
                                <i class="fas fa-fingerprint"></i>
                                CURP
                            </label>
                            <div class="input-container">
                                <input type="text" class="form-control" id="curp" name="curp" required
                                    placeholder="Ej: ABCDEFGHIJKLMNOPQ1" value="{{ old('curp') }}" maxlength="18">
                                <div class="input-icon">
                                    <i class="fas fa-id-card-alt"></i>
                                </div>
                            </div>
                            @error('curp')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="form-section" data-scroll data-scroll-delay="50">
                        <h3 class="section-title">
                            <i class="fas fa-address-book"></i>
                            Información de Contacto
                        </h3>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Correo Electrónico
                            </label>
                            <div class="input-container">
                                <input type="email" class="form-control" id="email" name="email" required
                                    placeholder="tu.correo@universidad.edu" value="{{ old('email') }}">
                                <div class="input-icon">
                                    <i class="fas fa-at"></i>
                                </div>
                                <div class="email-feedback"></div>
                            </div>
                            @error('email')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="telefono" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono
                                </label>
                                <div class="input-container">
                                    <input type="tel" class="form-control" id="telefono" name="telefono" required
                                        placeholder="+52 123 456 7890" value="{{ old('telefono') }}">
                                    <div class="input-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                </div>
                                @error('telefono')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="antiguedad" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Antigüedad (años)
                                </label>
                                <div class="input-container">
                                    <input type="number" class="form-control" id="antiguedad" name="antiguedad"
                                        required placeholder="Ej: 5" min="0" max="50"
                                        value="{{ old('antiguedad') }}">
                                    <div class="input-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                </div>
                                @error('antiguedad')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Seguridad -->
                    <div class="form-section" data-scroll data-scroll-delay="100">
                        <h3 class="section-title">
                            <i class="fas fa-shield-alt"></i>
                            Seguridad
                        </h3>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Contraseña
                                </label>
                                <div class="input-container">
                                    <input type="password" class="form-control" id="password" name="password" required
                                        placeholder="Mínimo 8 caracteres" minlength="8">
                                    <div class="input-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <button type="button" class="password-toggle" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill"></div>
                                    </div>
                                    <span class="strength-text">Seguridad de la contraseña</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Confirmar Contraseña
                                </label>
                                <div class="input-container">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required placeholder="Repite tu contraseña">
                                    <div class="input-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <button type="button" class="password-toggle" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos -->
                    <div class="form-section" data-scroll data-scroll-delay="150">
                        <h3 class="section-title">
                            <i class="fas fa-file-upload"></i>
                            Documentos Requeridos
                        </h3>

                        <div class="form-grid">
                            <div class="file-upload-group">
                                <label for="constancia_laboral" class="form-label">
                                    <i class="fas fa-file-pdf"></i>
                                    Constancia Laboral
                                </label>
                                <i class="info-icon" data-modal="modalConstancia">
                                    <i class="fas fa-info-circle"></i>
                                </i>
                                <div class="file-upload-container @error('constancia_laboral') error @enderror">
                                    <input type="file" class="file-input" id="constancia_laboral"
                                        name="constancia_laboral" accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="constancia_laboral" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Subir archivo</span>
                                        <span class="file-size">PDF, JPG, PNG - Máx. 5MB</span>
                                    </label>
                                </div>
                                @error('constancia_laboral')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <div class="file-upload-group">
                                <label for="cfdi" class="form-label">
                                    <i class="fas fa-file-invoice"></i>
                                    CFDI/Recibo de Nómina
                                </label>
                                <i class="info-icon" data-modal="modalRecibo">
                                    <i class="fas fa-info-circle"></i>
                                </i>
                                <div class="file-upload-container @error('cfdi') error @enderror">
                                    <input type="file" class="file-input" id="cfdi" name="cfdi"
                                        accept=".pdf,.jpg,.jpeg,.png" required>
                                    <label for="cfdi" class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="file-text">Subir archivo</span>
                                        <span class="file-size">PDF, JPG, PNG - Máx. 5MB</span>
                                    </label>
                                </div>
                                @error('cfdi')
                                    <span class="error-message">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fotografia" class="form-label">
                                <i class="fas fa-camera"></i>
                                Fotografía del Rostro
                            </label>
                            <i class="info-icon" data-modal="modalFotografia">
                                <i class="fas fa-info-circle"></i>
                            </i>
                            <div class="photo-upload-container @error('fotografia') error @enderror">
                                <input type="file" class="file-input" id="fotografia" name="fotografia"
                                    accept=".jpg,.jpeg,.png" required>
                                <label for="fotografia" class="photo-upload-label">
                                    <div class="photo-preview">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <span class="photo-text">Subir fotografía</span>
                                    <span class="photo-size">JPG o PNG - Máx. 2MB</span>
                                </label>
                            </div>
                            @error('fotografia')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Términos y Condiciones -->
                    <div class="form-group terms-group" data-scroll data-scroll-delay="200">
                        <div class="terms-container">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required
                                {{ old('terms') ? 'checked' : '' }}>
                            <label class="terms-label" for="terms">
                                Acepto los <a href="#terms" class="terms-link">términos y condiciones</a> y la
                                <a href="#privacy" class="terms-link">política de privacidad</a>
                            </label>
                        </div>
                        @error('terms')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="auth-btn primary">
                        <i class="fas fa-user-plus"></i>
                        <span class="btn-text">Crear Cuenta</span>
                        <div class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>

                    <div class="auth-footer">
                        <p>¿Ya tienes una cuenta?
                            <a href="{{ route('login') }}" class="auth-link">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Panel Derecho - Inspiración -->
        <div class="inspiration-panel">
            <div class="inspiration-content">
                <div class="welcome-message">
                    <div class="welcome-badge">
                        <i class="fas fa-star"></i>
                        Bienvenido al Equipo
                    </div>
                    <h1 class="welcome-title">
                        Tu Talento
                        <span class="highlight">Nos Importa</span>
                    </h1>
                    <p class="welcome-description">
                        Estás a un paso de ser parte de la experiencia deportiva y cultural más grande de la universidad.
                        Juntos crearemos memorias inolvidables.
                    </p>

                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Competencia Sana</h4>
                                <p>Demuestra tus habilidades en un ambiente de respeto y camaradería</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Comunidad Activa</h4>
                                <p>Conoce a compañeros que comparten tus mismas pasiones</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Bienestar Integral</h4>
                                <p>Combina actividad física y expresión cultural para un desarrollo completo</p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-container">
                        <div class="stat">
                            <div class="stat-number">12+</div>
                            <div class="stat-label">Disciplinas</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">5000+</div>
                            <div class="stat-label">Participantes</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Diversión</div>
                        </div>
                    </div>

                    <div class="inspiration-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>"El deporte y la cultura tienen el poder de transformar vidas y unir comunidades"</p>
                        <div class="quote-author">- Comunidad Universitaria</div>
                    </div>
                </div>
            </div>

            <div class="inspiration-background">
                <div class="floating-elements">
                    <div class="floating-element sport">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="floating-element art">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div class="floating-element music">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="floating-element chess">
                        <i class="fas fa-chess-knight"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Términos y Condiciones -->
    <div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-file-contract"></i>
                    Términos y Condiciones
                </h2>
                <button class="modal-close" data-modal="termsModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="terms-content">
                    <div class="terms-section">
                        <h3><i class="fas fa-gavel"></i> Aceptación de Términos</h3>
                        <p>Al registrarse en el Sistema de Inscripción a Actividades Deportivas y Culturales de la
                            Universidad, usted acepta cumplir con los siguientes términos y condiciones establecidos
                            conforme a la legislación mexicana aplicable.</p>
                    </div>

                    <div class="terms-section">
                        <h3><i class="fas fa-user-check"></i> Elegibilidad</h3>
                        <p>Para participar en las actividades, debe ser trabajador activo de la universidad con
                            documentación válida:</p>
                        <ul>
                            <li>Número de trabajador vigente</li>
                            <li>Constancia laboral actualizada</li>
                            <li>CFDI o recibo de nómina reciente</li>
                            <li>Fotografía reciente del rostro</li>
                        </ul>
                    </div>

                    <div class="terms-section">
                        <h3><i class="fas fa-running"></i> Reglas de Participación</h3>
                        <ul>
                            <li>Máximo 2 disciplinas por participante</li>
                            <li>Cupo limitado por actividad</li>
                            <li>Comportamiento deportivo y respetuoso</li>
                            <li>Asistencia obligatoria a las actividades inscritas</li>
                        </ul>
                    </div>

                    <div class="terms-section">
                        <h3><i class="fas fa-ban"></i> Causales de Descalificación</h3>
                        <ul>
                            <li>Documentación falsa o alterada</li>
                            <li>Comportamiento antideportivo</li>
                            <li>Incumplimiento de horarios</li>
                            <li>Falta de respeto a organizadores o participantes</li>
                        </ul>
                    </div>

                    <div class="terms-section">
                        <h3><i class="fas fa-exclamation-triangle"></i> Limitación de Responsabilidad</h3>
                        <p>La universidad no se hace responsable por lesiones o accidentes ocurridos durante la
                            participación en las actividades, excepto aquellos cubiertos por el seguro institucional.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" data-modal="termsModal">Aceptar y Cerrar</button>
            </div>
        </div>
    </div>

    <!-- Modal Política de Privacidad -->
    <div id="privacyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-shield-alt"></i>
                    Política de Privacidad
                </h2>
                <button class="modal-close" data-modal="privacyModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="privacy-content">
                    <div class="privacy-section">
                        <h3><i class="fas fa-database"></i> Recopilación de Datos</h3>
                        <p>De acuerdo con la Ley Federal de Protección de Datos Personales en Posesión de los Particulares
                            (LFPDPPP), informamos que recabamos:</p>
                        <ul>
                            <li><strong>Datos identificativos:</strong> Nombre completo, CURP, fecha de nacimiento</li>
                            <li><strong>Datos laborales:</strong> Número de trabajador, antigüedad, documentos laborales
                            </li>
                            <li><strong>Datos de contacto:</strong> Correo electrónico, número telefónico</li>
                            <li><strong>Datos biométricos:</strong> Fotografía del rostro</li>
                        </ul>
                    </div>

                    <div class="privacy-section">
                        <h3><i class="fas fa-bullseye"></i> Finalidad del Tratamiento</h3>
                        <p>Sus datos personales serán utilizados para:</p>
                        <ul>
                            <li>Gestión de inscripciones a actividades deportivas y culturales</li>
                            <li>Validación de identidad y situación laboral</li>
                            <li>Comunicación sobre el estado de su inscripción</li>
                            <li>Generación de estadísticas institucionales</li>
                        </ul>
                    </div>

                    <div class="privacy-section">
                        <h3><i class="fas fa-lock"></i> Protección de Datos</h3>
                        <p>Implementamos medidas de seguridad administrativas, técnicas y físicas conforme a los estándares
                            de la LFPDPPP:</p>
                        <ul>
                            <li>Cifrado de datos sensibles</li>
                            <li>Acceso restringido al personal autorizado</li>
                            <li>Almacenamiento seguro de documentos</li>
                            <li>Procesamiento conforme al principio de confidencialidad</li>
                        </ul>
                    </div>

                    <div class="privacy-section">
                        <h3><i class="fas fa-share-alt"></i> Transferencia de Datos</h3>
                        <p>Sus datos no serán transferidos a terceros sin su consentimiento, excepto cuando sea necesario
                            para:</p>
                        <ul>
                            <li>Cumplir con obligaciones legales</li>
                            <li>Procesar inscripciones en actividades específicas</li>
                            <li>Generar reportes estadísticos anónimos</li>
                        </ul>
                    </div>

                    <div class="privacy-section">
                        <h3><i class="fas fa-user-cog"></i> Derechos ARCO</h3>
                        <p>Usted tiene derecho a:</p>
                        <ul>
                            <li><strong>Acceder</strong> a sus datos personales</li>
                            <li><strong>Rectificar</strong> información inexacta</li>
                            <li><strong>Cancelar</strong> el uso de sus datos</li>
                            <li><strong>Oponerse</strong> al tratamiento para fines secundarios</li>
                        </ul>
                        <p>Para ejercer estos derechos, contacte al Comité Organizador.</p>
                    </div>

                    <div class="privacy-section">
                        <h3><i class="fas fa-clock"></i> Conservación de Datos</h3>
                        <p>Sus datos serán conservados por un período de 2 años después de finalizado el evento, conforme a
                            los plazos de prescripción legal establecidos en el Código Civil Federal.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" data-modal="privacyModal">Entendido</button>
            </div>
        </div>
    </div>

    <!-- Modal Fotografía -->
    <div id="modalFotografia" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-camera"></i>
                    Requisitos de Fotografía
                </h2>
                <button class="modal-close" data-modal="modalFotografia">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="document-requirements">
                    <div class="requirement-section">
                        <h3><i class="fas fa-check-circle"></i> Características Obligatorias</h3>
                        <ul>
                            <li><strong>Formato:</strong> JPG o PNG</li>
                            <li><strong>Tamaño máximo:</strong> 2MB</li>
                            <li><strong>Fondo:</strong> Blanco liso sin texturas</li>
                            <li><strong>Iluminación:</strong> Rostro bien iluminado sin sombras</li>
                            <li><strong>Enfoque:</strong> Rostro nítido y claro</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-user"></i> Composición</h3>
                        <ul>
                            <li>Rostro completo frente a la cámara</li>
                            <li>Expresión neutral, ojos abiertos</li>
                            <li>Sin lentes de sol o gorras</li>
                            <li>Sin otras personas en la foto</li>
                            <li>Ropa formal o semiformal</li>
                        </ul>
                    </div>

                    <div class="example-images">
                        <h3><i class="fas fa-images"></i> Ejemplos Correctos</h3>
                        <div class="examples-grid">
                            <div class="example correct">
                                <div class="example-image photo-example"></div>
                                <p>Fondo blanco • Buena iluminación</p>
                            </div>
                            <div class="example correct">
                                <div class="example-image photo-example"></div>
                                <p>Rostro centrado • Enfoque claro</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" data-modal="modalFotografia">Entendido</button>
            </div>
        </div>
    </div>

    <!-- Modal Constancia Laboral -->
    <div id="modalConstancia" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-file-pdf"></i>
                    Requisitos Constancia Laboral
                </h2>
                <button class="modal-close" data-modal="modalConstancia">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="document-requirements">
                    <div class="requirement-section">
                        <h3><i class="fas fa-check-circle"></i> Características del Documento</h3>
                        <ul>
                            <li><strong>Formato:</strong> PDF, JPG o PNG</li>
                            <li><strong>Tamaño máximo:</strong> 5MB</li>
                            <li><strong>Vigencia:</strong> Máximo 3 meses de antigüedad</li>
                            <li><strong>Contenido:</strong> Debe incluir fecha de expedición</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-file-signature"></i> Información Requerida</h3>
                        <ul>
                            <li>Nombre completo del trabajador</li>
                            <li>Número de trabajador</li>
                            <li>Puesto o cargo actual</li>
                            <li>Fecha de ingreso a la institución</li>
                            <li>Vigencia laboral actual</li>
                            <li>Sello oficial de la institución</li>
                            <li>Firma del responsable</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-exclamation-triangle"></i> Documentos No Aceptados</h3>
                        <ul>
                            <li>Constancias con más de 3 meses de antigüedad</li>
                            <li>Documentos sin sello oficial</li>
                            <li>Imágenes borrosas o ilegibles</li>
                            <li>Documentos alterados o editados</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" data-modal="modalConstancia">Entendido</button>
            </div>
        </div>
    </div>

    <!-- Modal Recibo de Nómina -->
    <div id="modalRecibo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">
                    <i class="fas fa-file-invoice"></i>
                    Requisitos CFDI/Recibo de Nómina
                </h2>
                <button class="modal-close" data-modal="modalRecibo">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="document-requirements">
                    <div class="requirement-section">
                        <h3><i class="fas fa-check-circle"></i> Características del Documento</h3>
                        <ul>
                            <li><strong>Formato:</strong> PDF, JPG o PNG</li>
                            <li><strong>Tamaño máximo:</strong> 5MB</li>
                            <li><strong>Vigencia:</strong> Del período actual o anterior</li>
                            <li><strong>Claridad:</strong> Texto legible y completo</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-receipt"></i> Información Requerida (CFDI)</h3>
                        <ul>
                            <li>RFC de la institución y del trabajador</li>
                            <li>Fecha de emisión</li>
                            <li>Folio fiscal (UUID)</li>
                            <li>Período de pago</li>
                            <li>Salario neto y bruto</li>
                            <li>Sello digital del SAT</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-file-alt"></i> Información Requerida (Recibo)</h3>
                        <ul>
                            <li>Nombre completo del trabajador</li>
                            <li>Número de trabajador</li>
                            <li>Período de pago</li>
                            <li>Salario neto devengado</li>
                            <li>Deducciones y percepciones</li>
                            <li>Sello de la institución</li>
                        </ul>
                    </div>

                    <div class="requirement-section">
                        <h3><i class="fas fa-ban"></i> Documentos No Aceptados</h3>
                        <ul>
                            <li>Recibos con más de 2 períodos de antigüedad</li>
                            <li>Documentos incompletos o cortados</li>
                            <li>Imágenes con reflejos o sombras</li>
                            <li>Archivos protegidos con contraseña</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="modal-btn primary" data-modal="modalRecibo">Entendido</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .error-message {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
        }

        .error-message i {
            margin-right: 0.5rem;
        }

        .input-container.error .form-control {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        .file-upload-container.error .file-upload-label {
            border-color: #e53e3e;
            background: rgba(229, 62, 62, 0.05);
        }

        .photo-upload-container.error .photo-upload-label {
            border-color: #e53e3e;
            background: rgba(229, 62, 62, 0.05);
        }

        /* === LAYOUT PRINCIPAL === */
        .register-container {
            display: flex;
            min-height: 200vh;
            background: white;
        }

        .form-panel {
            flex: 1;
            background: white;
            overflow-y: auto;
            padding: 2rem;
        }

        .inspiration-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* === ESTILOS PARA MODALES DE DOCUMENTOS === */
        .document-requirements {
            line-height: 1.6;
        }

        .requirement-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 79, 110, 0.1);
        }

        .requirement-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .requirement-section h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .requirement-section h3 i {
            color: var(--secondary-color);
            width: 20px;
        }

        .requirement-section ul {
            color: var(--text-primary);
            padding-left: 1.5rem;
            margin: 0;
        }

        .requirement-section li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .requirement-section strong {
            color: var(--primary-color);
        }

        /* Ejemplos de imágenes */
        .example-images {
            margin-top: 2rem;
        }

        .examples-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .example {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            background: rgba(0, 79, 110, 0.05);
        }

        .example.correct {
            border: 2px solid var(--success);
        }

        .example.incorrect {
            border: 2px solid var(--danger);
        }

        .example-image {
            width: 100px;
            height: 120px;
            margin: 0 auto 0.5rem;
            border-radius: 8px;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .photo-example::before {
            content: "📷";
            font-size: 2rem;
        }

        .example p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 500;
        }

        /* === PANEL DE FORMULARIO === */
        .form-content {
            max-width: 600px;
            margin: 0 auto;
            padding: 1rem;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* === PANEL DE INSPIRACIÓN === */
        .inspiration-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 3rem 2rem;
            max-width: 500px;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #FFFFFF 0%, #E0F7FA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .highlight {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 3rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .benefits-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            text-align: left;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .benefit-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .benefit-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .benefit-content h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: white;
        }

        .benefit-content p {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            line-height: 1.5;
        }

        .stats-container {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat {
            text-align: center;
        }

        /* === ICONO DE INFORMACIÓN === */
        .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            font-size: 0.7rem;
            cursor: pointer;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
            margin: 0.5rem 0
        }

        .info-icon:hover {
            background: var(--primary-color);
            transform: scale(1.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inspiration-quote {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .inspiration-quote i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            display: block;
        }

        .inspiration-quote p {
            font-size: 1.1rem;
            font-style: italic;
            margin-bottom: 1rem;
            color: white;
            line-height: 1.6;
        }

        .quote-author {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        /* === FONDO ANIMADO === */
        .inspiration-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .floating-element {
            position: absolute;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: float 8s ease-in-out infinite;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-element.sport {
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }

        .floating-element.art {
            top: 60%;
            left: 70%;
            animation-delay: 2s;
        }

        .floating-element.music {
            top: 30%;
            left: 80%;
            animation-delay: 4s;
        }

        .floating-element.chess {
            top: 70%;
            left: 30%;
            animation-delay: 6s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* === ESTILOS BASE DEL FORMULARIO (igual que antes) === */
        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .auth-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .auth-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--secondary-color);
            width: 16px;
        }

        .input-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--text-secondary);
            z-index: 2;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
            background: rgba(0, 79, 110, 0.1);
        }

        .auth-btn {
            position: relative;
            width: 100%;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }

        .auth-btn.primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            color: white;
            box-shadow: var(--shadow-2);
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-3);
        }

        .auth-btn .btn-loading {
            display: none;
        }

        .auth-btn.loading .btn-text {
            opacity: 0;
        }

        .auth-btn.loading .btn-loading {
            display: block;
            position: absolute;
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
        }

        .auth-footer p {
            color: var(--text-secondary);
            margin: 0;
        }

        .auth-link {
            color: var(--secondary-color);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            background: rgba(0, 79, 110, 0.02);
            border-radius: 15px;
            border: 1px solid rgba(0, 79, 110, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid rgba(0, 170, 139, 0.2);
        }

        .section-title i {
            color: var(--secondary-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        /* File Upload Styles */
        .file-upload-group {
            margin-bottom: 1rem;
        }

        .file-upload-container {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-upload-label:hover {
            border-color: var(--secondary-color);
            background: rgba(0, 170, 139, 0.05);
            transform: translateY(-2px);
        }

        .file-upload-label i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .file-text {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .file-size {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Photo Upload */
        .photo-upload-container {
            position: relative;
        }

        .photo-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            max-width: 200px;
            margin: 0 auto;
        }

        .photo-upload-label:hover {
            border-color: var(--secondary-color);
            background: rgba(0, 170, 139, 0.05);
        }

        .photo-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .photo-text {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .photo-size {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #e53e3e;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Terms and Conditions */
        .terms-group {
            margin: 2rem 0;
        }

        .terms-container {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 0.25rem;
        }

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .terms-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
            cursor: pointer;
        }

        .terms-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            text-decoration: underline;
        }

        /* Scroll Animations */
        [data-scroll] {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        [data-scroll].scroll-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }

            .inspiration-panel {
                display: none;
            }

            .form-panel {
                padding: 1rem;
            }

            .form-content {
                padding: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-section {
                padding: 1rem;
                margin-bottom: 2rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .file-upload-label,
            .photo-upload-label {
                padding: 1.5rem;
            }

            .auth-title {
                font-size: 1.75rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .register-container {
                flex-direction: row;
            }

            .form-content {
                max-width: 100%;
                padding: 0 1rem;
            }
        }

        /* === MODALES === */
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
            margin: 2% auto;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            max-height: 85vh;
            overflow: hidden;
            box-shadow: var(--shadow-3);
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            border-bottom: 2px solid rgba(0, 79, 110, 0.1);
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .modal-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .modal-title i {
            color: var(--secondary-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            color: var(--primary-color);
            background: rgba(0, 79, 110, 0.1);
        }

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 2px solid rgba(0, 79, 110, 0.1);
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
        }

        /* Contenido de Términos y Privacidad */
        .terms-content,
        .privacy-content {
            line-height: 1.6;
        }

        .terms-section,
        .privacy-section {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(0, 79, 110, 0.1);
        }

        .terms-section:last-child,
        .privacy-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .terms-section h3,
        .privacy-section h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .terms-section h3 i,
        .privacy-section h3 i {
            color: var(--secondary-color);
            width: 20px;
        }

        .terms-section p,
        .privacy-section p {
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .terms-section ul,
        .privacy-section ul {
            color: var(--text-primary);
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .terms-section li,
        .privacy-section li {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .terms-section strong,
        .privacy-section strong {
            color: var(--primary-color);
        }

        /* Botones de Modal */
        .modal-btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-btn.primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            color: white;
            box-shadow: var(--shadow-1);
        }

        .modal-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-2);
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

        /* Scrollbar personalizado para modales */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                margin: 5% auto;
                width: 95%;
                max-height: 90vh;
            }

            .modal-header {
                padding: 1rem 1.5rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                padding: 1rem 1.5rem;
            }

            .modal-title {
                font-size: 1.25rem;
            }

            .terms-section h3,
            .privacy-section h3 {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    const input = document.getElementById(target);
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            });

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthFill = document.querySelector('.strength-fill');
            const strengthText = document.querySelector('.strength-text');

            if (passwordInput && strengthFill) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let color = '#e53e3e';
                    let text = 'Débil';

                    if (password.length >= 8) strength += 25;
                    if (/[A-Z]/.test(password)) strength += 25;
                    if (/[0-9]/.test(password)) strength += 25;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 25;

                    if (strength >= 75) {
                        color = '#38a169';
                        text = 'Fuerte';
                    } else if (strength >= 50) {
                        color = '#d69e2e';
                        text = 'Media';
                    }

                    strengthFill.style.width = strength + '%';
                    strengthFill.style.background = color;
                    strengthText.textContent = text + ' - ' + getStrengthTips(password);
                });
            }

            function getStrengthTips(password) {
                const tips = [];
                if (password.length < 8) tips.push('mínimo 8 caracteres');
                if (!/[A-Z]/.test(password)) tips.push('una mayúscula');
                if (!/[0-9]/.test(password)) tips.push('un número');
                if (!/[^A-Za-z0-9]/.test(password)) tips.push('un carácter especial');

                return tips.length > 0 ? 'Falta: ' + tips.join(', ') : 'Contraseña segura';
            }

            // File upload preview
            document.querySelectorAll('.file-input').forEach(input => {
                input.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    const fileText = label.querySelector('.file-text');

                    if (this.files.length > 0) {
                        const file = this.files[0];
                        fileText.textContent = file.name;
                        label.style.borderColor = 'var(--secondary-color)';
                        label.style.background = 'rgba(0, 170, 139, 0.1)';
                    }
                });
            });

            // Photo upload preview
            const photoInput = document.getElementById('fotografia');
            if (photoInput) {
                photoInput.addEventListener('change', function() {
                    const label = this.nextElementSibling;
                    const photoPreview = label.querySelector('.photo-preview');

                    if (this.files.length > 0) {
                        const file = this.files[0];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            photoPreview.innerHTML =
                                `<img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                        };

                        reader.readAsDataURL(file);
                        label.querySelector('.photo-text').textContent = 'Foto seleccionada';
                        label.style.borderColor = 'var(--secondary-color)';
                    }
                });
            }

            // Form submission
            const authForm = document.querySelector('.auth-form');
            const submitBtn = authForm.querySelector('.auth-btn');

            authForm.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
            });

            // Scroll animations
            const scrollElements = document.querySelectorAll('[data-scroll]');

            function checkScroll() {
                scrollElements.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    if (elementTop < window.innerHeight * 0.85) {
                        const delay = element.getAttribute('data-scroll-delay') || 0;
                        setTimeout(() => {
                            element.classList.add('scroll-visible');
                        }, delay);
                    }
                });
            }

            checkScroll();
            window.addEventListener('scroll', checkScroll);
        });

        // Funcionalidad de Modales
        function initModals() {
            // Abrir modales desde los enlaces de términos
            document.querySelectorAll('.terms-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modalType = this.getAttribute('href') === '#terms' ? 'termsModal' :
                        'privacyModal';
                    openModal(modalType);
                });
            });

            // Cerrar modales
            document.querySelectorAll('.modal-close, .modal-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal');
                    closeModal(modalId);
                });
            });

            // Cerrar modal haciendo click fuera del contenido
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            // Cerrar con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal').forEach(modal => {
                        if (modal.style.display === 'block') {
                            closeModal(modal.id);
                        }
                    });
                }
            });
        }

        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevenir scroll del body
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restaurar scroll
            }
        }

        function initDocumentModals() {
            // Abrir modales desde los iconos de información
            document.querySelectorAll('.info-icon').forEach(icon => {
                icon.addEventListener('click', function() {
                    const modalType = this.getAttribute('data-modal');
                    openModal(modalType);
                });
            });

            // Cerrar modales (complementa la función existente)
            document.querySelectorAll('.modal-close, .modal-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modalId = this.getAttribute('data-modal');
                    closeModal(modalId);
                });
            });

            // Cerrar modal haciendo click fuera del contenido
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this.id);
                    }
                });
            });

            // Cerrar con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal').forEach(modal => {
                        if (modal.style.display === 'block') {
                            closeModal(modal.id);
                        }
                    });
                }
            });
        }

        // Función para abrir modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        }

        // Función para cerrar modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        }

        // Inicializar modales cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            initModals();
            initDocumentModals();
        });

        // Validación de email en tiempo real
        const emailInput = document.getElementById('email');
        const emailFeedback = document.querySelector('.email-feedback');

        if (emailInput && emailFeedback) {
            let emailTimeout;

            emailInput.addEventListener('input', function() {
                clearTimeout(emailTimeout);
                const email = this.value;

                if (email.length > 3 && email.includes('@')) {
                    emailTimeout = setTimeout(() => {
                        fetch('/check-email', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    email: email
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.available) {
                                    emailFeedback.innerHTML =
                                        '<span style="color: var(--success);"><i class="fas fa-check-circle"></i> ' +
                                        data.message + '</span>';
                                    emailInput.style.borderColor = 'var(--success)';
                                } else {
                                    emailFeedback.innerHTML =
                                        '<span style="color: var(--danger);"><i class="fas fa-exclamation-circle"></i> ' +
                                        data.message + '</span>';
                                    emailInput.style.borderColor = 'var(--danger)';
                                }
                            });
                    }, 500);
                }
            });
        }

        // Validación de fecha de nacimiento
        const fechaNacimientoInput = document.getElementById('fecha_nacimiento');
        if (fechaNacimientoInput) {
            fechaNacimientoInput.addEventListener('change', function() {
                const fechaNacimiento = new Date(this.value);
                const hoy = new Date();
                const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                const mes = hoy.getMonth() - fechaNacimiento.getMonth();

                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }

                if (edad < 18) {
                    alert('Debes ser mayor de 18 años para registrarte.');
                    this.value = '';
                } else if (edad > 70) {
                    alert('La fecha de nacimiento no es válida.');
                    this.value = '';
                }
            });
        }

        // Validación de CURP
        const curpInput = document.getElementById('curp');
        if (curpInput) {
            curpInput.addEventListener('input', function() {
                const curp = this.value.toUpperCase();
                const curpRegex = /^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/;

                if (curp.length === 18 && !curpRegex.test(curp)) {
                    this.style.borderColor = 'var(--danger)';
                } else {
                    this.style.borderColor = '';
                }
            });
        }

        // Validación de teléfono
        const telefonoInput = document.getElementById('telefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function() {
                const telefono = this.value;
                const telefonoRegex = /^(\+52|52)?\s?(\d{2,3}|\(\d{2,3}\))[\s\-]?\d{3,4}[\s\-]?\d{4}$/;

                if (telefono.length > 5 && !telefonoRegex.test(telefono)) {
                    this.style.borderColor = 'var(--danger)';
                } else {
                    this.style.borderColor = '';
                }
            });
        }

        // Validación de formulario antes de enviar
        const authForm = document.querySelector('.auth-form');
        const submitBtn = authForm.querySelector('.auth-btn');

        authForm.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let allFilled = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    allFilled = false;
                    field.style.borderColor = 'var(--danger)';
                }
            });

            if (!allFilled) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
                return;
            }

            // Verificar términos y condiciones
            const termsCheckbox = document.getElementById('terms');
            if (!termsCheckbox.checked) {
                e.preventDefault();
                alert('Debes aceptar los términos y condiciones.');
                return;
            }

            submitBtn.classList.add('loading');
        });

        // Resaltar campos con error después de la validación del servidor
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si hay errores en los campos de archivo
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                const errorElement = input.closest('.file-upload-group, .form-group').querySelector(
                    '.error-message');
                if (errorElement) {
                    const container = input.closest('.file-upload-container, .photo-upload-container');
                    container.classList.add('error');
                }
            });

            // Verificar errores en campos de texto
            const textInputs = document.querySelectorAll(
                'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], input[type="date"], input[type="password"]'
            );
            textInputs.forEach(input => {
                const errorElement = input.closest('.form-group').querySelector('.error-message');
                if (errorElement) {
                    input.style.borderColor = '#e53e3e';
                }
            });
        });
    </script>
@endpush
