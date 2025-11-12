<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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
            max-width: 500px;
            margin: 0 auto;
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
            background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
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

        /* Form Options */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
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
            color: #718096;
            margin: 0;
        }

        .auth-link {
            color: #718096;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-link:hover {
            color: #718096;
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

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
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

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow-3);
            transform: scale(0.9);
            transition: transform 0.3s ease;
            position: relative;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .modal-close:hover {
            color: var(--primary-color);
            background: rgba(0, 79, 110, 0.1);
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #FF6B6B 0%, #EE5A52 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            text-align: center;
            margin-bottom: 1rem;
        }

        .modal-message {
            color: var(--text-secondary);
            text-align: center;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-btn.primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            color: white;
        }

        .modal-btn.secondary {
            background: #f7fafc;
            color: var(--text-secondary);
            border: 2px solid #e2e8f0;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-2);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Panel Izquierdo - Formulario -->
        <div class="form-panel">
            <div class="form-content">
                <div class="auth-header">
                    <h2 class="auth-title">Bienvenido de Nuevo</h2>
                    <p class="auth-subtitle">Ingresa a tu cuenta para continuar</p>
                </div>

                <form method="POST" action="#" class="auth-form" data-scroll>
                    <div class="form-group" data-scroll data-scroll-delay="0">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Correo Electrónico
                        </label>
                        <div class="input-container">
                            <input type="email" class="form-control" id="email" name="email" required autofocus
                                placeholder="tu.correo@universidad.edu">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" data-scroll data-scroll-delay="50">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Contraseña
                        </label>
                        <div class="input-container">
                            <input type="password" class="form-control" id="password" name="password" required
                                placeholder="Ingresa tu contraseña">
                            <div class="input-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options" data-scroll data-scroll-delay="100">
                        <div class="remember-me">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordar sesión</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="forgot-password">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="auth-btn primary" data-scroll data-scroll-delay="150">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="btn-text">Iniciar Sesión</span>
                        <div class="btn-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>

                    <div class="auth-footer">
                        <p>¿No tienes una cuenta?
                            <a href="{{ route('register') }}" class="auth-link">
                                Regístrate aquí
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
                        <i class="fas fa-rocket"></i>
                        ¡Qué Bueno Verte de Nuevo!
                    </div>
                    <h1 class="welcome-title">
                        Tu Pasión
                        <span class="highlight">Nos Inspira</span>
                    </h1>
                    <p class="welcome-description">
                        Vuelve a ser parte de la experiencia deportiva y cultural más grande de la universidad.
                        Tus logros y participación hacen la diferencia.
                    </p>

                    <div class="benefits-grid">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Sigue Tu Progreso</h4>
                                <p>Revisa tus estadísticas y logros en las actividades anteriores</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Nuevos Eventos</h4>
                                <p>Descubre las nuevas actividades y competencias disponibles</p>
                            </div>
                        </div>

                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Logros por Alcanzar</h4>
                                <p>Compite por nuevos reconocimientos y premios especiales</p>
                            </div>
                        </div>
                    </div>

                    <div class="stats-container">
                        <div class="stat">
                            <div class="stat-number">85%</div>
                            <div class="stat-label">Participación Activa</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Soporte</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Comunidad</div>
                        </div>
                    </div>

                    <div class="inspiration-quote">
                        <i class="fas fa-quote-left"></i>
                        <p>"Cada inicio de sesión es una nueva oportunidad para superarte y conectar con tu comunidad"
                        </p>
                        <div class="quote-author">- Equipo Organizador</div>
                    </div>
                </div>
            </div>

            <div class="inspiration-background">
                <div class="floating-elements">
                    <div class="floating-element sport">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="floating-element art">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="floating-element music">
                        <i class="fas fa-drum"></i>
                    </div>
                    <div class="floating-element chess">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cuenta suspendida -->
    <div class="modal-overlay" id="suspendedModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-icon">
                <i class="fas fa-ban"></i>
            </div>
            <h3 class="modal-title">Cuenta Suspendida</h3>
            <p class="modal-message">
                Su cuenta ha sido suspendida temporalmente. Esto puede deberse a:
                <br><br>
                • Incumplimiento de normas de la plataforma<br>
                • Actividad sospechosa detectada<br>
                • Documentación incompleta o inválida<br>
                • Comportamiento inapropiado en actividades
                <br><br>
                Para más información, contacte al comité organizador.
            </p>
            <div class="modal-actions">
                <a href="mailto:comite@universidad.edu" class="modal-btn primary">
                    <i class="fas fa-envelope"></i>
                    Contactar Comité
                </a>
                <button class="modal-btn secondary" id="closeModalBtn">
                    <i class="fas fa-times"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            const suspendedModal = document.getElementById('suspendedModal');
            const closeModal = document.getElementById('closeModal');
            const closeModalBtn = document.getElementById('closeModalBtn');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                        '<i class="fas fa-eye-slash"></i>';
                });
            }

            // Form submission loading state
            const authForm = document.querySelector('.auth-form');
            const submitBtn = authForm.querySelector('.auth-btn');

            function showSuspendedModal() {
                if (suspendedModal) {
                    suspendedModal.classList.add('active');
                }
            }

            function hideSuspendedModal() {
                if (suspendedModal) {
                    suspendedModal.classList.remove('active');
                }
            }

            if (closeModal) {
                closeModal.addEventListener('click', hideSuspendedModal);
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', hideSuspendedModal);
            }

            // Close modal when clicking outside
            if (suspendedModal) {
                suspendedModal.addEventListener('click', function(e) {
                    if (e.target === suspendedModal) {
                        hideSuspendedModal();
                    }
                });
            }

            // Check if we need to show the suspended modal
            @if (session('show_suspended_modal'))
                showSuspendedModal();
            @endif

            // Actualizar el evento de envío del formulario
            authForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitBtn.classList.add('loading');

                // Enviar formulario real
                const formData = new FormData(authForm);

                fetch('{{ route('login.submit') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        // Verificar si la respuesta es JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Si no es JSON, probablemente es un error del servidor
                            throw new Error('El servidor devolvió una respuesta no válida');
                        }
                    })
                    .then(data => {
                        submitBtn.classList.remove('loading');
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            if (data.show_modal) {
                                showSuspendedModal();
                            } else {
                                // Mostrar mensaje de error en el formulario
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'alert alert-error';
                                errorDiv.style.cssText =
                                    'background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;';
                                errorDiv.textContent = data.message || 'Error en el inicio de sesión';

                                // Insertar antes del formulario
                                authForm.parentNode.insertBefore(errorDiv, authForm);

                                // Remover después de 5 segundos
                                setTimeout(() => {
                                    errorDiv.remove();
                                }, 5000);
                            }
                        }
                    })
                    .catch(error => {
                        submitBtn.classList.remove('loading');
                        console.error('Error:', error);

                        // Mostrar error genérico
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-error';
                        errorDiv.style.cssText =
                            'background: #fee; border: 1px solid #fcc; color: #c33; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;';
                        errorDiv.textContent = 'Error en el servidor. Intente nuevamente.';

                        authForm.parentNode.insertBefore(errorDiv, authForm);

                        setTimeout(() => {
                            errorDiv.remove();
                        }, 5000);
                    });
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

            // Initial check
            checkScroll();
            window.addEventListener('scroll', checkScroll);
        });
    </script>
</body>

</html>
