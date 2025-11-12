<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistema de Inscripciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #004F6E;
            --secondary-color: #00AA8B;
            --accent-color: #0077CC;
            --surface-color: #FFFFFF;
            --background-color: #F8F9FA;
            --text-primary: #1A1A1A;
            --text-secondary: #666666;
            --shadow-1: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            --shadow-2: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
            --shadow-3: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
            line-height: 1.6;
            padding-top: 80px;
            /* Para compensar el navbar fijo */
        }

        /* Navbar Styles - Material Design Inspired */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow-2);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: rgba(0, 79, 110, 0.95);
            backdrop-filter: blur(20px);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: transform 0.2s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin: 0 0.25rem;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        /* Botón de Iniciar Sesión Mejorado */
        .login-btn {
            background: linear-gradient(135deg, var(--secondary-color), #009975);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-1);
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-3);
            color: white;
            text-decoration: none;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .register-btn {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }

        /* Main Content */
        main {
            min-height: calc(100vh - 160px);
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-text {
            font-weight: 500;
        }

        .footer-links {
            display: flex;
            gap: 1.5rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }

            .nav-link {
                padding: 0.5rem !important;
                margin: 0.125rem 0;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-links {
                justify-content: center;
            }

            .btn-group-mobile {
                display: flex;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .login-btn,
            .register-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading animation for buttons */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 20px !important;
                /* Ajusta según la altura de tu navbar móvil */
            }
        }

        /* Botones móviles más compactos */
        .mobile-icon {
            width: 40px !important;
            height: 40px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 10px !important;
        }

        .mobile-icon.login-btn {
            min-width: auto !important;
        }

        /* Asegurar que el hero content tenga suficiente espacio en móvil */
        @media (max-width: 768px) {
            .hero-content {
                padding-top: 1rem;
            }

            .badge-container {
                margin-top: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar Fijo -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-trophy"></i>
                <span>Evento Deportivo-Cultural</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#disciplinas">
                            <i class="fas fa-list"></i> Disciplinas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#informacion">
                            <i class="fas fa-info-circle"></i> Información
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="fas fa-user"></i> Mi Cuenta
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn register-btn">
                                <i class="fas fa-sign-out-alt"></i> Salir
                            </button>
                        </form>
                    @else
                        <!-- Botones de escritorio - ocultos en móvil -->
                        <div class="d-none d-md-flex align-items-center gap-2">
                            <a href="{{ route('register') }}" class="register-btn">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                            <a href="{{ route('login') }}" class="login-btn">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </div>

                        <!-- Botones móviles - solo iconos -->
                        <div class="d-flex d-md-none align-items-center gap-1">
                            <a href="{{ route('register') }}" class="register-btn mobile-icon" title="Registrarse">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            <a href="{{ route('login') }}" class="login-btn mobile-icon" title="Iniciar Sesión">
                                <i class="fas fa-sign-in-alt"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Dinámico -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-text">
                    <p class="mb-0">
                        &copy; <span id="current-year">{{ date('Y') }}</span> Universidad - Sistema de Inscripciones
                        Deportivas y Culturales
                    </p>
                </div>
                <div class="footer-links">
                    <a href="#"><i class="fas fa-shield-alt"></i> Privacidad</a>
                    <a href="#"><i class="fas fa-file-contract"></i> Términos</a>
                    <a href="#"><i class="fas fa-envelope"></i> Contacto</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Año actual dinámico en el footer
        document.getElementById('current-year').textContent = new Date().getFullYear();

        // Smooth scroll para enlaces internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efecto de carga para botones
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
