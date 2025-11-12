@extends('comite.layouts.app')

@section('content')
<div class="dashboard-content">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-shield-check"></i> Verificación de Constancias</h1>
            <p>Sistema de verificación oficial de constancias de inscripción</p>
        </div>
        <div class="header-actions">
        </div>
    </div>

    <!-- Panel de Búsqueda -->
    <div class="verification-panel">
        <div class="panel-header">
            <h2><i class="fas fa-search"></i> Verificar Constancia</h2>
            <p>Ingresa el código de verificación de la constancia</p>
        </div>

        <div class="search-form">
            <div class="input-group">
                <div class="input-icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <input type="text"
                       id="codigoVerificacion"
                       placeholder="Ingresa el código de 12 caracteres (ej: A1B2C3D4E5F6)"
                       maxlength="12"
                       class="form-input">
                <button class="btn-verify" id="btnVerificar">
                    <i class="fas fa-search"></i>
                    Verificar
                </button>
            </div>
            <div class="input-hint">
                <i class="fas fa-info-circle"></i>
                El código de verificación se encuentra en la parte inferior de cada constancia
            </div>
        </div>
    </div>

    <!-- Resultados de Verificación -->
    <div class="verification-results" id="verificationResults" style="display: none;">
        <div class="result-card" id="resultCard">
            <!-- Los resultados se cargarán aquí dinámicamente -->
        </div>
    </div>

</div>

<style>
    .verification-panel {
        background: var(--bg-white);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid #e2e8f0;
    }

    .panel-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .panel-header h2 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .panel-header p {
        color: var(--text-secondary);
        margin: 0;
    }

    .search-form {
        max-width: 600px;
        margin: 0 auto;
    }

    .input-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .input-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        background: var(--bg-light);
        border: 2px solid #e2e8f0;
        border-radius: 8px 0 0 8px;
        color: var(--text-secondary);
    }

    .form-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0 8px 8px 0;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 79, 110, 0.1);
    }

    .btn-verify {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-verify:hover {
        background: #003d58;
        transform: translateY(-1px);
    }

    .btn-verify:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .input-hint {
        font-size: 0.875rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .verification-results {
        margin-bottom: 2rem;
    }

    .result-card {
        background: var(--bg-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border-left: 6px solid #e2e8f0;
    }

    .result-card.valid {
        border-left-color: var(--success);
    }

    .result-card.invalid {
        border-left-color: var(--error);
    }

    .result-card.expired {
        border-left-color: var(--warning);
    }

    .result-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .result-icon {
        font-size: 2.5rem;
    }

    .result-icon.valid {
        color: var(--success);
    }

    .result-icon.invalid {
        color: var(--error);
    }

    .result-icon.expired {
        color: var(--warning);
    }

    .result-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
    }

    .result-message {
        color: var(--text-secondary);
        margin: 0;
    }

    .constancia-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .info-value {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 500;
    }

    .verification-details {
        background: var(--bg-light);
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-white);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.valid {
        background: rgba(56, 161, 105, 0.1);
        color: var(--success);
    }

    .stat-icon.invalid {
        background: rgba(229, 62, 62, 0.1);
        color: var(--error);
    }

    .stat-icon.expired {
        background: rgba(214, 158, 46, 0.1);
        color: var(--warning);
    }

    .stat-icon.total {
        background: rgba(0, 79, 110, 0.1);
        color: var(--primary-color);
    }

    .stat-number {
        display: block;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .verification-history {
        background: var(--bg-white);
        border-radius: 12px;
        padding: 2rem;
        box-shadow: var(--shadow-md);
    }

    .section-header {
        margin-bottom: 1.5rem;
    }

    .section-header h3 {
        color: var(--text-primary);
        margin: 0;
        font-size: 1.25rem;
    }

    .history-table {
        min-height: 200px;
    }

    .table-loading {
        text-align: center;
        padding: 3rem;
        color: var(--text-secondary);
    }

    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.3s ease;
    }

    .history-item:hover {
        background: var(--bg-light);
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .history-code {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--text-primary);
    }

    .history-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-valid {
        background: rgba(56, 161, 105, 0.1);
        color: var(--success);
    }

    .status-invalid {
        background: rgba(229, 62, 62, 0.1);
        color: var(--error);
    }

    .status-expired {
        background: rgba(214, 158, 46, 0.1);
        color: var(--warning);
    }

    .history-date {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .input-group {
            flex-direction: column;
        }

        .input-icon {
            border-radius: 8px 8px 0 0;
            width: 100%;
            padding: 0.5rem;
        }

        .form-input {
            border-radius: 0 0 8px 8px;
        }

        .constancia-info {
            grid-template-columns: 1fr;
        }

        .quick-stats {
            grid-template-columns: 1fr;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const btnVerificar = document.getElementById('btnVerificar');
    const codigoInput = document.getElementById('codigoVerificacion');
    const resultsContainer = document.getElementById('verificationResults');
    const resultCard = document.getElementById('resultCard');
    const historyTable = document.getElementById('historyTable');

    // Cargar estadísticas iniciales
    cargarEstadisticas();

    // Evento para verificar constancia
    btnVerificar.addEventListener('click', verificarConstancia);
    codigoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verificarConstancia();
        }
    });

    // Convertir a mayúsculas automáticamente
    codigoInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    async function verificarConstancia() {
        const codigo = codigoInput.value.trim();

        if (!codigo) {
            showNotification('Por favor ingresa un código de verificación', 'warning');
            return;
        }

        if (codigo.length !== 12) {
            showNotification('El código debe tener exactamente 12 caracteres', 'warning');
            return;
        }

        btnVerificar.disabled = true;
        btnVerificar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';

        try {

            const response = await fetch(`/api/constancias/verificar/${codigo}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            // VERIFICACIÓN CORREGIDA - usar data.valida en lugar de data.success
            if (data.success === true && data.valida === true) {
                mostrarResultadoValido(data);
                agregarAlHistorial(codigo, 'valid', data.constancia);
                showNotification('Constancia verificada correctamente', 'success');
            } else {
                mostrarResultadoInvalido(data);
                agregarAlHistorial(codigo, data.constancia ? 'expired' : 'invalid', null);
                showNotification('Constancia no válida', 'error');
            }

            // Actualizar estadísticas
            cargarEstadisticas();

        } catch (error) {
            console.error('Error en verificación:', error);
            mostrarResultadoInvalido({
                error: 'Error de conexión: ' + error.message
            });
            showNotification('Error al verificar la constancia', 'error');
        } finally {
            btnVerificar.disabled = false;
            btnVerificar.innerHTML = '<i class="fas fa-search"></i> Verificar';
        }
    }

    function mostrarResultadoValido(data) {
        const { constancia, inscripcion } = data;

        resultCard.className = 'result-card valid';
        resultCard.innerHTML = `
            <div class="result-header">
                <div class="result-icon valid">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h3 class="result-title">Constancia Válida</h3>
                    <p class="result-message">La constancia ha sido verificada correctamente</p>
                </div>
            </div>

            <div class="constancia-info">
                <div class="info-item">
                    <span class="info-label">Número de Constancia</span>
                    <span class="info-value">${constancia.numero_constancia}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Código de Verificación</span>
                    <span class="info-value" style="font-family: 'Courier New', monospace;">${constancia.codigo_verificacion}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Emisión</span>
                    <span class="info-value">${constancia.fecha_emision}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Válida hasta</span>
                    <span class="info-value">${constancia.fecha_vencimiento}</span>
                </div>
            </div>

            <div class="verification-details">
                <h4>Información del Participante</h4>
                <div class="constancia-info">
                    <div class="info-item">
                        <span class="info-label">Nombre Completo</span>
                        <span class="info-value">${inscripcion.participante}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Disciplina</span>
                        <span class="info-value">${inscripcion.disciplina}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Categoría</span>
                        <span class="info-value">${inscripcion.categoria}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Género</span>
                        <span class="info-value">${inscripcion.genero}</span>
                    </div>
                </div>
            </div>

            <div class="result-actions">

            </div>
        `;

        resultsContainer.style.display = 'block';

        // Scroll suave a los resultados
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function mostrarResultadoInvalido(data) {
        resultCard.className = 'result-card invalid';
        resultCard.innerHTML = `
            <div class="result-header">
                <div class="result-icon invalid">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <h3 class="result-title">Constancia Inválida</h3>
                    <p class="result-message">${data.error || 'No se pudo verificar la constancia'}</p>
                </div>
            </div>
            <div class="constancia-info">
                <div class="info-item">
                    <span class="info-label">Código Ingresado</span>
                    <span class="info-value" style="font-family: 'Courier New', monospace;">${codigoInput.value}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha de Verificación</span>
                    <span class="info-value">${new Date().toLocaleString('es-MX')}</span>
                </div>
            </div>
            <div class="verification-details">
                <p style="color: var(--text-secondary); margin: 0;">
                    <i class="fas fa-info-circle"></i>
                    Si crees que esto es un error, verifica que el código sea correcto o contacta al administrador del sistema.
                </p>
            </div>
        `;

        resultsContainer.style.display = 'block';
        resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    async function cargarEstadisticas() {
        // En una implementación real, aquí harías una petición al servidor
        // Por ahora simulamos datos
        const stats = await obtenerEstadisticas();
    }

    function agregarAlHistorial(codigo, estado, constancia) {

        // Aquí podrías guardar en localStorage o enviar al servidor
        // Por ahora solo mostramos en consola
    }

    function showNotification(message, type) {
        // Eliminar notificaciones existentes
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notif => notif.remove());

        const notification = document.createElement('div');
        notification.className = `notification-toast ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;

        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'var(--success)' : type === 'warning' ? 'var(--warning)' : 'var(--error)'};
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
        }, 5000);
    }

    // Agregar estilos CSS para las animaciones
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

    // Función simulada para estadísticas
    async function obtenerEstadisticas() {
        return {
            validas: Math.floor(Math.random() * 50) + 10,
            invalidas: Math.floor(Math.random() * 20) + 1,
            expiradas: Math.floor(Math.random() * 15) + 1,
            totales: Math.floor(Math.random() * 100) + 30
        };
    }
});
</script>
@endsection
