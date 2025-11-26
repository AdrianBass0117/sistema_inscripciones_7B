<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DisciplinasController;
use App\Http\Controllers\CuentaUsuarioController;
use App\Http\Controllers\CrearController;
use App\Http\Controllers\DisciplinaUsuarioController;
use App\Http\Controllers\ErrorDocumentoController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ValidacionAspirantesController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\ValidacionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProtocolosController;

// 1. Redirigir al proveedor (GitHub)
Route::get('/auth/github', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

// 2. Recibir la respuesta (Callback)
Route::get('/auth/github/callback', function () {
    try {
        // Aquí obtenemos el usuario de GitHub usando el protocolo OAuth
        $githubUser = Socialite::driver('github')->user();
        
        // Buscamos si ya existe o lo creamos (Lógica simplificada para demo)
        $user = Usuario::where('email', $githubUser->getEmail())->first();
        
        if(!$user) {
            return redirect()->route('login')->with('error', 'Tu correo de GitHub no está registrado en el sistema.');
        }

        // Iniciar sesión
        session([
            'user_type' => 'usuario', // Asumimos que es usuario
            'user_id' => $user->id_usuario,
            'user_email' => $user->email,
        ]);

        return redirect()->route('personal');
        
    } catch (\Exception $e) {
        return redirect()->route('login')->with('error', 'Error en autenticación OAuth');
    }
});

// === PROTOCOLOS DE SEGURIDAD ===

// Grupo de pruebas de protocolos
Route::get('/proto/smtps', [ProtocolosController::class, 'testSMTPS']);
Route::get('/proto/ssh', [ProtocolosController::class, 'testSSHReal']);
Route::get('/proto/sftp', [ProtocolosController::class, 'testSFTPReal']);
Route::get('/proto/scp', [ProtocolosController::class, 'testSCP']);
Route::get('/proto/ftps', [ProtocolosController::class, 'testFTPS']);
Route::get('/proto/imaps', [ProtocolosController::class, 'testIMAPS_Socket']);
Route::get('/proto/set', [ProtocolosController::class, 'demoTransaccionSET']);
Route::get('/proto/firmas', [ProtocolosController::class, 'testFirmaDigital']);     // <-- Verifica esta
Route::get('/proto/certificados', [ProtocolosController::class, 'testCertificados']); // <-- Nueva

// IPSEC
Route::get('/proto/ipsec-check', function() {
    return response()->json([
        'estado' => 'ACCESO PERMITIDO',
        'mensaje' => 'Túnel IPSEC Validado (Middleware).'
    ]);
})->middleware('ipsec');

// Página de inicio pública
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/api/constancias/verificar/{codigo}', [ReporteController::class, 'apiVerificarConstancia'])
    ->name('api.constancias.verificar');

// Rutas de autenticación (solo para invitados)
Route::middleware('auth.guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Rutas de registro
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.submit');

    Route::post('/check-email', [RegisterController::class, 'checkEmail'])
        ->name('check.email');
});

Route::get('/user-photo/{userId}', [App\Http\Controllers\ImageController::class, 'showUserPhoto'])
    ->name('user.photo');

// Logout (accesible para todos los autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- BLOQUE DE RUTAS DE CONTRASEÑA CORREGIDO ---
/*
|--------------------------------------------------------------------------
| Rutas de Reseteo de Contraseña
|--------------------------------------------------------------------------
*/

// Muestra el formulario "Olvidé mi contraseña" (forgot-password.blade.php)
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

// Maneja el envío del formulario para enviar el enlace
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');

// Muestra el formulario para ingresar la nueva contraseña (reset-password.blade.php)
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

// Maneja el envío del formulario con la nueva contraseña
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');
// --- FIN DEL BLOQUE CORREGIDO ---


// ================= RUTAS PROTEGIDAS =================

Route::middleware(['auth.check:aspirante'])->group(function () {
    Route::get('/aspirante', function () {
        return view('/aspirante.principal');
    })->name('aspirante');

    Route::get('/aspirante/cuenta', [CuentaUsuarioController::class, 'index'])
        ->name('aspirante.cuenta');

    // Rutas para corrección de información personal
    Route::get('/aspirante/correccion-informacion', [CuentaUsuarioController::class, 'corregirInformacionPersonal'])
        ->name('aspirante.correccion-informacion');

    Route::post('/aspirante/actualizar-informacion', [CuentaUsuarioController::class, 'actualizarInformacionPersonalRechazada'])
        ->name('aspirante.actualizar-informacion');

    Route::put('/cuenta/informacion-personal', [CuentaUsuarioController::class, 'actualizarInformacionPersonal'])
        ->name('cuenta.actualizar-informacion');

    Route::post('/aspirante/cuenta/documento/{idDocumento}/corregir', [CuentaUsuarioController::class, 'subirDocumentoCorregido'])
        ->name('documento.subir-correccion');

    // Obtener información del usuario (AJAX)
    Route::get('/cuenta/informacion', [CuentaUsuarioController::class, 'obtenerInformacionUsuario'])
        ->name('cuenta.obtener-informacion');

    // Rutas para manejo de errores
    Route::get('/errores/documento/{idDocumento}', [ErrorDocumentoController::class, 'obtenerErroresDocumento'])
        ->name('errores.documento');

    Route::get('/errores/usuario', [ErrorDocumentoController::class, 'obtenerTodosErroresUsuario'])
        ->name('errores.usuario');

    Route::post('/documentos/actualizar-multiples', [CuentaUsuarioController::class, 'actualizarDocumentos'])
        ->name('documentos.actualizar-multiples');

    Route::get('/aspirante/notificaciones', [NotificacionController::class, 'indexAspirante'])
        ->name('aspirante.notificaciones');

    Route::post('/aspirante/notificaciones/mark-read', [NotificacionController::class, 'markAsReadUser'])
        ->name('aspirante.notificaciones.markAsRead');

    Route::post('/aspirante/notificaciones/mark-unread', [NotificacionController::class, 'markAsUnreadUser'])
        ->name('aspirante.notificaciones.markAsUnread');

    Route::post('/aspirante/notificaciones/mark-all-read', [NotificacionController::class, 'markAllReadUser'])
        ->name('aspirante.notificaciones.markAllRead');
});

// RUTAS PARA PERSONAL
Route::middleware(['auth.check:usuario'])->group(function () {
    Route::get('/personal', [PrincipalController::class, 'principalParticipante'])->name('personal');
    Route::post('personal/inscribir/{idDisciplina}', [PrincipalController::class, 'inscribirDisciplina'])->name('participante.inscribir');

    // Disciplinas
    Route::get('/personal/disciplinas', [DisciplinaUsuarioController::class, 'index'])
        ->name('personal.disciplinas');

    Route::get('/personal/disciplinas/{id}', [DisciplinaUsuarioController::class, 'obtenerDisciplina'])
        ->name('personal.disciplinas.show');

    Route::post('/personal/disciplinas/inscribir', [DisciplinaUsuarioController::class, 'inscribir'])
        ->name('personal.disciplinas.inscribir');

    Route::post('/personal/disciplinas/cancelar', [DisciplinaUsuarioController::class, 'cancelarInscripcion'])
        ->name('personal.disciplinas.cancelar');

    // Inscripciones
    Route::get('/personal/inscripciones', [DisciplinaUsuarioController::class, 'inscripciones'])
        ->name('personal.inscripciones');

    Route::get('/personal/inscripciones/{id}/detalles', [DisciplinaUsuarioController::class, 'obtenerDetallesInscripcion'])
        ->name('personal.inscripciones.detalles');

    Route::get('/personal/inscripciones/{id}/historial', [DisciplinaUsuarioController::class, 'obtenerHistorialInscripcion'])
        ->name('personal.inscripciones.historial');

    // Constancias
    // 1. Para descarga directa (sin AJAX)
    Route::get('/personal/inscripciones/{id}/descargar-constancia', [ReporteController::class, 'descargarConstancia'])
        ->name('personal.inscripciones.descargar-constancia');

    // 2. Para API/AJAX (devuelve JSON)
    Route::get('/personal/inscripciones/{id}/descargar-constancia-api', [ReporteController::class, 'descargarConstanciaApi'])
        ->name('personal.inscripciones.descargar-constancia-api');

    Route::get('/personal/notificaciones', [NotificacionController::class, 'indexPersonal'])
        ->name('personal.notificaciones');

    Route::post('/personal/notificaciones/mark-read', [NotificacionController::class, 'markAsReadUser'])
        ->name('personal.notificaciones.markAsRead');

    Route::post('/personal/notificaciones/mark-unread', [NotificacionController::class, 'markAsUnreadUser'])
        ->name('personal.notificaciones.markAsUnread');

    Route::post('/personal/notificaciones/mark-all-read', [NotificacionController::class, 'markAllReadUser'])
        ->name('personal.notificaciones.markAllRead');

    Route::get('/personal/historial', [DisciplinaUsuarioController::class, 'historial'])
        ->name('personal.historial');

    Route::get('/personal/historial/detalles/{id}', [DisciplinaUsuarioController::class, 'obtenerDetallesHistorial'])
        ->name('personal.historial.detalles');

    Route::get('/personal/cuenta', [CuentaUsuarioController::class, 'indexPersonal'])
        ->name('personal.cuenta');

    Route::put('/personal/cuenta/actualizar-email', [CuentaUsuarioController::class, 'actualizarEmail'])
        ->name('personal.cuenta.actualizar-email');

    Route::put('/personal/cuenta/actualizar-password', [CuentaUsuarioController::class, 'actualizarPassword'])
        ->name('personal.cuenta.actualizar-password');

    Route::get('/personal/cuenta', [CuentaUsuarioController::class, 'indexPersonal'])->name('personal.cuenta');
    Route::put('/personal/cuenta/informacion-personal', [CuentaUsuarioController::class, 'actualizarInformacionPersonal'])
        ->name('personal.cuenta.actualizar-informacion-personal');
    Route::post('/personal/cuenta/documentos/{idDocumento}/corregir', [CuentaUsuarioController::class, 'subirDocumentoCorregido'])
        ->name('personal.cuenta.subir-documento-corregido');
    Route::post('/personal/cuenta/documentos/actualizar', [CuentaUsuarioController::class, 'actualizarDocumentos'])
        ->name('personal.cuenta.actualizar-documentos');

    // Vista gráfica de SET
    Route::get('/proto/set-visual', [ProtocolosController::class, 'vistaSET']);
    Route::post('/proto/set-generar', [ProtocolosController::class, 'generarFirmaSET']);
    // Gestión de Tarjetas (Protocolo SET)
        Route::get('/personal/tarjetas', [CuentaUsuarioController::class, 'indexTarjetas'])
            ->name('personal.tarjetas');
            
        Route::post('/personal/tarjetas', [CuentaUsuarioController::class, 'storeTarjeta'])
            ->name('personal.tarjetas.store');
});

// RUTAS PARA COMITÉ
Route::middleware(['auth.check:comite'])->group(function () {
    Route::get('/Comite', [PrincipalController::class, 'principal'])->name('comite');

    // Gestión de Aspirantes (Inscripciones)
    Route::get('/Comite/Aspirantes', [ValidacionAspirantesController::class, 'index'])
        ->name('comite.aspirantes');

    Route::post('/Comite/inscripciones/{id}/aceptar', [ValidacionAspirantesController::class, 'aceptarInscripcion'])
        ->name('comite.inscripciones.aceptar');

    Route::post('/Comite/inscripciones/{id}/rechazar', [ValidacionAspirantesController::class, 'rechazarInscripcion'])
        ->name('comite.inscripciones.rechazar');

    Route::post('/Comite/inscripciones/{id}/reconsiderar', [ValidacionAspirantesController::class, 'reconsiderarInscripcion'])
        ->name('comite.inscripciones.reconsiderar');

    Route::get('/Comite/estadisticas/aspirantes', [ValidacionAspirantesController::class, 'obtenerEstadisticas'])
        ->name('comite.estadisticas.aspirantes');

    Route::get('/comite/verificacion-constancias', function () {
        return view('comite.verificacion-constancias');
    })->name('comite.verificacion-constancias');

    // Rutas para Reportes
    Route::get('/Comite/Reportes', function () {
        return view('comite.reportes');
    })->name('comite.reportes');

    Route::get('/comite/reportes/disciplinas', [ReporteController::class, 'listarDisciplinas'])
        ->name('comite.reportes.disciplinas');

    Route::get('/comite/reportes/disciplinas-historial', [ReporteController::class, 'disciplinasConHistorial'])
        ->name('comite.reportes.disciplinas-historial');

    Route::get('/comite/reportes/disciplina-historial/{idDisciplina}', [ReporteController::class, 'disciplinaHistorial'])
        ->name('comite.reportes.disciplina-historial');

    Route::get('/comite/reportes/inscripciones', [ReporteController::class, 'todasLasInscripciones'])
        ->name('comite.reportes.inscripciones');

    Route::get('/comite/reportes/inscripciones/estado/{estado}', [ReporteController::class, 'inscripcionesPorEstado'])
        ->name('comite.reportes.inscripciones.estado');

    Route::get('/comite/reportes/usuarios', [ReporteController::class, 'listarUsuarios'])
        ->name('comite.reportes.usuarios');

    Route::get('/comite/reportes/usuarios/estado/{estado}', [ReporteController::class, 'usuariosPorEstado'])
        ->name('comite.reportes.usuarios.estado');

    Route::get('/comite/reportes/disciplina-inscritos/{idDisciplina}', [ReporteController::class, 'obtenerInscritosActuales'])
        ->name('comite.reportes.disciplina-inscritos');

    Route::get('/comite/reportes/datos-detalle/{idDisciplina}', [ReporteController::class, 'obtenerDatosReporteDetallado'])
        ->name('comite.reportes.datos-detalle');

    Route::get('/comite/reportes/detalle/{idDisciplina}', [ReporteController::class, 'mostrarReporteDetalle'])
        ->name('comite.reportes.detalle');

    Route::post('/comite/reportes/exportar/{idDisciplina}', [ExportController::class, 'exportarReporteDisciplina'])
        ->name('comite.reportes.exportar');

    Route::post('/comite/reportes/usuarios/exportar/{estado}', [ExportController::class, 'exportarUsuariosPorEstado'])
        ->name('comite.reportes.usuarios.exportar');

    // Perfil de aspirante
    Route::get('/Comite/Cuenta-Aspirante/{id}', [ValidacionAspirantesController::class, 'showCuentaAspirante'])
        ->name('comite.cuentas-aspirantes');

    // Acciones de documentos
    Route::get('/Comite/documentos/{id}/ver', [ValidacionAspirantesController::class, 'verDocumento'])
        ->name('comite.documentos.ver');

    Route::get('/Comite/documentos/{id}/descargar', [ValidacionAspirantesController::class, 'descargarDocumento'])
        ->name('comite.documentos.descargar');

    // Rutas para Disciplinas
    Route::get('/Comite/Disciplinas', [DisciplinasController::class, 'index'])->name('comite.disciplinas');
    Route::get('/Comite/Disciplinas/Crear', [DisciplinasController::class, 'create'])->name('comite.disciplinas-crear');
    Route::post('/Comite/Disciplinas/Crear', [DisciplinasController::class, 'store'])->name('comite.disciplinas-store');
    Route::get('/Comite/Disciplinas/Editar/{id}', [DisciplinasController::class, 'edit'])->name('comite.disciplinas-editar');
    Route::put('/Comite/Disciplinas/Editar/{id}', [DisciplinasController::class, 'update'])->name('comite.disciplinas-update'); // Cambiado a PUT
    Route::post('/Comite/disciplinas/{id}/toggle-status', [DisciplinasController::class, 'toggleStatus'])->name('comite.disciplinas.toggle-status');
    Route::get('/Comite/disciplinas/estadisticas', [DisciplinasController::class, 'getStatistics'])->name('comite.disciplinas.estadisticas');

    // Rutas para finalización de disciplinas
    Route::get('/Comite/Disciplinas/{id}/finalizar', [DisciplinasController::class, 'showFinalizar'])
        ->name('comite.disciplinas-finalizar');

    Route::post('/Comite/Disciplinas/{id}/finalizar', [DisciplinasController::class, 'finalizarDisciplina'])
        ->name('comite.disciplinas-finalizar-store');

    // Nuevas rutas para ver inscritos
    Route::get('/Comite/Disciplinas/{id}/inscritos', [DisciplinasController::class, 'showInscritos'])
        ->name('comite.disciplinas-inscritos');

    Route::get('/Comite/Disciplinas/{id}/inscritos/data', [DisciplinasController::class, 'getInscritosData'])
        ->name('comite.disciplinas-inscritos.data');

    // Rutas de Validación
    Route::get('/Comite/Validaciones', [ValidacionController::class, 'index'])->name('comite.validacion');
    Route::get('/Comite/obtener-usuarios', [ValidacionController::class, 'obtenerUsuarios'])->name('comite.obtener.usuarios');

    // Acciones de usuario
    Route::post('/Comite/usuarios/{id}/aceptar', [ValidacionController::class, 'aceptarUsuario'])->name('comite.usuarios.aceptar');
    Route::post('/Comite/usuarios/{id}/rechazar', [ValidacionController::class, 'rechazarUsuario'])->name('comite.usuarios.rechazar');
    Route::post('/Comite/usuarios/{id}/reconsiderar', [ValidacionController::class, 'reconsiderarUsuario'])->name('comite.usuarios.reconsiderar');

    // Validación de aspirante específico
    Route::get('/Comite/Validaciones/Usuario/{id}', [ValidacionController::class, 'show'])->name('comite.validacion-aspirante');

    // Acciones de documentos
    Route::post('/Comite/documentos/{id}/aceptar', [ValidacionController::class, 'aceptarDocumento'])->name('comite.documentos.aceptar');
    Route::post('/Comite/documentos/{id}/rechazar', [ValidacionController::class, 'rechazarDocumento'])->name('comite.documentos.rechazar');
    Route::get('/Comite/documentos/{id}/descargar', [ValidacionController::class, 'descargarDocumento'])
        ->name('comite.documentos.descargar');

    // CON ESTAS NUEVAS (con parámetros de ID y nombres):
    Route::post('/Comite/informacion-personal/{id}/aprobar', [ErrorDocumentoController::class, 'aprobarInformacionPersonal'])
        ->name('comite.informacion-personal.aprobar');

    Route::post('/Comite/informacion-personal/{id}/rechazar', [ErrorDocumentoController::class, 'rechazarInformacionPersonal'])
        ->name('comite.informacion-personal.rechazar');

    Route::get('/Comite/informacion-personal/{id}/estado', [ErrorDocumentoController::class, 'obtenerEstadoValidacionInformacion'])
        ->name('comite.informacion-personal.estado');

    Route::post('/Comite/informacion-personal/nueva-validacion', [ErrorDocumentoController::class, 'crearNuevaValidacionPendiente'])
        ->name('comite.informacion-personal.nueva-validacion');

    Route::get('/Comite/documentos/{id}/ver', [ValidacionController::class, 'verDocumento'])
        ->name('comite.documentos.ver');

    // Rutas para manejo de errores en documentos
    Route::post('/Comite/errores/registrar', [ErrorDocumentoController::class, 'registrarError'])->name('comite.errores.registrar');
    Route::post('/Comite/errores/{id}/corregido', [ErrorDocumentoController::class, 'marcarCorregido'])->name('comite.errores.corregido');
    Route::get('/Comite/usuarios/{id}/errores', [ErrorDocumentoController::class, 'obtenerErroresUsuario'])->name('comite.usuarios.errores');

    // Rutas para Notificaciones
    Route::get('/Comite/notificaciones', [NotificacionController::class, 'index'])
        ->name('comite.notificaciones');

    Route::post('/Comite/notificaciones', [NotificacionController::class, 'store'])
        ->name('comite.notificaciones.store');

    Route::post('/Comite/notificaciones/mark-read', [NotificacionController::class, 'markAsRead'])
        ->name('comite.notificaciones.markAsRead');

    Route::post('/Comite/notificaciones/mark-unread', [NotificacionController::class, 'markAsUnread'])
        ->name('comite.notificaciones.markAsUnread');

    Route::post('/Comite/notificaciones/mark-all-read', [NotificacionController::class, 'markAllRead'])
        ->name('comite.notificaciones.markAllRead');
});

// RUTAS PARA SUPERVISOR
Route::middleware(['auth.check:supervisor'])->group(function () {
    Route::get('/Supervisor', function () {
        return view('/supervisor.principal');
    })->name('supervisor');

    Route::get('/Supervisor/Notificaciones', [NotificacionController::class, 'indexSupervisor'])
        ->name('supervisor.notificaciones');

    Route::post('/supervisor/Notificaciones', [NotificacionController::class, 'storeSupervisor'])
        ->name('supervisor.notificaciones.store');

    Route::post('/supervisor/Notificaciones/mark-read', [NotificacionController::class, 'markAsRead'])
        ->name('supervisor.notificaciones.markAsRead');

    Route::post('/supervisor/Notificaciones/mark-unread', [NotificacionController::class, 'markAsUnread'])
        ->name('supervisor.notificaciones.markAsUnread');

    Route::post('/supervisor/Notificaciones/mark-all-read', [NotificacionController::class, 'markAllRead'])
        ->name('supervisor.notificaciones.markAllRead');

    // Rutas para la gestión de usuarios del supervisor
    Route::prefix('Supervisor')->group(function () {
        Route::get('/comite', [CrearController::class, 'index'])->name('supervisor.comite');
        Route::post('/crear-usuario', [CrearController::class, 'crearUsuario'])->name('supervisor.crear.usuario');
        Route::get('/obtener-usuarios', [CrearController::class, 'obtenerUsuarios'])->name('supervisor.obtener.usuarios');
    });

    // Dashboard de estadísticas
    Route::get('/Supervisor/estadisticas', [SupervisorController::class, 'estadisticasEjecutivas'])->name('supervisor.estadisticas');

    // Descargar reportes
    Route::post('/Supervisor/reportes/descargar', [SupervisorController::class, 'descargarReportes'])->name('supervisor.reportes.descargar');

    // Datos para gráficos (AJAX)
    Route::get('/Supervisor/estadisticas/datos-graficos', [SupervisorController::class, 'obtenerDatosGraficos'])->name('supervisor.estadisticas.datos-graficos');

    Route::get('/Supervisor/Reportes', function () {
        return view('/supervisor.reportes');
    })->name('supervisor.reportes');

    // Vista del Panel de Protocolos
    Route::get('/Supervisor/protocolos', function () {
        return view('supervisor.protocolos');
    })->name('supervisor.protocolos');

    // Blockchain
    Route::get('/Supervisor/blockchain', [App\Http\Controllers\BlockchainController::class, 'index'])
        ->name('supervisor.blockchain');
    Route::post('/Supervisor/blockchain/hack/{id}', [App\Http\Controllers\BlockchainController::class, 'hackBlock'])
        ->name('supervisor.blockchain.hack');
    Route::post('/Supervisor/blockchain/repair', [App\Http\Controllers\BlockchainController::class, 'repairChain'])
        ->name('supervisor.blockchain.repair');
});

// Ruta de home genérica (para cualquier usuario autenticado)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/home', function () {
        // Redirigir según el tipo de usuario
        $userType = session('user_type');

        return match ($userType) {
            'comite' => redirect()->route('comite'),
            'supervisor' => redirect()->route('supervisor'),
            'usuario' => redirect()->route('personal'),
            'aspirante' => redirect()->route('aspirante'),
            default => redirect()->route('login')
        };
    })->name('home');
});