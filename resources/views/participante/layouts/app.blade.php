<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Evento Universitario</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            overflow-x: hidden;
        }

        /* === PANEL LATERAL - ESCRITORIO === */
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
            /* Sin scroll en el panel lateral */
            overflow: hidden;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E2E8F0;
            background: linear-gradient(135deg, var(--primary-color), #003D58);
            color: white;
            flex-shrink: 0;
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

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            /* Sin scroll interno */
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .nav-section {
            margin-bottom: 1.5rem;
            flex-shrink: 0;
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
            flex-shrink: 0;
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

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #E2E8F0;
            background: var(--bg-light);
            flex-shrink: 0;
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
            /* Compensar el ancho del sidebar fijo */
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

        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
            /* Solo el contenido principal tiene scroll */
        }

        /* === MENÚ MÓVIL - SIEMPRE VISIBLE === */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
                overflow-x: hidden;
                padding-bottom: 70px;
                /* Espacio para el menú móvil fijo */
            }

            .sidebar {
                width: 100%;
                height: 70px;
                position: fixed;
                bottom: 0;
                left: 0;
                top: auto;
                transform: none;
                /* Siempre visible */
                border-radius: 0;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                overflow-x: auto;
                overflow-y: hidden;
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
                flex: 1;
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
                position: relative;
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
                position: absolute;
                top: 0.25rem;
                right: 0.5rem;
                padding: 0.15rem 0.3rem;
                font-size: 0.6rem;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                margin-bottom: 0;
            }

            .content-area {
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
            flex-shrink: 0;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Panel Lateral -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-trophy"></i>
                    <span>Evento Universitario</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-title">Principal</div>
                    <a href="{{ route('personal') }}" class="nav-item active">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                    <a href="{{ route('personal.notificaciones') }}" class="nav-item">
                        <i class="fas fa-bell"></i>
                        <span>Notificaciones</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Inscripciones</div>
                    <a href="{{ route('personal.inscripciones') }}" class="nav-item">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Mis Inscripciones</span>
                    </a>
                    <a href="{{ route('personal.disciplinas') }}" class="nav-item">
                        <i class="fas fa-running"></i>
                        <span>Disciplinas</span>
                    </a>
                    <a href="{{ route('personal.historial') }}" class="nav-item">
                        <i class="fas fa-history"></i>
                        <span>Historial</span>
                    </a>
                    <a href="{{ route('personal.tarjetas') }}" class="nav-item">
                        <i class="fas fa-credit-card"></i>
                        <span>Métodos de Pago (SET)</span>
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-title">Cuenta</div>
                    <!-- <a href="{{ route('personal.cuenta') }}" class="nav-item">
                        <i class="fas fa-user-cog"></i>
                        <span>Perfil</span>
                    </a> -->
                    <a href="#" class="nav-item" id="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        @php
                            $userId = session('user_id');
                            $userName = session('user_name');

                            // Intentar obtener la foto del usuario
                            $fotoUrl = null;
                            if ($userId) {
                                try {
                                    $fotoUrl = app('App\Http\Controllers\Auth\AuthController')->getUserPhoto($userId);
                                } catch (Exception $e) {
                                    \Log::error('Error al cargar foto de usuario: ' . $e->getMessage());
                                    $fotoUrl = null;
                                }
                            }

                            $iniciales = 'US';
                            if ($userName) {
                                $nombres = explode(' ', $userName);
                                if (count($nombres) >= 2) {
                                    $iniciales = strtoupper(substr($nombres[0], 0, 1) . substr($nombres[1], 0, 1));
                                } else {
                                    $iniciales = strtoupper(substr($userName, 0, 2));
                                }
                            }
                        @endphp

                        @if ($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto de perfil"
                                onerror="this.style.display='none'; this.parentNode.innerHTML='{{ $iniciales }}';">
                        @else
                            {{ $iniciales }}
                        @endif
                    </div>
                    <div class="user-details">
                        <h4 title="{{ $userName ?? 'Usuario' }}">
                            @php
                                $nombre = $userName ?? 'Usuario';
                                if (strlen($nombre) > 20) {
                                    $nombre = substr($nombre, 0, 17) . '...';
                                }
                            @endphp
                            {{ $nombre }}
                        </h4>
                        <span>Participante</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Contenido Principal -->
        <main class="main-content">
            <header class="top-bar">
                <div class="page-title">
                    <h1>Principal</h1>
                </div>
                <div class="page-actions">
                    <a href="{{ route('personal.notificaciones') }}">
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
