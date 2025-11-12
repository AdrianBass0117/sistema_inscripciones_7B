@extends('aspirante.layouts.app')

@section('content')
<div class="dashboard-content">
    <!-- Estado de Validación -->
    <div class="validation-status">
        <div class="status-card">
            <div class="status-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="status-content">
                <h3>Estado de Validación</h3>
                <div class="status-badge pending">En Proceso</div>
                <p>Tu documentación está siendo revisada</p>
            </div>
        </div>
    </div>

    <!-- Mensaje Principal -->
    <div class="welcome-section">
        <div class="welcome-card">
            <div class="welcome-content">
                <div class="welcome-badge">
                    <i class="fas fa-seedling"></i>
                    ¡Bienvenido Aspirante!
                </div>
                <h2>Tu Aventura Deportiva <span class="highlight">Está por Comenzar</span></h2>
                <p class="welcome-description">
                    Estás a un paso de formar parte de la experiencia universitaria más emocionante.
                    Mientras completamos la validación de tus datos, prepárate para descubrir un mundo
                    lleno de oportunidades deportivas y culturales.
                </p>

                <div class="process-steps">
                    <div class="step-item current">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Registro Completado</h4>
                            <p>Has enviado tu información correctamente</p>
                        </div>
                    </div>
                    <div class="step-item upcoming">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Validación en Proceso</h4>
                            <p>Revisando tu documentación</p>
                        </div>
                    </div>
                    <div class="step-item upcoming">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Acceso Completo</h4>
                            <p>Podrás inscribirte a las disciplinas</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="welcome-illustration">
                <i class="fas fa-door-open"></i>
            </div>
        </div>
    </div>

    <!-- Información Importante -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="info-content">
                <h3>¿Qué Sucede Ahora?</h3>
                <p>Nuestro equipo está verificando tu información. Este proceso garantiza que todos los participantes cumplan con los requisitos necesarios para una competencia justa y segura.</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="info-content">
                <h3>Tiempo de Espera</h3>
                <p>La validación generalmente toma entre 24 y 48 horas. Te notificaremos por correo electrónico tan pronto como tu cuenta sea activada completamente.</p>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="info-content">
                <h3>Próximos Pasos</h3>
                <p>Una vez validado, tendrás acceso completo al catálogo de disciplinas y podrás inscribirte en las actividades que más te interesen.</p>
            </div>
        </div>
    </div>

    <!-- Mensaje Motivacional -->
    <div class="motivational-section">
        <div class="motivational-card">
            <div class="quote-icon">
                <i class="fas fa-quote-left"></i>
            </div>
            <div class="quote-content">
                <h3>La Paciencia es la Compañera de los Grandes Logros</h3>
                <p>Todo proceso valioso requiere su tiempo. Mientras esperas, recuerda que cada atleta, cada artista, cada campeón comenzó exactamente donde tú estás ahora: lleno de potencial y a la espera de su oportunidad.</p>
                <div class="quote-author">
                    <i class="fas fa-heart"></i>
                    Equipo Organizador
                </div>
            </div>
        </div>
    </div>

    <!-- Contacto de Soporte -->
    <div class="support-section">
        <div class="support-card">
            <div class="support-content">
                <h3><i class="fas fa-headset"></i> ¿Necesitas Ayuda?</h3>
                <p>Si tienes alguna pregunta sobre tu proceso de validación o necesitas asistencia, nuestro equipo de soporte está aquí para ayudarte.</p>
                <div class="support-contacts">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>soporte@universidad.edu</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+52 55 1234 5678</span>
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
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .dashboard-content {
        max-width: 1000px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* === ESTADO DE VALIDACIÓN === */
    .validation-status {
        margin-bottom: 2rem;
    }

    .status-card {
        background: var(--bg-white);
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border-left: 6px solid var(--warning);
    }

    .status-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--warning), #B7791F);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }

    .status-content h3 {
        font-size: 1.25rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .status-badge.pending {
        background: rgba(214, 158, 46, 0.1);
        color: var(--warning);
        border: 1px solid var(--warning);
    }

    .status-content p {
        color: var(--text-secondary);
        margin: 0;
    }

    /* === MENSAJE PRINCIPAL === */
    .welcome-section {
        margin-bottom: 3rem;
    }

    .welcome-card {
        background: linear-gradient(135deg, var(--primary-color), #003D58);
        color: white;
        padding: 3rem;
        border-radius: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: "";
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }

    .welcome-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .highlight {
        background: linear-gradient(135deg, var(--secondary-color) 0%, #00CC99 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .welcome-description {
        font-size: 1.2rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
        opacity: 0.9;
        max-width: 600px;
    }

    .welcome-illustration {
        font-size: 8rem;
        opacity: 0.7;
        z-index: 1;
    }

    /* === PROCESO DE VALIDACIÓN === */
    .process-steps {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .step-item.current .step-number {
        background: var(--secondary-color);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 170, 139, 0.3);
    }

    .step-item.upcoming .step-number {
        background: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.7);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .step-content h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        color: white;
    }

    .step-content p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* === INFORMACIÓN IMPORTANTE === */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .info-card {
        background: var(--bg-white);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid var(--bg-light);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .info-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: var(--bg-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary-color);
        flex-shrink: 0;
    }

    .info-content h3 {
        margin: 0 0 1rem 0;
        color: var(--text-primary);
        font-size: 1.1rem;
    }

    .info-content p {
        margin: 0;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* === MENSAJE MOTIVACIONAL === */
    .motivational-section {
        margin-bottom: 3rem;
    }

    .motivational-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        border-radius: 20px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .motivational-card::before {
        content: "";
        position: absolute;
        top: -20%;
        right: -10%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .quote-icon {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.3);
        margin-bottom: 1rem;
    }

    .quote-content h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .quote-content p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        opacity: 0.9;
    }

    .quote-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-style: italic;
        opacity: 0.8;
    }

    /* === SECCIÓN DE SOPORTE === */
    .support-section {
        margin-bottom: 2rem;
    }

    .support-card {
        background: var(--bg-white);
        padding: 2rem;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        border: 2px solid var(--bg-light);
    }

    .support-content h3 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .support-content p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .support-contacts {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: var(--bg-light);
        border-radius: 10px;
        color: var(--text-primary);
    }

    .contact-item i {
        color: var(--secondary-color);
        width: 16px;
    }

    /* === RESPONSIVE === */
    @media (max-width: 768px) {
        .dashboard-content {
            padding: 0.5rem;
        }

        .welcome-card {
            flex-direction: column;
            text-align: center;
            gap: 2rem;
            padding: 2rem;
        }

        .welcome-content h2 {
            font-size: 2rem;
        }

        .welcome-illustration {
            font-size: 5rem;
        }

        .process-steps {
            flex-direction: column;
            gap: 1.5rem;
        }

        .step-item {
            justify-content: center;
            text-align: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-card {
            flex-direction: column;
            text-align: center;
        }

        .support-contacts {
            flex-direction: column;
            gap: 1rem;
        }

        .motivational-card {
            padding: 2rem;
        }

        .status-card {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .welcome-card {
            padding: 1.5rem;
        }

        .welcome-content h2 {
            font-size: 1.75rem;
        }

        .welcome-description {
            font-size: 1rem;
        }

        .info-card {
            padding: 1.5rem;
        }

        .motivational-card {
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animación de elementos al hacer scroll
        const animatedElements = document.querySelectorAll('.info-card, .motivational-card, .support-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Aplicar estilos iniciales y observar
        animatedElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });

        // Simular actualización de estado (en una aplicación real, esto vendría del servidor)
        setTimeout(() => {
            const statusBadge = document.querySelector('.status-badge');
            if (statusBadge) {
                // Esto es solo para demostración - en producción vendría de una API
                console.log('Verificando estado de validación...');
            }
        }, 2000);
    });
</script>
@endsection
