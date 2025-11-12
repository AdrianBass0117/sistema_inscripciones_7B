<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #004F6E;
            --secondary-color: #00AA8B;
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --shadow-2: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-3: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* === LAYOUT PRINCIPAL === */
        .login-container {
            display: flex;
            min-height: 100vh;
            background: white;
        }

        .form-panel {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
        }

        .inspiration-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
            position: relative;
            overflow-y: auto;
        }

        /* === PANEL DE FORMULARIO === */
        .form-content {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
        }

        /* === PANEL DE INSPIRACIÓN === */
        .inspiration-content {
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            padding: 3rem 2rem;
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            min-height: 100vh
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

        .password-tips {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 3rem;
            text-align: left;
        }

        .password-tips h4 {
            color: white;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .password-tips ul {
            color: rgba(255, 255, 255, 0.9);
            padding-left: 1.5rem;
            margin: 0;
        }

        .password-tips li {
            margin-bottom: 0.5rem;
            line-height: 1.4;
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
            background-image: url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
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

        /* === ESTILOS DEL FORMULARIO === */
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

        .form-control:read-only {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
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

        /* Scroll personalizado para paneles */
        .inspiration-panel::-webkit-scrollbar {
            width: 6px;
        }

        .inspiration-panel::-webkit-scrollbar-track {
            background: rgba(241, 241, 241, 0.1);
            border-radius: 3px;
        }

        .inspiration-panel::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .inspiration-panel::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .form-panel {
                position: relative;
                height: auto;
                min-height: 100vh;
            }

            .inspiration-panel {
                display: none;
            }

            .form-content {
                padding: 1rem;
                max-width: 100%;
            }

            .auth-title {
                font-size: 1.75rem;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .login-container {
                flex-direction: row;
            }

            .form-content {
                max-width: 100%;
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Panel Izquierdo - Formulario -->
        <div class="form-panel">
            <div class="form-content">
                <div class="auth-header">
                    <h2 class="auth-title">Nueva Contraseña</h2>
                    <p class="auth-subtitle">Crea una nueva contraseña segura para tu cuenta</p>
                </div>

                <form method="POST" action="#" class="auth-form">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <div class="input-container">
                            <input type="email" class="form-control" id="email" name="email"
                                value="usuario@ejemplo.com" required readonly>
                            <div class="input-icon">
                                <i class="fas fa-at"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Nueva Contraseña
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
                                name="password_confirmation" required placeholder="Repite tu nueva contraseña">
                            <div class="input-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <button type="button" class="password-toggle" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="auth-btn primary">
                        <i class="fas fa-save"></i>
                        <span class="btn-text">Guardar Nueva Contraseña</span>
                        <div class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>

                    <div class="auth-footer">
                        <p>¿Recordaste tu contraseña?
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
                        <i class="fas fa-lock-open"></i>
                        ¡Nuevo Comienzo!
                    </div>
                    <h1 class="welcome-title">
                        Tu Seguridad
                        <span class="highlight">Renovada</span>
                    </h1>
                    <p class="welcome-description">
                        Estás a un paso de recuperar el acceso completo a tu cuenta. Elige una contraseña segura que te
                        proteja y te permita continuar disfrutando de todas las actividades.
                    </p>

                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Protección Actualizada</h4>
                                <p>Tu nueva contraseña mantendrá tu cuenta segura y protegida</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Acceso Inmediato</h4>
                                <p>Podrás ingresar a tu cuenta inmediatamente después de este paso</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Continuidad Asegurada</h4>
                                <p>Retoma tu participación en las actividades sin interrupciones</p>
                            </div>
                        </div>
                    </div>

                    <div class="password-tips">
                        <h4>Consejos para una contraseña segura:</h4>
                        <ul>
                            <li>Usa al menos 8 caracteres</li>
                            <li>Combina mayúsculas y minúsculas</li>
                            <li>Incluye números y símbolos</li>
                            <li>Evita información personal obvia</li>
                        </ul>
                    </div>

                    <div class="inspiration-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>"Cada nuevo comienzo es una oportunidad para fortalecer nuestra seguridad y continuar nuestro
                            camino con mayor protección"</p>
                        <div class="quote-author">- Equipo de Seguridad</div>
                    </div>
                </div>
            </div>

            <div class="inspiration-background">
                <div class="floating-elements">
                    <div class="floating-element sport">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="floating-element art">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="floating-element music">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div class="floating-element chess">
                        <i class="fas fa-rocket"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

            // Form submission loading state
            const authForm = document.querySelector('.auth-form');
            const submitBtn = authForm.querySelector('.auth-btn');

            authForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitBtn.classList.add('loading');

                // Simular envío del formulario
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    alert('Contraseña restablecida correctamente');
                }, 2000);
            });
        });
    </script>
</body>

</html>
