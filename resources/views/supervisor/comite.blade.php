@extends('supervisor.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-user-plus"></i> Gestión de Usuarios</h1>
                <p>Agregar nuevos miembros al comité y crear supervisores</p>
            </div>
            <div class="header-actions">
                <button class="btn-secondary" onclick="showUserList()">
                    <i class="fas fa-list"></i>
                    Ver Usuarios
                </button>
            </div>
        </div>

        <!-- Alertas -->
        <div class="alerts-container" id="alertsContainer"></div>

        <!-- Panel de Creación -->
        <div class="creation-panel">
            <div class="panel-tabs">
                <button class="tab-button active" onclick="switchTab('comite')">
                    <i class="fas fa-users"></i>
                    Nuevo Miembro del Comité
                </button>
                <button class="tab-button" onclick="switchTab('supervisor')">
                    <i class="fas fa-user-shield"></i>
                    Nuevo Supervisor
                </button>
            </div>

            <!-- Formulario para Comité -->
            <div class="tab-content active" id="comiteTab">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-users"></i> Agregar Miembro del Comité</h3>
                        <div class="card-badge">Usuario Operativo</div>
                    </div>
                    <div class="card-body">
                        <form id="comiteForm" class="user-form">
                            @csrf
                            <input type="hidden" name="tipo_usuario" value="comite">

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">
                                        <i class="fas fa-envelope"></i>
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" name="email" required
                                        placeholder="ejemplo@universidad.edu" id="comiteEmail">
                                    <div class="form-hint">Se enviarán las credenciales a este correo</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">
                                        <i class="fas fa-lock"></i>
                                        Contraseña
                                    </label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" name="password" required
                                            placeholder="Mínimo 8 caracteres" id="comitePassword" minlength="8">
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('comitePassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="comitePasswordStrength">
                                        <div class="strength-bar"></div>
                                        <div class="strength-text">Seguridad de la contraseña</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-secondary" onclick="resetForm('comiteForm')">
                                    <i class="fas fa-undo"></i>
                                    Limpiar
                                </button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-user-plus"></i>
                                    Crear Miembro del Comité
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Formulario para Supervisor -->
            <div class="tab-content" id="supervisorTab">
                <div class="form-card">
                    <div class="card-header">
                        <h3><i class="fas fa-user-shield"></i> Crear Nuevo Supervisor</h3>
                        <div class="card-badge warning">Acceso Total</div>
                    </div>
                    <div class="card-body">
                        <form id="supervisorForm" class="user-form">
                            @csrf
                            <input type="hidden" name="tipo_usuario" value="supervisor">

                            <div class="security-notice">
                                <i class="fas fa-shield-alt"></i>
                                <div class="notice-content">
                                    <strong>Acceso de Supervisor</strong>
                                    <p>Los supervisores tienen acceso completo a todos los reportes, datos consolidados y
                                        funciones administrativas.</p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label required">
                                        <i class="fas fa-envelope"></i>
                                        Correo Electrónico
                                    </label>
                                    <input type="email" class="form-control" name="email" required
                                        placeholder="supervisor@universidad.edu" id="supervisorEmail">
                                    <div class="form-hint">Debe ser un correo institucional</div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">
                                        <i class="fas fa-lock"></i>
                                        Contraseña
                                    </label>
                                    <div class="password-input-group">
                                        <input type="password" class="form-control" name="password" required
                                            placeholder="Mínimo 8 caracteres" id="supervisorPassword" minlength="8">
                                        <button type="button" class="password-toggle"
                                            onclick="togglePassword('supervisorPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="supervisorPasswordStrength">
                                        <div class="strength-bar"></div>
                                        <div class="strength-text">Seguridad de la contraseña</div>
                                    </div>
                                </div>
                            </div>

                            <div class="supervisor-permissions">
                                <h4><i class="fas fa-user-shield"></i> Permisos de Supervisor</h4>
                                <div class="permissions-notice">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Los supervisores tienen acceso completo al sistema por defecto</span>
                                </div>
                                <div class="permissions-list">
                                    <div class="permission-item full-access">
                                        <i class="fas fa-chart-line"></i>
                                        <div class="permission-content">
                                            <strong>Reportes Ejecutivos</strong>
                                            <span>Acceso a todos los reportes consolidados</span>
                                        </div>
                                    </div>
                                    <div class="permission-item full-access">
                                        <i class="fas fa-database"></i>
                                        <div class="permission-content">
                                            <strong>Datos Completos</strong>
                                            <span>Visualización de toda la información del sistema</span>
                                        </div>
                                    </div>
                                    <div class="permission-item full-access">
                                        <i class="fas fa-cog"></i>
                                        <div class="permission-content">
                                            <strong>Configuración del Sistema</strong>
                                            <span>Acceso a configuraciones globales</span>
                                        </div>
                                    </div>
                                    <div class="permission-item full-access">
                                        <i class="fas fa-user-plus"></i>
                                        <div class="permission-content">
                                            <strong>Gestión de Usuarios</strong>
                                            <span>Crear nuevos miembros del comité y supervisores</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-secondary" onclick="resetForm('supervisorForm')">
                                    <i class="fas fa-undo"></i>
                                    Limpiar
                                </button>
                                <button type="submit" class="btn-warning">
                                    <i class="fas fa-user-shield"></i>
                                    Crear Supervisor
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios (Opcional) -->
        <div class="users-list-section" id="usersListSection" style="display: none;">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Usuarios del Sistema</h2>
                <button class="btn-outline" onclick="hideUserList()">
                    <i class="fas fa-times"></i>
                    Cerrar
                </button>
            </div>
            <div class="users-grid">
                <!-- Aquí se cargaría dinámicamente la lista de usuarios -->
                <div class="no-users">
                    <i class="fas fa-users"></i>
                    <h3>Lista de Usuarios</h3>
                    <p>La funcionalidad de lista de usuarios estará disponible próximamente</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === ESTILOS GENERALES === */
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

        /* Alertas */
        .alerts-container {
            margin-bottom: 2rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: slideDown 0.3s ease;
        }

        .alert.success {
            background: rgba(56, 161, 105, 0.1);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert.error {
            background: rgba(229, 62, 62, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
        }

        .alert.warning {
            background: rgba(214, 158, 46, 0.1);
            border: 1px solid var(--warning);
            color: var(--warning);
        }

        /* Panel de Pestañas */
        .creation-panel {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .panel-tabs {
            display: flex;
            background: var(--bg-light);
            border-bottom: 1px solid var(--border-color);
        }

        .tab-button {
            flex: 1;
            padding: 1.5rem 2rem;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-button:hover {
            background: rgba(0, 79, 110, 0.05);
            color: var(--primary-color);
        }

        .tab-button.active {
            background: white;
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }

        .tab-content {
            display: none;
            padding: 0;
        }

        .tab-content.active {
            display: block;
        }

        /* Tarjetas de Formulario */
        .form-card {
            background: white;
        }

        .form-card .card-header {
            padding: 2rem 2rem 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0;
            color: var(--text-primary);
            font-size: 1.25rem;
        }

        .card-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            background: var(--primary-color);
            color: white;
        }

        .card-badge.warning {
            background: var(--warning);
        }

        .card-body {
            padding: 2rem;
        }

        /* Formularios */
        .user-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-label.required::after {
            content: '*';
            color: var(--danger);
            margin-left: 0.25rem;
        }

        .form-label i {
            color: var(--secondary-color);
            width: 16px;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.1);
        }

        .form-hint {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Input de Contraseña */
        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-bar::before {
            content: '';
            display: block;
            height: 100%;
            width: 0%;
            background: var(--danger);
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        /* Sección de Permisos */
        .permissions-section,
        .supervisor-permissions {
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .permissions-section h4,
        .supervisor-permissions h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .permission-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
        }

        .permission-item:hover {
            border-color: var(--secondary-color);
            transform: translateY(-1px);
        }

        .permission-item.full-access {
            background: rgba(0, 170, 139, 0.05);
            border-color: var(--secondary-color);
        }

        .permission-label {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            cursor: pointer;
            flex: 1;
        }

        .permission-label i {
            color: var(--secondary-color);
            font-size: 1.25rem;
            margin-top: 0.25rem;
            flex-shrink: 0;
        }

        .permission-content {
            flex: 1;
        }

        .permission-content strong {
            display: block;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .permission-content span {
            display: block;
            color: var(--text-secondary);
            font-size: 0.8rem;
            line-height: 1.3;
        }

        .permissions-notice {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: rgba(0, 123, 255, 0.05);
            border-radius: 8px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-color);
        }

        .permissions-notice i {
            color: var(--accent-color);
            font-size: 1.25rem;
        }

        .permissions-notice span {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .permissions-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* Nota de Seguridad */
        .security-notice {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            background: rgba(214, 158, 46, 0.05);
            border-radius: 12px;
            border: 1px solid var(--warning);
            margin-bottom: 1.5rem;
        }

        .security-notice i {
            color: var(--warning);
            font-size: 1.5rem;
            margin-top: 0.25rem;
        }

        .notice-content strong {
            display: block;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .notice-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Acciones del Formulario */
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        /* Lista de Usuarios */
        .users-list-section {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin: 0;
        }

        .no-users {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .no-users i {
            font-size: 4rem;
            color: var(--border-color);
            margin-bottom: 1rem;
        }

        .no-users h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
        }

        /* Botones */
        .btn-primary,
        .btn-secondary,
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

        .btn-warning {
            background: var(--warning);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-primary:hover,
        .btn-warning:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: white;
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
            }

            .panel-tabs {
                flex-direction: column;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .form-card .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .card-body {
                padding: 1.5rem;
            }

            .permissions-section,
            .supervisor-permissions {
                padding: 1rem;
            }

            .security-notice {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Animaciones */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para las tarjetas de usuarios */
        .users-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .user-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .user-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .user-details h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .user-meta small {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cambio de pestañas
            window.switchTab = function(tab) {
                // Ocultar todas las pestañas
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('active');
                });

                // Mostrar pestaña seleccionada
                document.getElementById(tab + 'Tab').classList.add('active');
                event.target.classList.add('active');
            };

            // Alternar visibilidad de contraseña
            window.togglePassword = function(inputId) {
                const input = document.getElementById(inputId);
                const icon = event.target.querySelector('i') || event.target;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'fas fa-eye';
                }
            };

            // Validación de fortaleza de contraseña
            document.querySelectorAll('input[type="password"]').forEach(input => {
                input.addEventListener('input', function() {
                    const password = this.value;
                    const strengthBar = document.getElementById(this.id + 'Strength').querySelector(
                        '.strength-bar');
                    const strengthText = document.getElementById(this.id + 'Strength')
                        .querySelector('.strength-text');

                    let strength = 0;
                    let text = 'Muy débil';
                    let color = '#E53E3E';

                    if (password.length >= 8) strength += 25;
                    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
                    if (password.match(/\d/)) strength += 25;
                    if (password.match(/[^a-zA-Z\d]/)) strength += 25;

                    if (strength >= 75) {
                        text = 'Muy fuerte';
                        color = '#38A169';
                    } else if (strength >= 50) {
                        text = 'Fuerte';
                        color = '#00AA8B';
                    } else if (strength >= 25) {
                        text = 'Moderada';
                        color = '#D69E2E';
                    }

                    strengthBar.style.setProperty('--strength-width', strength + '%');
                    strengthBar.querySelector('::before').style.width = strength + '%';
                    strengthBar.querySelector('::before').style.background = color;
                    strengthText.textContent = text;
                    strengthText.style.color = color;
                });
            });

            // Envío de formularios
            document.getElementById('comiteForm').addEventListener('submit', function(e) {
                e.preventDefault();
                createUser('comite');
            });

            document.getElementById('supervisorForm').addEventListener('submit', function(e) {
                e.preventDefault();
                createUser('supervisor');
            });

            // Función para crear usuario
            window.createUser = function(tipo) {
                const form = document.getElementById(tipo + 'Form');
                const formData = new FormData(form);

                // Mostrar loading
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando...';
                submitBtn.disabled = true;

                // Envío real con AJAX
                fetch('{{ route('supervisor.crear.usuario') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert(data.message, 'success');
                            resetForm(tipo + 'Form');
                        } else {
                            showAlert(data.message || 'Error al crear el usuario', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Error de conexión', 'error');
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            };

            // Función para cargar lista de usuarios (opcional)
            window.cargarListaUsuarios = function() {
                fetch('{{ route('supervisor.obtener.usuarios') }}', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mostrarUsuariosEnLista(data.usuarios);
                        } else {
                            console.error('Error al cargar usuarios');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            };

            // Función para mostrar usuarios en la lista
            function mostrarUsuariosEnLista(usuarios) {
                const usersGrid = document.querySelector('.users-grid');

                if (usuarios.length === 0) {
                    usersGrid.innerHTML = `
            <div class="no-users">
                <i class="fas fa-users"></i>
                <h3>No hay usuarios registrados</h3>
                <p>Los usuarios que crees aparecerán aquí</p>
            </div>
        `;
                    return;
                }

                let html = '<div class="users-cards">';

                usuarios.forEach(usuario => {
                    const tipoTexto = usuario.tipo === 'comite' ? 'Miembro del Comité' : 'Supervisor';
                    const icono = usuario.tipo === 'comite' ? 'fa-users' : 'fa-user-shield';
                    const badgeClass = usuario.tipo === 'comite' ? 'card-badge' : 'card-badge warning';

                    html += `
            <div class="user-card">
                <div class="user-info">
                    <div class="user-icon">
                        <i class="fas ${icono}"></i>
                    </div>
                    <div class="user-details">
                        <h4>${usuario.email}</h4>
                        <span class="user-type ${badgeClass}">${tipoTexto}</span>
                    </div>
                </div>
                <div class="user-meta">
                    <small>Creado: ${new Date(usuario.fecha_creacion).toLocaleDateString()}</small>
                </div>
            </div>
        `;
                });

                html += '</div>';
                usersGrid.innerHTML = html;
            }

            // Actualizar la función showUserList para cargar los usuarios
            window.showUserList = function() {
                document.getElementById('usersListSection').style.display = 'block';
                cargarListaUsuarios();
            };
            // Resetear formulario
            window.resetForm = function(formId) {
                document.getElementById(formId).reset();
                showAlert('Formulario limpiado', 'info');
            };

            // Mostrar/ocultar lista de usuarios
            window.showUserList = function() {
                document.getElementById('usersListSection').style.display = 'block';
            };

            window.hideUserList = function() {
                document.getElementById('usersListSection').style.display = 'none';
            };

            // Mostrar alertas
            function showAlert(message, type) {
                const alert = document.createElement('div');
                alert.className = `alert ${type}`;
                alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle"></i>
                <span>${message}</span>
            `;

                document.getElementById('alertsContainer').appendChild(alert);

                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    alert.style.animation = 'slideUp 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            }

            // Añadir animación de salida
            const style = document.createElement('style');
            style.textContent = `
            @keyframes slideUp {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-10px);
                }
            }
        `;
            document.head.appendChild(style);
        });
    </script>
@endsection
