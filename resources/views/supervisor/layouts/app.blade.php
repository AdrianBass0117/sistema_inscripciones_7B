<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Evento Universitario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #004F6E;
            --secondary-color: #00AA8B;
            --accent-color: #0077B6;
            --text-primary: #2D3748;
            --text-secondary: #718096;
            --bg-light: #F7FAFC;
            --bg-white: #FFFFFF;
            --success: #38A169;
            --warning: #D69E2E;
            --error: #E53E3E;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: var(--bg-light);
            overflow: hidden;
        }

        /* === PANEL LATERAL === */
        .sidebar {
            width: 280px;
            background: var(--bg-white);
            box-shadow: var(--shadow-md);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E2E8F0;
            background: var(--bg-light);
            position: sticky;
            bottom: 0;
            background: inherit;
            z-index: 10;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
        }

        .nav-section {
            margin-bottom: 1.5rem;
        }

        .nav-title {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: rgba(0, 79, 110, 0.1);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--secondary-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-details h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-details span {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* === CONTENIDO PRINCIPAL === */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: 280px;
            width: calc(100% - 280px);
        }

        .top-bar {
            background: var(--bg-white);
            padding: 1rem 2rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            background: inherit;
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            height: calc(100vh - 80px);
        }

        .page-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .page-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .notification-btn:hover {
            background: var(--bg-light);
            color: var(--primary-color);
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--error);
            color: white;
            font-size: 0.7rem;
            padding: 0.1rem 0.3rem;
            border-radius: 50%;
            min-width: 16px;
            text-align: center;
        }

        /* === MENÚ MÓVIL CORREGIDO === */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
                overflow: hidden;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: fixed;
                bottom: 0;
                left: 0;
                top: auto;
                transform: none;
                height: 70px;
                max-height: 70px;
                overflow-x: auto;
                overflow-y: hidden;
                border-radius: 0;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .sidebar-header,
            .sidebar-footer {
                display: none;
            }

            .sidebar-nav {
                display: flex;
                flex-direction: row;
                padding: 0.5rem;
                overflow-x: auto;
                overflow-y: hidden;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .sidebar-nav::-webkit-scrollbar {
                display: none;
            }

            .nav-section {
                margin-bottom: 0;
                display: flex;
                flex-direction: row;
            }

            .nav-title {
                display: none;
            }

            .nav-item {
                flex-direction: column;
                padding: 0.5rem 0.75rem;
                font-size: 0.7rem;
                min-width: 70px;
                border-left: none;
                border-top: 3px solid transparent;
                text-align: center;
                justify-content: center;
            }

            .nav-item.active {
                border-left: none;
                border-top-color: var(--primary-color);
            }

            .nav-item i {
                font-size: 1.2rem;
                margin-bottom: 0.25rem;
            }

            .nav-badge {
                margin-left: 0;
                margin-top: 0.25rem;
                position: absolute;
                top: 0;
                right: 0.5rem;
                padding: 0.15rem 0.3rem;
                font-size: 0.6rem;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                margin-bottom: 70px;
            }

            .content-area {
                height: calc(100vh - 150px);
                padding-bottom: 1rem;
            }

            .mobile-menu-btn {
                display: none;
            }

            /* Ajustes para pantallas muy pequeñas */
            @media (max-width: 480px) {
                .nav-item {
                    min-width: 65px;
                    padding: 0.5rem;
                }

                .nav-item i {
                    font-size: 1.1rem;
                }

                .nav-item span {
                    font-size: 0.65rem;
                }
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }

            .sidebar::-webkit-scrollbar {
                width: 4px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: var(--bg-light);
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: var(--text-secondary);
                border-radius: 2px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: var(--primary-color);
            }
        }

        .sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .sidebar-footer {
            margin-top: auto;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Panel Lateral Fijo -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <div class="sidebar-brand">
                        <i class="fas fa-trophy"></i>
                        <span>Evento Universitario</span>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <div class="nav-section">
                        <div class="nav-title">Principal</div>
                        <a href="{{ route('supervisor') }}" class="nav-item active">
                            <i class="fas fa-home"></i>
                            <span>Inicio</span>
                        </a>
                        <a href="{{ route('supervisor.notificaciones') }}" class="nav-item">
                            <i class="fas fa-bell"></i>
                            <span>Notificaciones</span>
                        </a>
                        <a href="{{ route('supervisor.comite') }}" class="nav-item">
                            <i class="fas fa-user-cog"></i>
                            <span>Comite</span>
                        </a>

                        <div class="nav-section">
                            <div class="nav-title">Supervisor</div>
                            <a href="{{ route('supervisor.estadisticas') }}" class="nav-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Estadisticas</span>
                            </a>
                            <a href="{{ route('supervisor.protocolos') }}" class="nav-item">
                                <i class="fas fa-network-wired"></i>
                                <span>Monitor de Protocolos</span>
                            </a>

                            <a href="{{ route('supervisor.blockchain') }}" class="nav-item">
                                <i class="fas fa-link"></i>
                                <span>Blockchain Log</span>
                            </a>
                        </div>

                        <div class="nav-section">
                            <div class="nav-title">Cuenta</div>
                            <a href="#" class="nav-item" id="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Cerrar Sesión</span>
                            </a>
                        </div>
                </nav>

                <div class="sidebar-footer">
                    <div class="user-info">
                        <div class="user-avatar">JU</div>
                        <div class="user-details">
                            <h4>Juan Pérez</h4>
                            <span>supervisor</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Contenido Principal con Scroll -->
        <main class="main-content">
            <header class="top-bar">
                <div class="page-title">
                    <h1>Principal</h1>
                </div>
                <div class="page-actions">
                    <a href="{{ route('supervisor.notificaciones') }}">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                        </button>
                    </a>
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </header>

            <div class="content-area">
                <!-- El contenido específico de cada vista irá aquí -->
                @yield('content')
            </div>
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navegación activa - EXCLUYENDO el botón de logout
            document.querySelectorAll('.nav-item').forEach(item => {
                if (!item.id || item.id !== 'logout-btn') {
                    item.addEventListener('click', function(e) {
                        // Solo aplicar si no es el botón de logout
                        if (!this.id || this.id !== 'logout-btn') {
                            // Actualizar estado activo
                            document.querySelectorAll('.nav-item').forEach(i => {
                                if (!i.id || i.id !== 'logout-btn') {
                                    i.classList.remove('active');
                                }
                            });
                            this.classList.add('active');
                        }
                    });
                }
            });

            // Manejar el botón de logout
            const logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevenir que otros eventos se disparen

                    // Enviar formulario directamente sin confirmación
                    document.getElementById('logout-form').submit();
                });
            }

            // Establecer elemento activo basado en la URL actual
            function setActiveNavItem() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-item').forEach(item => {
                    if (!item.id || item.id !== 'logout-btn') {
                        if (item.getAttribute('href') === currentPath) {
                            item.classList.add('active');
                        } else {
                            item.classList.remove('active');
                        }
                    }
                });
            }

            setActiveNavItem();
        });
    </script>
</body>

</html>
