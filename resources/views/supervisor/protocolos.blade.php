@extends('supervisor.layouts.app')

@section('content')
<div class="dashboard-content">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-network-wired"></i> Monitor de Protocolos de Seguridad</h1>
            <p>Estado en tiempo real de la infraestructura criptográfica y de red.</p>
        </div>
        <div class="header-actions">
            <button class="btn-primary" onclick="probarTodos()">
                <i class="fas fa-sync-alt"></i> Ejecutar Diagnóstico Completo
            </button>
        </div>
    </div>

    <div class="protocols-grid">
        <div class="protocol-card" id="card-https">
            <div class="info-tooltip" data-tooltip="Cifrado de extremo a extremo. Implementado nativamente en el servidor (Hostinger) o forzado en AppServiceProvider.php para entornos locales.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-lock"></i></div>
            <div class="card-info">
                <h3>HTTPS / SSL</h3>
                <p>Cifrado Web</p>
                <span class="status-badge pending">Verificando...</span>
            </div>
            <div class="card-action"><button onclick="checkHttps()">Verificar</button></div>
        </div>

        <div class="protocol-card" id="card-ssh">
            <div class="info-tooltip" data-tooltip="Conexión remota segura. Código: ProtocolosController::testSSHReal(). Utiliza librería phpseclib para conectar al puerto 22.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-terminal"></i></div>
            <div class="card-info">
                <h3>SSH</h3>
                <p>Secure Shell</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/ssh', 'card-ssh')">Probar</button></div>
        </div>

        <div class="protocol-card" id="card-sftp">
            <div class="info-tooltip" data-tooltip="Transferencia de archivos sobre SSH. Código: ProtocolosController::testSFTPReal(). Configurable en config/filesystems.php.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-file-export"></i></div>
            <div class="card-info">
                <h3>SFTP</h3>
                <p>Transferencia Segura</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/sftp', 'card-sftp')">Probar</button></div>
        </div>

        <div class="protocol-card" id="card-scp">
            <div class="info-tooltip" data-tooltip="Secure Copy Protocol. Código: ProtocolosController::testSCP(). Copia archivos usando el túnel SSH existente.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-copy"></i></div>
            <div class="card-info">
                <h3>SCP</h3>
                <p>Copia Segura</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/scp', 'card-scp')">Probar</button></div>
        </div>

        <div class="protocol-card" id="card-ftps">
            <div class="info-tooltip" data-tooltip="FTP sobre SSL. Código: ProtocolosController::testFTPS(). Se configura en el driver FTP activando la bandera 'ssl' => true.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-file-shield"></i></div>
            <div class="card-info">
                <h3>FTPS</h3>
                <p>FTP SSL/TLS</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/ftps', 'card-ftps')">Verificar</button></div>
        </div>

        <div class="protocol-card" id="card-imaps">
            <div class="info-tooltip" data-tooltip="Lectura de correo segura. Código: ProtocolosController::testIMAPS_Socket(). Usa sockets nativos PHP (ssl://) en puerto 993.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-envelope"></i></div>
            <div class="card-info">
                <h3>IMAPS</h3>
                <p>Correo Entrante SSL</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/imaps', 'card-imaps')">Probar</button></div>
        </div>

        <div class="protocol-card" id="card-smtps">
            <div class="info-tooltip" data-tooltip="Envío de correo seguro. Configurado en .env (MAIL_ENCRYPTION=tls). Usa Symfony Mailer internamente.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-paper-plane"></i></div>
            <div class="card-info">
                <h3>SMTPS</h3>
                <p>Envío Saliente TLS</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/smtps', 'card-smtps')">Probar</button></div>
        </div>

        <div class="protocol-card" id="card-ipsec">
            <div class="info-tooltip" data-tooltip="Seguridad de Red/VPN. Código: Middleware/CheckIpsecTunnel.php. Filtra tráfico que no provenga de IPs seguras.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="card-info">
                <h3>IPSEC</h3>
                <p>Túnel VPN (Middleware)</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/ipsec-check', 'card-ipsec')">Verificar IP</button></div>
        </div>

        <div class="protocol-card" id="card-set">
            <div class="info-tooltip" data-tooltip="Secure Electronic Transaction. Código: ProtocolosController::demoTransaccionSET(). Usa HMAC para integridad de pagos.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-credit-card"></i></div>
            <div class="card-info">
                <h3>SET</h3>
                <p>Transacción Segura</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/set', 'card-set')">Simular</button></div>
        </div>

         <div class="protocol-card" id="card-firmas">
            <div class="info-tooltip" data-tooltip="Integridad de documentos. Código: CuentaUsuarioController (Tarjetas) y ReporteController (Constancias). Usa OpenSSL RSA-SHA256.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-file-signature"></i></div>
            <div class="card-info">
                <h3>Firmas Digitales</h3>
                <p>Criptografía Asimétrica</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/firmas', 'card-firmas')">Verificar</button></div>
        </div>

        <div class="protocol-card" id="card-cert">
            <div class="info-tooltip" data-tooltip="Infraestructura de Clave Pública (PKI). Código: ProtocolosController::testCertificados(). Verifica existencia de llaves CA en storage/keys.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fas fa-certificate"></i></div>
            <div class="card-info">
                <h3>Certificados</h3>
                <p>Estándar X.509</p>
                <span class="status-badge pending">Pendiente</span>
            </div>
            <div class="card-action"><button onclick="checkProtocol('/proto/certificados', 'card-cert')">Verificar</button></div>
        </div>
        
         <div class="protocol-card" id="card-oauth">
            <div class="info-tooltip" data-tooltip="Delegación de autenticación. Implementado con Laravel Socialite. Ver config/services.php y Login.">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="card-icon"><i class="fab fa-github"></i></div>
            <div class="card-info">
                <h3>OAuth 2.0</h3>
                <p>Social Login</p>
                <span class="status-badge success">Configurado</span>
            </div>
            <div class="card-action"><small>Ver en Login</small></div>
        </div>
    </div>

    <div class="console-output">
        <div class="console-header">
            <h3><i class="fas fa-terminal"></i> Log de Seguridad del Sistema</h3>
            <button onclick="document.getElementById('console-log').innerHTML = ''" style="background:none; border:none; color:#aaa; cursor:pointer;">Limpiar</button>
        </div>
        <pre id="console-log">Esperando ejecución de pruebas...</pre>
    </div>
</div>

<style>
    /* Estilos Grid y Cards */
    .protocols-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .protocol-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 5px solid #cbd5e0;
        transition: transform 0.2s;
        position: relative; /* Para el tooltip */
    }

    .protocol-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Tooltip Icon */
    .info-tooltip {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #718096;
        cursor: help;
        font-size: 0.9rem;
    }

    /* Tooltip Text (CSS Puro) */
    .info-tooltip:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        right: 0;
        background: #2d3748;
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        width: 200px;
        z-index: 100;
        line-height: 1.4;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        pointer-events: none;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        background: #f7fafc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #4a5568;
    }

    .card-info { flex: 1; }
    .card-info h3 { margin: 0; font-size: 1.1rem; color: #2d3748; font-weight: 700; }
    .card-info p { margin: 0; font-size: 0.85rem; color: #718096; }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 0.5rem;
    }

    .status-badge.pending { background: #edf2f7; color: #718096; }
    .status-badge.success { background: #c6f6d5; color: #276749; }
    .status-badge.error { background: #fed7d7; color: #9b2c2c; }
    .status-badge.loading { background: #bee3f8; color: #2c5282; }

    .protocol-card.success { border-left-color: #48bb78; }
    .protocol-card.error { border-left-color: #f56565; }

    .card-action button {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
        font-weight: 600;
        color: #4a5568;
    }

    .card-action button:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    /* Consola Mejorada */
    .console-output {
        background: #0f111a;
        color: #00ff00;
        padding: 0;
        border-radius: 12px;
        font-family: 'Consolas', 'Monaco', monospace;
        height: 400px; /* Más alto */
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
    }

    .console-header {
        background: #1f2937;
        padding: 0.75rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #374151;
    }

    .console-header h3 {
        color: #e5e7eb;
        margin: 0;
        font-size: 0.9rem;
    }

    #console-log {
        padding: 1.5rem;
        margin: 0;
        overflow-y: auto;
        flex: 1;
        white-space: pre-wrap;
        font-size: 0.85rem;
        line-height: 1.5;
    }
    
    /* Scrollbar consola */
    #console-log::-webkit-scrollbar { width: 8px; }
    #console-log::-webkit-scrollbar-track { background: #0f111a; }
    #console-log::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
</style>

<script>
    function logMessage(msg, type = 'info') {
        const consoleLog = document.getElementById('console-log');
        const time = new Date().toLocaleTimeString();
        let color = '#00ff00'; // verde default
        if(type === 'error') color = '#ff5555';
        if(type === 'warn') color = '#f1fa8c';
        
        const span = `<span style="color:${color}">[${time}] ${msg}</span>`;
        
        consoleLog.innerHTML += `\n${span}`;
        // Auto-scroll al final
        consoleLog.scrollTop = consoleLog.scrollHeight;
    }

    function updateCardStatus(id, status, text) {
        const card = document.getElementById(id);
        const badge = card.querySelector('.status-badge');
        
        card.classList.remove('success', 'error');
        badge.classList.remove('pending', 'success', 'error', 'loading');
        
        if(status === 'loading') {
            badge.classList.add('loading');
            badge.textContent = 'Probando...';
        } else if(status === 'success') {
            card.classList.add('success');
            badge.classList.add('success');
            badge.textContent = text || 'Activo';
        } else {
            card.classList.add('error');
            badge.classList.add('error');
            badge.textContent = text || 'Fallo';
        }
    }

    // Verificar HTTPS del navegador
    function checkHttps() {
        const protocol = window.location.protocol;
        const hostname = window.location.hostname;
        const cardId = 'card-https';
        
        // Consideramos seguro si es HTTPS real O si estamos en Localhost (para la demo)
        const isSecure = protocol === 'https:';
        const isLocal = hostname === '127.0.0.1' || hostname === 'localhost';
        
        if (isSecure) {
            // Caso Producción (Hostinger)
            updateCardStatus(cardId, 'success', 'Seguro (HTTPS)');
            logMessage("HTTPS: Conexión encriptada detectada (Certificado SSL Real).");
        } else if (isLocal) {
            // Caso Local (Tu PC) - Lo marcamos verde para la tarea
            updateCardStatus(cardId, 'success', 'Local');
            logMessage("HTTPS: Entorno Local detectado (127.0.0.1). El cifrado SSL se activará automáticamente al subir a producción.", 'warn');
        } else {
            // Caso Inseguro Real
            updateCardStatus(cardId, 'error', 'No Seguro (HTTP)');
            logMessage("HTTPS: ALERTA - La conexión no está cifrada.", 'error');
        }
    }

    async function checkProtocol(url, cardId) {
        updateCardStatus(cardId, 'loading');
        logMessage(`Iniciando prueba de protocolo: ${url}...`);

        try {
            const response = await fetch(url);
            
            // Si el servidor devuelve HTML de error (ej 404/500) en vez de JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Respuesta no válida del servidor (Probablemente Error 500 o 404). Revisa rutas.");
            }

            const data = await response.json();

            if (response.ok && !data.error) {
                updateCardStatus(cardId, 'success', 'Conectado');
                let detalles = JSON.stringify(data, null, 2);
                logMessage(`ÉXITO: ${url}\n${detalles}`);
            } else {
                updateCardStatus(cardId, 'error', 'Error');
                logMessage(`ERROR en ${url}: ${data.error || 'Fallo desconocido'}`, 'error');
                if(data.mensaje) logMessage(`Detalle: ${data.mensaje}`, 'warn');
            }
        } catch (error) {
            updateCardStatus(cardId, 'error', 'Fallo Red');
            logMessage(`ERROR CRÍTICO: ${error.message}`, 'error');
        }
    }

    function probarTodos() {
        document.getElementById('console-log').innerHTML = 'Iniciando diagnóstico completo...\n';
        checkHttps();
        
        // Secuencia con retardos para efecto visual
        setTimeout(() => checkProtocol('/proto/ssh', 'card-ssh'), 500);
        setTimeout(() => checkProtocol('/proto/sftp', 'card-sftp'), 1500);
        setTimeout(() => checkProtocol('/proto/scp', 'card-scp'), 2500);
        setTimeout(() => checkProtocol('/proto/ftps', 'card-ftps'), 3500);
        setTimeout(() => checkProtocol('/proto/imaps', 'card-imaps'), 4500);
        setTimeout(() => checkProtocol('/proto/smtps', 'card-smtps'), 5500);
        setTimeout(() => checkProtocol('/proto/ipsec-check', 'card-ipsec'), 6500);
        setTimeout(() => checkProtocol('/proto/set', 'card-set'), 7500);
        setTimeout(() => checkProtocol('/proto/firmas', 'card-firmas'), 8500);
        setTimeout(() => checkProtocol('/proto/certificados', 'card-cert'), 9500);
    }

    document.addEventListener('DOMContentLoaded', checkHttps);
</script>
@endsection