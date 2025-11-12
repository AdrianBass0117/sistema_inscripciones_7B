@extends('participante.layouts.app')

@section('content')
    <div class="notificaciones-content">
        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <h1>Notificaciones</h1>
                <p>Mantente informado sobre tus inscripciones y el evento</p>
            </div>
            <div class="header-actions">
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
                <button class="filter-tab" data-filter="urgent">
                    <i class="fas fa-exclamation-circle"></i>
                    Urgentes
                    <span class="filter-count">{{ $urgentCount ?? 0 }}</span>
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
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .header-content p {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Filtros */
        .filters-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            gap: 1rem;
            flex-wrap: wrap;
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
            background: var(--bg-white);
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-tab:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .filter-tab.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .filter-count {
            background: var(--secondary-color);
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
            min-width: 300px;
            margin: 2rem 0;
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
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
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .notification-item.unread {
            background: linear-gradient(135deg, var(--bg-white) 0%, rgba(0, 79, 110, 0.03) 100%);
            border-left-color: var(--primary-color);
        }

        .notification-item.important {
            border-left-color: var(--warning);
            background: linear-gradient(135deg, var(--bg-white) 0%, rgba(214, 158, 46, 0.05) 100%);
        }

        .notification-item.unread.important {
            border-left-color: var(--error);
            background: linear-gradient(135deg, var(--bg-white) 0%, rgba(229, 62, 62, 0.05) 100%);
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
            background: var(--primary-color);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .importance-marker {
            width: 4px;
            height: 20px;
            background: var(--warning);
            border-radius: 2px;
        }

        .notification-item.unread.important .importance-marker {
            background: var(--error);
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
            color: var(--error);
        }

        .notification-icon.warning {
            background: rgba(214, 158, 46, 0.1);
            color: var(--warning);
        }

        .notification-icon.info {
            background: rgba(0, 119, 182, 0.1);
            color: var(--accent-color);
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
            color: var(--text-primary);
            margin: 0;
        }

        .notification-time {
            color: var(--text-secondary);
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .notification-message {
            color: var(--text-secondary);
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
            background: var(--bg-light);
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .notification-time-full {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* Acciones */
        .notification-actions {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }

        .btn-action {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border: 2px solid #E2E8F0;
            border-radius: 8px;
            background: var(--bg-white);
            color: var(--text-primary);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            min-width: 40px;
            justify-content: center;
        }

        .btn-action:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Estados Vacíos */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
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
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #003D58;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid #E2E8F0;
        }

        .btn-outline:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
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
            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .filters-section {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-tabs {
                justify-content: center;
            }

            .search-box {
                min-width: auto;
            }

            .notification-item {
                padding: 1rem;
                margin: 0 -0.5rem;
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

            .btn-action {
                flex: 1;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .filter-tabs {
                flex-direction: column;
            }

            .filter-tab {
                justify-content: center;
            }

            .notification-meta {
                flex-direction: column;
                gap: 0.5rem;
            }

            .notification-actions {
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
                        fetch('{{ route('personal.notificaciones.markAllRead') }}', {
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
                fetch('{{ route('personal.notificaciones.markAsRead') }}', {
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
                fetch('{{ route('personal.notificaciones.markAsUnread') }}', {
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

                    fetch(`{{ route('personal.notificaciones') }}?page=${page}`)
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
                    const isUrgent = notification.classList.contains('important');
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
                        case 'urgent':
                            matchesFilter = isUrgent;
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
            }

            function showNotification(message, type) {
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
