@extends('participante.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Encabezado -->
        <div class="account-header">
            <div class="header-content">
                <h1><i class="fas fa-user-cog"></i> Configuración de Cuenta</h1>
                <p>Gestiona tu información personal y preferencias de seguridad</p>
            </div>
            <div class="header-illustration">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="account-content">
            <!-- Formulario de Actualización -->
            <div class="form-section">
                <!-- Formulario de Correo -->
                <div class="form-card">
                    <div class="card-header">
                        <h2><i class="fas fa-envelope"></i> Actualizar Correo Electrónico</h2>
                    </div>
                    <div class="card-body">
                        <form id="emailForm" class="account-form" action="{{ route('personal.cuenta.actualizar-email') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="currentEmail" class="form-label">Correo Electrónico Actual</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="currentEmail" class="form-input" value="{{ $usuario->email }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newEmail" class="form-label">Nuevo Correo Electrónico</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="newEmail" name="email" class="form-input"
                                        placeholder="Ingresa tu nuevo correo electrónico" required>
                                </div>
                                <small class="form-hint">Te enviaremos un código de verificación a este correo</small>
                            </div>

                            <div class="form-group">
                                <label for="confirmEmail" class="form-label">Confirmar Nuevo Correo</label>
                                <div class="input-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" id="confirmEmail" name="email_confirmation" class="form-input"
                                        placeholder="Confirma tu nuevo correo electrónico" required>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i>
                                    Actualizar Correo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Formulario de Contraseña -->
                <div class="form-card">
                    <div class="card-header">
                        <h2><i class="fas fa-lock"></i> Cambiar Contraseña</h2>
                    </div>
                    <div class="card-body">
                        <form id="passwordForm" class="account-form"
                            action="{{ route('personal.cuenta.actualizar-password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="currentPassword" class="form-label">Contraseña Actual</label>
                                <div class="input-group">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="currentPassword" name="current_password" class="form-input"
                                        placeholder="Ingresa tu contraseña actual" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="newPassword" class="form-label">Nueva Contraseña</label>
                                <div class="input-group">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="newPassword" name="password" class="form-input"
                                        placeholder="Crea una nueva contraseña" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" data-strength="0"></div>
                                    </div>
                                    <span class="strength-text">Seguridad de la contraseña</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirmPassword" class="form-label">Confirmar Nueva Contraseña</label>
                                <div class="input-group">
                                    <i class="fas fa-lock input-icon"></i>
                                    <input type="password" id="confirmPassword" name="password_confirmation"
                                        class="form-input" placeholder="Confirma tu nueva contraseña" required>
                                    <button type="button" class="password-toggle">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="password-requirements">
                                <h4>Requisitos de la contraseña:</h4>
                                <ul>
                                    <li class="requirement" data-requirement="length">
                                        <i class="fas fa-circle"></i>
                                        Mínimo 8 caracteres
                                    </li>
                                    <li class="requirement" data-requirement="uppercase">
                                        <i class="fas fa-circle"></i>
                                        Al menos una mayúscula
                                    </li>
                                    <li class="requirement" data-requirement="number">
                                        <i class="fas fa-circle"></i>
                                        Al menos un número
                                    </li>
                                    <li class="requirement" data-requirement="special">
                                        <i class="fas fa-circle"></i>
                                        Al menos un carácter especial
                                    </li>
                                </ul>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-key"></i>
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información de Seguridad -->
            <div class="security-section">
                <div class="security-card">
                    <div class="card-header">
                        <h2><i class="fas fa-shield-check"></i> Seguridad de la Cuenta</h2>
                    </div>
                    <div class="card-body">
                        <div class="security-item">
                            <div class="security-icon success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="security-content">
                                <h4>Correo Verificado</h4>
                                <p>Tu dirección de correo electrónico está verificada</p>
                            </div>
                        </div>

                        <div class="security-item">
                            <div class="security-icon warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="security-content">
                                <h4>Último Cambio</h4>
                                <p>Contraseña actualizada hace
                                    {{ \Carbon\Carbon::parse($usuario->updated_at)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="security-item">
                            <div class="security-icon info">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="security-content">
                                <h4>Estado de Cuenta</h4>
                                <p>{{ $usuario->estado_cuenta }}</p>
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

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            color: var(--text-secondary);
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: var(--bg-white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.1);
        }

        .form-input:read-only {
            background: var(--bg-light);
            color: var(--text-secondary);
            cursor: not-allowed;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
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

        .form-hint {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* === FUERZA DE CONTRASEÑA === */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            width: 100%;
            height: 4px;
            background: var(--border-color);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-fill[data-strength="0"] {
            width: 0%;
            background: var(--danger);
        }

        .strength-fill[data-strength="1"] {
            width: 25%;
            background: var(--danger);
        }

        .strength-fill[data-strength="2"] {
            width: 50%;
            background: var(--warning);
        }

        .strength-fill[data-strength="3"] {
            width: 75%;
            background: var(--warning);
        }

        .strength-fill[data-strength="4"] {
            width: 100%;
            background: var(--success);
        }

        .strength-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* === REQUISITOS DE CONTRASEÑA === */
        .password-requirements {
            background: var(--bg-light);
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-color);
        }

        .password-requirements h4 {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--text-secondary);
            transition: color 0.2s ease;
        }

        .requirement.met {
            color: var(--success);
        }

        .requirement.met i {
            color: var(--success);
        }

        .requirement i {
            font-size: 0.5rem;
            color: var(--text-secondary);
            transition: color 0.2s ease;
        }

        /* === BOTONES === */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #009975;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* === SECCIÓN DE SEGURIDAD === */
        .security-section {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .security-card {
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .security-item:last-child {
            border-bottom: none;
        }

        .security-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .security-icon.success {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
        }

        .security-icon.warning {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .security-icon.info {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
        }

        .security-content h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .security-content p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* === RESPONSIVE === */
        @media (max-width: 1024px) {
            .account-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .account-header {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .header-illustration {
                font-size: 3rem;
            }

            .header-content h1 {
                font-size: 1.5rem;
            }

            .form-actions {
                justify-content: center;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .account-header {
                padding: 1.5rem;
            }

            .card-body {
                padding: 1rem;
            }

            .security-item {
                flex-direction: column;
                text-align: center;
                gap: 0.75rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle de visibilidad de contraseña
            const passwordToggles = document.querySelectorAll('.password-toggle');

            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.closest('.input-group').querySelector('input');
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.className = 'fas fa-eye-slash';
                    } else {
                        input.type = 'password';
                        icon.className = 'fas fa-eye';
                    }
                });
            });

            // Validación de fuerza de contraseña
            const newPasswordInput = document.getElementById('newPassword');
            const strengthFill = document.querySelector('.strength-fill');
            const requirements = document.querySelectorAll('.requirement');

            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                // Validar requisitos
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                // Actualizar indicadores visuales de requisitos
                updateRequirement('length', hasLength);
                updateRequirement('uppercase', hasUppercase);
                updateRequirement('number', hasNumber);
                updateRequirement('special', hasSpecial);

                // Calcular fuerza
                if (hasLength) strength++;
                if (hasUppercase) strength++;
                if (hasNumber) strength++;
                if (hasSpecial) strength++;

                // Actualizar barra de fuerza
                strengthFill.setAttribute('data-strength', strength);
            });

            function updateRequirement(type, met) {
                const requirement = document.querySelector(`[data-requirement="${type}"]`);
                if (met) {
                    requirement.classList.add('met');
                } else {
                    requirement.classList.remove('met');
                }
            }

            // Validación de formularios
            const emailForm = document.getElementById('emailForm');
            const passwordForm = document.getElementById('passwordForm');

            emailForm.addEventListener('submit', function(e) {
                const newEmail = document.getElementById('newEmail').value;
                const confirmEmail = document.getElementById('confirmEmail').value;

                if (newEmail !== confirmEmail) {
                    e.preventDefault();
                    showNotification('Los correos electrónicos no coinciden', 'error');
                    return;
                }

                if (!isValidEmail(newEmail)) {
                    e.preventDefault();
                    showNotification('Por favor ingresa un correo electrónico válido', 'error');
                    return;
                }
            });

            passwordForm.addEventListener('submit', function(e) {
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    showNotification('Las contraseñas no coinciden', 'error');
                    return;
                }

                if (newPassword.length < 8) {
                    e.preventDefault();
                    showNotification('La contraseña debe tener al menos 8 caracteres', 'error');
                    return;
                }
            });

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            // Mostrar notificaciones de Laravel
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showNotification('{{ $error }}', 'error');
                @endforeach
            @endif

            function showNotification(message, type) {
                // Crear elemento de notificación
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            `;

                // Estilos de la notificación
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

                // Remover después de 3 segundos
                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Añadir estilos de animación
            const style = document.createElement('style');
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
        });
    </script>
@endsection
