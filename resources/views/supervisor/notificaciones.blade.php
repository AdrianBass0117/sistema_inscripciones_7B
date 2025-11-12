@extends('supervisor.layouts.app')

@section('content')
    <div class="notificaciones-content">
        <div class="page-header">
            <div class="header-content">
                <h1>Notificaciones</h1>
                <p>Mantente informado sobre tus inscripciones y el evento</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" id="sendNotificationBtn">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Notificación
                </button>
                <button class="btn btn-primary" id="markAllRead">
                    <i class="fas fa-check-double"></i>
                    Marcar todas como leídas
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="fas fa-bell"></i>
                    Todas
                    <span class="filter-count">{{ $totalCount }}</span>
                </button>
                <button class="filter-tab" data-filter="unread">
                    <i class="fas fa-envelope"></i>
                    No leídas
                    <span class="filter-count">{{ $unreadCount }}</span>
                </button>
                <button class="filter-tab" data-filter="system">
                    <i class="fas fa-cog"></i>
                    Sistema
                    <span class="filter-count">{{ $systemCount }}</span>
                </button>
            </div>
        </div>

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Buscar notificaciones..." class="search-input" id="searchInput">
        </div>

        <!-- Lista de Notificaciones -->
        <div class="notifications-list" id="notificationsList">
            @foreach ($notificaciones as $notificacion)
                <div class="notification-item {{ $notificacion->leida ? 'read' : 'unread' }} {{ $notificacion->tipo === 'urgente' ? 'important' : '' }}"
                    data-type="{{ $notificacion->tipo }}" data-date="{{ $notificacion->created_at->format('Y-m-d') }}">
                    <div class="notification-indicator">
                        @if (!$notificacion->leida)
                            <div class="unread-dot"></div>
                        @endif
                        @if ($notificacion->tipo === 'urgente')
                            <div class="importance-marker"></div>
                        @endif
                    </div>

                    <div
                        class="notification-icon {{ $notificacion->tipo === 'urgente' ? 'urgent' : ($notificacion->tipo === 'recordatorio' ? 'warning' : 'info') }}">
                        <i
                            class="fas {{ $notificacion->tipo === 'urgente' ? 'fa-exclamation-triangle' : ($notificacion->tipo === 'recordatorio' ? 'fa-clock' : 'fa-info-circle') }}"></i>
                    </div>

                    <div class="notification-content">
                        <div class="notification-header">
                            <h3>{{ $notificacion->asunto }}</h3>
                            <span class="notification-time">{{ $notificacion->getTiempoTranscurrido() }}</span>
                        </div>
                        <p class="notification-message">
                            {{ $notificacion->mensaje }}
                        </p>
                        <div class="notification-meta">
                            <span
                                class="notification-category {{ $notificacion->tipo }}">{{ $notificacion->getTipoFormateado() }}</span>
                            <span
                                class="notification-destinatarios">{{ $notificacion->getDestinatariosFormateado() }}</span>
                            <span
                                class="notification-time-full">{{ $notificacion->created_at->format('d M, h:i A') }}</span>
                        </div>
                    </div>

                    <div class="notification-actions">
                        @if (!$notificacion->leida)
                            <button class="btn-action mark-read" title="Marcar como leída"
                                data-id="{{ $notificacion->id_notificacion }}">
                                <i class="fas fa-envelope-open"></i>
                            </button>
                        @else
                            <button class="btn-action mark-unread" title="Marcar como no leída"
                                data-id="{{ $notificacion->id_notificacion }}">
                                <i class="fas fa-envelope"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: {{ count($notificaciones) == 0 ? 'block' : 'none' }};">
            <div class="empty-icon">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3>No hay notificaciones</h3>
            <p>No se encontraron notificaciones con los filtros aplicados</p>
            <button class="btn btn-primary" id="resetEmptyState">
                <i class="fas fa-redo"></i>
                Mostrar todas las notificaciones
            </button>
        </div>

        <!-- Load More -->
        @if ($notificaciones->hasMorePages())
            <div class="load-more-section">
                <button class="btn btn-outline" id="loadMore" data-page="2">
                    <i class="fas fa-chevron-down"></i>
                    Cargar más notificaciones
                </button>
            </div>
        @endif
    </div>

    <!-- Modal para Enviar Notificación -->
    <div id="sendNotificationModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <div class="modal-header-content">
                    <h3><i class="fas fa-paper-plane"></i> Enviar Nueva Notificación</h3>
                    <button class="modal-close" id="closeSendModal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form id="notificationForm" action="{{ route('supervisor.notificaciones.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="notificationTitle">Asunto de la notificación *</label>
                        <input type="text" id="notificationTitle" name="asunto"
                            placeholder="Ingresa el asunto de la notificación" required>
                    </div>

                    <div class="form-group">
                        <label for="notificationMessage">Mensaje *</label>
                        <textarea id="notificationMessage" name="mensaje" placeholder="Escribe el mensaje de la notificación..." rows="5"
                            required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="notificationType">Tipo de notificación</label>
                            <select id="notificationType" name="tipo">
                                <option value="general">Información General</option>
                                <option value="urgente">Urgente</option>
                                <option value="recordatorio">Recordatorio</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="notificationAudience">Destinatarios</label>
                            <select id="notificationAudience" name="destinatarios">
                                <option value="todos">Todos los usuarios</option>
                                <option value="comite">Solo comité</option>
                                <option value="personal">Solo personal</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelSend">Cancelar</button>
                <button class="btn btn-primary" id="confirmSend">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Notificación
                </button>
            </div>
        </div>
    </div>

    <style>
        /* === ESTILOS NOTIFICACIONES === */
        .notificaciones-content {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 1rem;
            overflow-x: hidden;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 1rem 0;
        }

        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2D3748;
            margin-bottom: 0.5rem;
        }

        .header-content p {
            color: #718096;
            font-size: 1.1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Filtros */
        .filters-section {
            margin-bottom: 2rem;
        }

        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-tab {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: white;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            color: #718096;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .filter-tab:hover {
            border-color: #004F6E;
            color: #004F6E;
            transform: translateY(-2px);
        }

        .filter-tab.active {
            background: #004F6E;
            border-color: #004F6E;
            color: white;
        }

        .filter-count {
            background: #00AA8B;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        /* Búsqueda */
        .search-box {
            position: relative;
            margin: 2rem 0;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #718096;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #004F6E;
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
            transform: translateY(-1px);
        }

        /* Lista de Notificaciones */
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Items de Notificación */
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .notification-item.unread {
            background: linear-gradient(135deg, white 0%, rgba(0, 79, 110, 0.03) 100%);
            border-left-color: #004F6E;
        }

        .notification-item.important {
            border-left-color: #D69E2E;
            background: linear-gradient(135deg, white 0%, rgba(214, 158, 46, 0.05) 100%);
        }

        .notification-item.unread.important {
            border-left-color: #E53E3E;
            background: linear-gradient(135deg, white 0%, rgba(229, 62, 62, 0.05) 100%);
        }

        /* Indicadores */
        .notification-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
            padding-top: 0.5rem;
        }

        .unread-dot {
            width: 12px;
            height: 12px;
            background: #004F6E;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .importance-marker {
            width: 4px;
            height: 20px;
            background: #D69E2E;
            border-radius: 2px;
        }

        .notification-item.unread.important .importance-marker {
            background: #E53E3E;
        }

        /* Iconos */
        .notification-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .notification-icon.urgent {
            background: rgba(229, 62, 62, 0.1);
            color: #E53E3E;
        }

        .notification-icon.warning {
            background: rgba(214, 158, 46, 0.1);
            color: #D69E2E;
        }

        .notification-icon.info {
            background: rgba(0, 119, 182, 0.1);
            color: #0077B6;
        }

        /* Contenido */
        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            gap: 1rem;
        }

        .notification-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2D3748;
            margin: 0;
        }

        .notification-time {
            color: #718096;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .notification-message {
            color: #718096;
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }

        .notification-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .notification-category {
            padding: 0.25rem 0.75rem;
            background: #F7FAFC;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            color: #718096;
        }

        .notification-category.urgente {
            background: rgba(229, 62, 62, 0.1);
            color: #E53E3E;
        }

        .notification-category.recordatorio {
            background: rgba(214, 158, 46, 0.1);
            color: #D69E2E;
        }

        .notification-category.general {
            background: rgba(0, 119, 182, 0.1);
            color: #0077B6;
        }

        .notification-time-full {
            color: #718096;
            font-size: 0.8rem;
        }

        /* Acciones */
        .notification-actions {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #718096;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
        }

        .btn-action:hover {
            background: #F7FAFC;
            color: #004F6E;
            transform: scale(1.1);
        }

        /* Estados Vacíos */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .empty-icon {
            font-size: 4rem;
            color: #718096;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #2D3748;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        /* Load More */
        .load-more-section {
            text-align: center;
            margin: 2rem 0;
        }

        /* Botones */
        .btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #004F6E;
            color: white;
        }

        .btn-primary:hover {
            background: #003D58;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 79, 110, 0.3);
        }

        .btn-secondary {
            background: #F7FAFC;
            color: #2D3748;
            border: 2px solid #E2E8F0;
        }

        .btn-secondary:hover {
            background: #EDF2F7;
            border-color: #CBD5E1;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: #2D3748;
            border: 2px solid #E2E8F0;
        }

        .btn-outline:hover {
            border-color: #004F6E;
            color: #004F6E;
            transform: translateY(-2px);
        }

        /* Modal */
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
            overflow-y: auto;
            padding: 20px 0;
        }

        .modal-content {
            background-color: white;
            margin: auto;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-content.large {
            max-width: 600px;
            max-height: 90vh;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #004F6E, #003d58);
            color: white;
            flex-shrink: 0;
        }

        .modal-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .modal-header h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.15);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .modal-body {
            padding: 2rem;
            background: #f8fafc;
            flex: 1;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e2e8f0;
            background: white;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            border-radius: 0 0 16px 16px;
            flex-shrink: 0;
        }

        /* Formularios en modal */
        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2D3748;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: white;
            color: #2D3748;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #004F6E;
            box-shadow: 0 0 0 4px rgba(0, 79, 110, 0.15);
            transform: translateY(-2px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.5;
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
            padding-right: 3rem;
        }

        /* Animaciones */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

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

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .notificaciones-content {
                padding: 0 0.5rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
                flex-direction: column;
            }

            .filter-tabs {
                justify-content: center;
            }

            .filter-tab {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
                flex: 1;
                justify-content: center;
                text-align: center;
            }

            .search-box {
                min-width: auto;
            }

            .notification-item {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .notification-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .notification-actions {
                width: 100%;
                justify-content: center;
            }

            .modal {
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                width: 95%;
                margin: 0;
            }

            .modal-content.large {
                max-height: 95vh;
            }

            .modal-header {
                padding: 1.25rem 1.5rem;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .modal-footer {
                flex-direction: column;
                gap: 0.75rem;
                padding: 1.25rem 1.5rem;
            }

            .modal-footer .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .notificaciones-content {
                padding: 0 0.25rem;
            }

            .header-actions .btn {
                font-size: 0.75rem;
                padding: 0.4rem 0.8rem;
            }

            .notification-header h3 {
                font-size: 1rem;
            }

            .notification-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .filter-tabs {
                flex-direction: column;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos principales
            const filterTabs = document.querySelectorAll('.filter-tab');
            const markAllReadBtn = document.getElementById('markAllRead');
            const notificationsList = document.getElementById('notificationsList');
            const emptyState = document.getElementById('emptyState');
            const resetEmptyStateBtn = document.getElementById('resetEmptyState');
            const loadMoreBtn = document.getElementById('loadMore');
            const sendNotificationBtn = document.getElementById('sendNotificationBtn');
            const sendNotificationModal = document.getElementById('sendNotificationModal');
            const searchInput = document.getElementById('searchInput');

            let currentFilter = 'all';

            // Filtrado por pestañas
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Actualizar pestaña activa
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    currentFilter = filter;
                    applyFilters();
                });
            });

            // Búsqueda en tiempo real
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    applyFilters();
                });
            }

            // Marcar todas como leídas
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function() {
                    if (confirm(
                            '¿Estás seguro de que quieres marcar todas las notificaciones como leídas?')) {
                        fetch('{{ route('supervisor.notificaciones.markAllRead') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Actualizar la interfaz
                                    document.querySelectorAll('.notification-item.unread').forEach(
                                        notification => {
                                            notification.classList.remove('unread');
                                            notification.classList.add('read');

                                            const markReadBtn = notification.querySelector(
                                                '.mark-read');
                                            if (markReadBtn) {
                                                markReadBtn.classList.remove('mark-read');
                                                markReadBtn.classList.add('mark-unread');
                                                markReadBtn.innerHTML =
                                                    '<i class="fas fa-envelope"></i>';
                                                markReadBtn.title = 'Marcar como no leída';
                                            }

                                            const unreadDot = notification.querySelector(
                                                '.unread-dot');
                                            if (unreadDot) {
                                                unreadDot.remove();
                                            }
                                        });

                                    updateFilterCounts();
                                    showNotification('Todas las notificaciones marcadas como leídas',
                                        'success');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showNotification('Error al marcar las notificaciones', 'warning');
                            });
                    }
                });
            }

            // Marcar como leído/no leído individual
            if (notificationsList) {
                notificationsList.addEventListener('click', function(e) {
                    if (e.target.closest('.mark-read')) {
                        const btn = e.target.closest('.mark-read');
                        const notificationId = btn.getAttribute('data-id');
                        const notification = btn.closest('.notification-item');

                        markAsRead(notificationId, notification, btn);
                    } else if (e.target.closest('.mark-unread')) {
                        const btn = e.target.closest('.mark-unread');
                        const notificationId = btn.getAttribute('data-id');
                        const notification = btn.closest('.notification-item');

                        markAsUnread(notificationId, notification, btn);
                    }
                });
            }

            function markAsRead(notificationId, notification, btn) {
                fetch('{{ route('supervisor.notificaciones.markAsRead') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_notificacion: notificationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notification.classList.remove('unread');
                            notification.classList.add('read');

                            btn.classList.remove('mark-read');
                            btn.classList.add('mark-unread');
                            btn.innerHTML = '<i class="fas fa-envelope"></i>';
                            btn.title = 'Marcar como no leída';

                            const unreadDot = notification.querySelector('.unread-dot');
                            if (unreadDot) {
                                unreadDot.remove();
                            }

                            updateFilterCounts();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al marcar como leída', 'warning');
                    });
            }

            function markAsUnread(notificationId, notification, btn) {
                fetch('{{ route('supervisor.notificaciones.markAsUnread') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_notificacion: notificationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notification.classList.remove('read');
                            notification.classList.add('unread');

                            btn.classList.remove('mark-unread');
                            btn.classList.add('mark-read');
                            btn.innerHTML = '<i class="fas fa-envelope-open"></i>';
                            btn.title = 'Marcar como leída';

                            const indicator = notification.querySelector('.notification-indicator');
                            if (indicator && !indicator.querySelector('.unread-dot')) {
                                const dot = document.createElement('div');
                                dot.className = 'unread-dot';
                                indicator.prepend(dot);
                            }

                            updateFilterCounts();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al marcar como no leída', 'warning');
                    });
            }

            // Funcionalidad del modal de enviar notificación
            if (sendNotificationBtn && sendNotificationModal) {
                const closeSendModal = document.getElementById('closeSendModal');
                const cancelSend = document.getElementById('cancelSend');
                const confirmSend = document.getElementById('confirmSend');
                const notificationForm = document.getElementById('notificationForm');

                // Abrir modal
                sendNotificationBtn.addEventListener('click', function() {
                    sendNotificationModal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });

                // Cerrar modal
                function closeSendNotificationModal() {
                    sendNotificationModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                    if (notificationForm) notificationForm.reset();
                }

                if (closeSendModal) {
                    closeSendModal.addEventListener('click', closeSendNotificationModal);
                }

                if (cancelSend) {
                    cancelSend.addEventListener('click', closeSendNotificationModal);
                }

                // Cerrar modal al hacer click fuera
                sendNotificationModal.addEventListener('click', function(e) {
                    if (e.target === sendNotificationModal) {
                        closeSendNotificationModal();
                    }
                });

                // Enviar notificación
                if (confirmSend) {
                    confirmSend.addEventListener('click', function() {
                        const titleInput = document.getElementById('notificationTitle');
                        const messageInput = document.getElementById('notificationMessage');

                        if (!titleInput || !messageInput) {
                            showNotification('Error: Campos no encontrados', 'warning');
                            return;
                        }

                        const title = titleInput.value.trim();
                        const message = messageInput.value.trim();

                        // Validaciones
                        if (!title) {
                            showNotification('Por favor, ingresa un asunto para la notificación',
                                'warning');
                            titleInput.focus();
                            return;
                        }

                        if (!message) {
                            showNotification('Por favor, ingresa el mensaje de la notificación', 'warning');
                            messageInput.focus();
                            return;
                        }

                        // Enviar formulario
                        notificationForm.submit();
                    });
                }
            }

            // Reset empty state
            if (resetEmptyStateBtn) {
                resetEmptyStateBtn.addEventListener('click', function() {
                    filterTabs.forEach(tab => tab.classList.remove('active'));
                    const allTab = document.querySelector('[data-filter="all"]');
                    if (allTab) allTab.classList.add('active');
                    currentFilter = 'all';
                    if (searchInput) searchInput.value = '';
                    applyFilters();
                });
            }

            // Load more
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const page = this.getAttribute('data-page');
                    const originalText = this.innerHTML;

                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
                    this.disabled = true;

                    fetch(`{{ route('supervisor.notificaciones') }}?page=${page}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.html) {
                                notificationsList.insertAdjacentHTML('beforeend', data.html);
                                this.setAttribute('data-page', parseInt(page) + 1);

                                if (!data.hasMore) {
                                    this.style.display = 'none';
                                }
                            }

                            this.innerHTML = originalText;
                            this.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.innerHTML = originalText;
                            this.disabled = false;
                            showNotification('Error al cargar más notificaciones', 'warning');
                        });
                });
            }

            function applyFilters() {
                let visibleCount = 0;
                const notifications = document.querySelectorAll('.notification-item');
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';

                notifications.forEach(notification => {
                    const type = notification.getAttribute('data-type');
                    const isUnread = notification.classList.contains('unread');
                    const isImportant = notification.classList.contains('important');
                    const title = notification.querySelector('h3').textContent.toLowerCase();
                    const message = notification.querySelector('.notification-message').textContent
                        .toLowerCase();

                    let matchesFilter = false;
                    let matchesSearch = true;

                    // Aplicar filtro de tipo
                    switch (currentFilter) {
                        case 'all':
                            matchesFilter = true;
                            break;
                        case 'unread':
                            matchesFilter = isUnread;
                            break;
                        case 'system':
                            matchesFilter = type === 'system';
                            break;
                        default:
                            matchesFilter = true;
                    }

                    // Aplicar filtro de búsqueda
                    if (searchTerm) {
                        matchesSearch = title.includes(searchTerm) || message.includes(searchTerm);
                    }

                    // Mostrar/ocultar notificación
                    if (matchesFilter && matchesSearch) {
                        notification.style.display = 'flex';
                        notification.style.animation = 'slideIn 0.3s ease';
                        visibleCount++;
                    } else {
                        notification.style.display = 'none';
                    }
                });

                // Mostrar empty state si no hay resultados
                if (emptyState && notificationsList && loadMoreBtn) {
                    if (visibleCount === 0) {
                        emptyState.style.display = 'block';
                        notificationsList.style.display = 'none';
                        if (loadMoreBtn) loadMoreBtn.style.display = 'none';
                    } else {
                        emptyState.style.display = 'none';
                        notificationsList.style.display = 'flex';
                        if (loadMoreBtn) loadMoreBtn.style.display = 'block';
                    }
                }
            }

            function updateFilterCounts() {
                // Los contadores se actualizan automáticamente desde el backend
                // Esta función se mantiene para cambios en el frontend
            }

            function showNotification(message, type) {
                // Tu función existente para mostrar notificaciones toast
                const notification = document.createElement('div');
                notification.className = `notification-toast ${type}`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'info'}-circle"></i>
                    <span>${message}</span>
                `;

                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'success' ? 'var(--success)' : type === 'warning' ? 'var(--warning)' : 'var(--accent-color)'};
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

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Inicializar filtros
            applyFilters();
        });
    </script>
@endsection
