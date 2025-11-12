@extends('layouts.app')

@section('title', 'Inicio - Evento Deportivo y Cultural')

@section('content')
    <!-- Hero Section Mejorada -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="row align-items-center min-vh-80">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <div class="badge-container">
                            <span class="hero-badge">
                                <i class="fas fa-calendar-star"></i>
                                Edición <span id="current-year-badge">{{ date('Y') }}</span>
                            </span>
                        </div>
                        <h1 class="hero-title">
                            Descubre Tu
                            <span class="text-gradient">Pasión</span>
                            <br>En Nuestro Evento
                        </h1>
                        <p class="hero-description">
                            Únete a la comunidad universitaria en una experiencia única donde el deporte y la cultura se
                            encuentran.
                            Inscríbete en tus disciplinas favoritas y crea memorias inolvidables.
                        </p>
                        <div class="hero-actions">
                            @auth
                                <a href="{{ route('home') }}" class="btn-hero primary">
                                    <i class="fas fa-rocket"></i>
                                    Ir al Dashboard
                                    <div class="hover-effect"></div>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn-hero primary">
                                    <i class="fas fa-user-plus"></i>
                                    Comenzar Ahora
                                    <div class="hover-effect"></div>
                                </a>
                                <a href="{{ route('login') }}" class="btn-hero secondary">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Ingresar
                                </a>
                            @endauth
                        </div>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <div class="stat-number">12+</div>
                                <div class="stat-label">Disciplinas</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">5000+</div>
                                <div class="stat-label">Participantes</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">100%</div>
                                <div class="stat-label">Diversión</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="hero-visual">
                        <div class="floating-card sport">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <div class="floating-card culture">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="floating-card chess">
                            <i class="fas fa-chess-queen"></i>
                        </div>
                        <div class="main-visual">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- Disciplinas Section Mejorada -->
    <section id="disciplinas" class="disciplines-section">
        <div class="container">
            <div class="section-header scroll-animate">
                <h2 class="section-title">Explora Nuestras Disciplinas</h2>
                <p class="section-subtitle">Encuentra la actividad perfecta para ti entre nuestra amplia variedad de
                    opciones</p>
            </div>

            <div class="disciplines-grid">
                <!-- Deportes de Conjunto -->
                <div class="discipline-card scroll-animate" data-category="deporte" data-delay="0">
                    <div class="card-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <h3 class="card-title">Deportes de Conjunto</h3>
                    <p class="card-description">Trabajo en equipo y competencia sana</p>
                    <ul class="card-features">
                        <li>Fútbol 7 Femenil/Varonil</li>
                        <li>Básquetbol</li>
                        <li>Voleibol</li>
                        <li>Soccer</li>
                    </ul>
                    <div class="card-hover-effect"></div>
                </div>

                <!-- Ajedrez -->
                <div class="discipline-card scroll-animate" data-category="estrategia" data-delay="50">
                    <div class="card-icon">
                        <i class="fas fa-chess-knight"></i>
                    </div>
                    <h3 class="card-title">Ajedrez</h3>
                    <p class="card-description">Estrategia, concentración y mente brillante</p>
                    <ul class="card-features">
                        <li>Torneos por categorías</li>
                        <li>Clasificación ELO</li>
                        <li>Premios especiales</li>
                    </ul>
                    <div class="card-hover-effect"></div>
                </div>

                <!-- Atletismo -->
                <div class="discipline-card scroll-animate" data-category="deporte" data-delay="100">
                    <div class="card-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <h3 class="card-title">Atletismo</h3>
                    <p class="card-description">Velocidad, resistencia y superación personal</p>
                    <ul class="card-features">
                        <li>Carreras de velocidad</li>
                        <li>Resistencia</li>
                        <li>Relevos</li>
                    </ul>
                    <div class="card-hover-effect"></div>
                </div>

                <!-- Culturales -->
                <div class="discipline-card scroll-animate" data-category="cultura" data-delay="150">
                    <div class="card-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="card-title">Actividades Culturales</h3>
                    <p class="card-description">Expresa tu creatividad y talento artístico</p>
                    <ul class="card-features">
                        <li>Canto individual/grupal</li>
                        <li>Fotografía</li>
                        <li>Y más sorpresas...</li>
                    </ul>
                    <div class="card-hover-effect"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Información Section Mejorada -->
    <section id="informacion" class="process-section">
        <div class="container">
            <div class="section-header scroll-animate">
                <h2 class="section-title">Participar es Muy Fácil</h2>
                <p class="section-subtitle">Solo sigue estos simples pasos y únete a la experiencia</p>
            </div>

            <div class="process-steps">
                <div class="process-line"></div>

                <div class="process-step scroll-animate" data-delay="0">
                    <div class="step-indicator">
                        <div class="step-number">1</div>
                        <div class="step-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3>Regístrate</h3>
                        <p>Crea tu cuenta con tu número de trabajador y datos básicos</p>
                    </div>
                </div>

                <div class="process-step scroll-animate" data-delay="50">
                    <div class="step-indicator">
                        <div class="step-number">2</div>
                        <div class="step-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3>Sube Documentos</h3>
                        <p>Adjunta tu constancia laboral y CFDI para validación</p>
                    </div>
                </div>

                <div class="process-step scroll-animate" data-delay="100">
                    <div class="step-indicator">
                        <div class="step-number">3</div>
                        <div class="step-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3>Selecciona Disciplinas</h3>
                        <p>Elige hasta 2 actividades de tu preferencia</p>
                    </div>
                </div>

                <div class="process-step scroll-animate" data-delay="150">
                    <div class="step-indicator">
                        <div class="step-number">4</div>
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="step-content">
                        <h3>¡Listo!</h3>
                        <p>Recibe notificación y prepárate para el evento</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content scroll-animate">
                <h2 class="cta-title">¿Listo para ser parte de la experiencia?</h2>
                <p class="cta-description">No esperes más, inscríbete ahora y asegura tu lugar en el evento del año</p>
                @auth
                    <a href="{{ route('home') }}" class="btn-cta">
                        <i class="fas fa-arrow-right"></i>
                        Continuar al Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-cta">
                        <i class="fas fa-rocket"></i>
                        Inscribirme Ahora
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <style>
        /* Estilos de animación para scroll */
        .scroll-animate {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .scroll-animate.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Disciplinas animación escalonada */
        .discipline-card.scroll-animate {
            transition-delay: calc(var(--delay, 0) * 1ms);
        }

        /* Process steps animación escalonada */
        .process-step.scroll-animate {
            transition-delay: calc(var(--delay, 0) * 1ms);
        }

        /* Hero Section Styles */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
        }

        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg,
                    rgba(0, 79, 110, 0.9) 0%,
                    rgba(0, 79, 110, 0.7) 50%,
                    rgba(0, 170, 139, 0.3) 100%);
            backdrop-filter: blur(2px);
        }

        .min-vh-80 {
            min-height: 80vh;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .badge-container {
            margin-bottom: 1.5rem;
        }

        .hero-badge {
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
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #FFFFFF 0%, #E0F7FA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-description {
            font-size: 1.25rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 90%;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .btn-hero {
            position: relative;
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .btn-hero.primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            color: white;
            box-shadow: var(--shadow-2);
        }

        .btn-hero.secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-hero .hover-effect {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-3);
        }

        .btn-hero.primary:hover .hover-effect {
            left: 100%;
        }

        .btn-hero.secondary:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .main-visual {
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .main-visual i {
            font-size: 4rem;
            color: var(--secondary-color);
        }

        .floating-card {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            animation: float 6s ease-in-out infinite;
            box-shadow: var(--shadow-2);
        }

        .floating-card.sport {
            top: 20%;
            left: 10%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            animation-delay: 0s;
        }

        .floating-card.culture {
            top: 10%;
            right: 20%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            animation-delay: 2s;
        }

        .floating-card.chess {
            bottom: 20%;
            right: 10%;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            animation-delay: 4s;
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

        .hero-scroll-indicator {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }

        .scroll-arrow {
            width: 30px;
            height: 30px;
            border-right: 3px solid rgba(255, 255, 255, 0.7);
            border-bottom: 3px solid rgba(255, 255, 255, 0.7);
            transform: rotate(45deg);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: rotate(45deg) translateY(0);
            }

            50% {
                transform: rotate(45deg) translateY(-10px);
            }
        }

        /* Section Styles */
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Discipline Cards */
        .disciplines-section {
            padding: 6rem 0;
            background: var(--background-color);
        }

        .disciplines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .discipline-card {
            position: relative;
            background: var(--surface-color);
            padding: 2.5rem 2rem;
            border-radius: 20px;
            box-shadow: var(--shadow-1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .discipline-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .discipline-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-3);
        }

        .discipline-card:hover::before {
            transform: scaleX(1);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .card-description {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .card-features {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .card-features li {
            padding: 0.5rem 0;
            color: var(--text-secondary);
            position: relative;
            padding-left: 1.5rem;
        }

        .card-features li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--secondary-color);
            font-weight: bold;
        }

        .card-hover-effect {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 170, 139, 0.05), transparent);
            transition: left 0.6s;
        }

        .discipline-card:hover .card-hover-effect {
            left: 100%;
        }

        /* Process Section */
        .process-section {
            padding: 6rem 0;
            background: white;
        }

        .process-steps {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .process-line {
            position: absolute;
            left: 60px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            z-index: 1;
        }

        .process-step {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 3rem;
            z-index: 2;
        }

        .step-indicator {
            position: relative;
            width: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 2rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-2);
        }

        .step-icon {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            box-shadow: var(--shadow-1);
            border: 3px solid var(--background-color);
        }

        .step-content {
            flex: 1;
            background: var(--background-color);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: var(--shadow-1);
            transition: all 0.3s ease;
        }

        .step-content:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-2);
        }

        .step-content h3 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .step-content p {
            color: var(--text-secondary);
            margin: 0;
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, #003D58 100%);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #009975 100%);
            color: white;
            padding: 1.25rem 2.5rem;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-2);
        }

        .btn-cta:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-3);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-description {
                font-size: 1.1rem;
                max-width: 100%;
            }

            .hero-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-hero {
                width: 100%;
                justify-content: center;
            }

            .hero-stats {
                justify-content: space-around;
            }

            .hero-visual {
                height: 300px;
                margin-top: 2rem;
            }

            .main-visual {
                width: 150px;
                height: 150px;
            }

            .main-visual i {
                font-size: 3rem;
            }

            .floating-card {
                width: 60px;
                height: 60px;
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .process-step {
                flex-direction: column;
                text-align: center;
            }

            .step-indicator {
                margin-right: 0;
                margin-bottom: 1rem;
            }

            .process-line {
                left: 50%;
                transform: translateX(-50%);
            }

            .cta-title {
                font-size: 2rem;
            }
        }
    </style>

    <script>
        // Actualizar año en el badge del hero
        document.getElementById('current-year-badge').textContent = new Date().getFullYear();

        // Animaciones de scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.scroll-animate');

            // Función para verificar si un elemento está en el viewport
            function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top <= (window.innerHeight * 0.70) &&
                    rect.bottom >= 0
                );
            }

            // Función para animar elementos
            function animateOnScroll() {
                animatedElements.forEach(element => {
                    if (isInViewport(element)) {
                        const delay = element.getAttribute('data-delay') || 0;
                        element.style.setProperty('--delay', delay);

                        setTimeout(() => {
                            element.classList.add('animated');
                        }, delay);
                    } else {
                        // Remover la clase cuando el elemento sale del viewport
                        element.classList.remove('animated');
                    }
                });
            }

            // Ejecutar al cargar la página
            animateOnScroll();

            // Ejecutar al hacer scroll
            window.addEventListener('scroll', animateOnScroll);

            // Ejecutar al redimensionar la ventana
            window.addEventListener('resize', animateOnScroll);

            // Smooth scroll para el indicador del hero
            document.querySelector('.hero-scroll-indicator').addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('disciplinas').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
@endsection
