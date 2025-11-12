<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Comite;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 10;

        // Obtener todas las notificaciones (no necesitan ser del comité específico)
        $notificaciones = Notificacion::recientes()->paginate($perPage);

        // Contadores para los filtros
        $totalCount = Notificacion::count();
        $unreadCount = Notificacion::noLeidas()->count();
        $systemCount = Notificacion::where('tipo', 'sistema')->count();

        if ($request->ajax()) {
            $html = '';
            foreach ($notificaciones as $notificacion) {
                $html .= view('comite.partials.notification-item', compact('notificacion'))->render();
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $notificaciones->hasMorePages()
            ]);
        }

        return view('comite.notificaciones', compact(
            'notificaciones',
            'totalCount',
            'unreadCount',
            'systemCount'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'tipo' => 'required|in:general,urgente,recordatorio',
            'destinatarios' => 'required|in:todos,comite,personal'
        ]);

        try {
            DB::beginTransaction();

            $asunto = $request->asunto;
            $mensaje = $request->mensaje;
            $tipo = $request->tipo;
            $destinatarios = $request->destinatarios;

            // Contar usuarios afectados
            $usuariosCount = $this->contarUsuariosPorDestinatarios($destinatarios);

            // Crear UNA sola notificación en la BD
            $notificacion = Notificacion::create([
                'tipo' => $tipo,
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'leida' => false,
                'destinatarios' => $destinatarios, // Guardamos a quién fue dirigida
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('comite.notificaciones')
                ->with('success', "Notificación '$asunto' creada correctamente. Se enviará a {$usuariosCount} usuarios.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('comite.notificaciones')
                ->with('error', 'Error al crear la notificación: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function indexSupervisor(Request $request)
    {
        $perPage = 10;

        // Obtener todas las notificaciones (no necesitan ser del comité específico)
        $notificaciones = Notificacion::recientes()->paginate($perPage);

        // Contadores para los filtros
        $totalCount = Notificacion::count();
        $unreadCount = Notificacion::noLeidas()->count();
        $systemCount = Notificacion::where('tipo', 'sistema')->count();

        if ($request->ajax()) {
            $html = '';
            foreach ($notificaciones as $notificacion) {
                $html .= view('supervisor.partials.notification-item', compact('notificacion'))->render();
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $notificaciones->hasMorePages()
            ]);
        }

        return view('supervisor.notificaciones', compact(
            'notificaciones',
            'totalCount',
            'unreadCount',
            'systemCount'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSupervisor(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'tipo' => 'required|in:general,urgente,recordatorio',
            'destinatarios' => 'required|in:todos,comite,personal'
        ]);

        try {
            DB::beginTransaction();

            $asunto = $request->asunto;
            $mensaje = $request->mensaje;
            $tipo = $request->tipo;
            $destinatarios = $request->destinatarios;

            // Contar usuarios afectados
            $usuariosCount = $this->contarUsuariosPorDestinatarios($destinatarios);

            // Crear UNA sola notificación en la BD
            $notificacion = Notificacion::create([
                'tipo' => $tipo,
                'asunto' => $asunto,
                'mensaje' => $mensaje,
                'leida' => false,
                'destinatarios' => $destinatarios, // Guardamos a quién fue dirigida
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('supervisor.notificaciones')
                ->with('success', "Notificación '$asunto' creada correctamente. Se enviará a {$usuariosCount} usuarios.");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('supervisor.notificaciones')
                ->with('error', 'Error al crear la notificación: ' . $e->getMessage());
        }
    }

    /**
     * Contar usuarios según el tipo de destinatarios
     */
    private function contarUsuariosPorDestinatarios($destinatarios)
    {
        $count = 0;

        switch ($destinatarios) {
            case 'todos':
                // Comité
                $count += Comite::count();

                // Personal (usuarios con estado_cuenta = 'validado')
                $count += Usuario::where('estado_cuenta', 'validado')->count();

                // Aspirantes (usuarios con estado_cuenta = 'pendiente' o 'rechazado')
                $count += Usuario::whereIn('estado_cuenta', ['pendiente', 'rechazado'])->count();
                break;

            case 'comite':
                $count += Comite::count();
                break;

            case 'personal':
                $count += Usuario::where('estado_cuenta', 'validado')->count();
                break;
        }

        return $count;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request)
    {
        $request->validate([
            'id_notificacion' => 'required|integer'
        ]);

        try {
            $notificacion = Notificacion::findOrFail($request->id_notificacion);
            $notificacion->marcarComoLeida();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Request $request)
    {
        $request->validate([
            'id_notificacion' => 'required|integer'
        ]);

        try {
            $notificacion = Notificacion::findOrFail($request->id_notificacion);
            $notificacion->marcarComoNoLeida();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllRead(Request $request)
    {
        try {
            $afectadas = Notificacion::noLeidas()->update(['leida' => true]);

            return response()->json([
                'success' => true,
                'message' => "Se marcaron {$afectadas} notificaciones como leídas"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display notifications for personal (usuario)
     */
    public function indexPersonal(Request $request)
    {
        $perPage = 10;

        // Obtener notificaciones para personal (destinatarios: todos o personal)
        $notificaciones = Notificacion::whereIn('destinatarios', ['todos', 'personal'])
            ->recientes()
            ->paginate($perPage);

        // Contadores para los filtros
        $totalCount = Notificacion::whereIn('destinatarios', ['todos', 'personal'])->count();
        $unreadCount = Notificacion::whereIn('destinatarios', ['todos', 'personal'])->noLeidas()->count();
        $systemCount = Notificacion::whereIn('destinatarios', ['todos', 'personal'])
            ->where('tipo', 'sistema')
            ->count();

        if ($request->ajax()) {
            $html = '';
            foreach ($notificaciones as $notificacion) {
                $html .= view('participante.partials.notification-item', compact('notificacion'))->render();
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $notificaciones->hasMorePages()
            ]);
        }

        return view('participante.notificaciones', compact(
            'notificaciones',
            'totalCount',
            'unreadCount',
            'systemCount'
        ));
    }

    /**
     * Display notifications for aspirantes
     */
    public function indexAspirante(Request $request)
    {
        $perPage = 10;

        // Obtener notificaciones para aspirantes (destinatarios: todos)
        $notificaciones = Notificacion::where('destinatarios', 'todos')
            ->recientes()
            ->paginate($perPage);

        // Contadores para los filtros
        $totalCount = Notificacion::where('destinatarios', 'todos')->count();
        $unreadCount = Notificacion::where('destinatarios', 'todos')->noLeidas()->count();
        $systemCount = Notificacion::where('destinatarios', 'todos')
            ->where('tipo', 'sistema')
            ->count();

        if ($request->ajax()) {
            $html = '';
            foreach ($notificaciones as $notificacion) {
                $html .= view('aspirante.partials.notification-item', compact('notificacion'))->render();
            }

            return response()->json([
                'html' => $html,
                'hasMore' => $notificaciones->hasMorePages()
            ]);
        }

        return view('aspirante.notificaciones', compact(
            'notificaciones',
            'totalCount',
            'unreadCount',
            'systemCount'
        ));
    }

    /**
     * Mark notification as read for personal/aspirante
     */
    public function markAsReadUser(Request $request)
    {
        $request->validate([
            'id_notificacion' => 'required|integer'
        ]);

        try {
            $notificacion = Notificacion::findOrFail($request->id_notificacion);

            // Verificar que la notificación es para el usuario
            if ($this->isNotificationForUser($notificacion)) {
                $notificacion->marcarComoLeida();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark notification as unread for personal/aspirante
     */
    public function markAsUnreadUser(Request $request)
    {
        $request->validate([
            'id_notificacion' => 'required|integer'
        ]);

        try {
            $notificacion = Notificacion::findOrFail($request->id_notificacion);

            // Verificar que la notificación es para el usuario
            if ($this->isNotificationForUser($notificacion)) {
                $notificacion->marcarComoNoLeida();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mark all notifications as read for personal/aspirante
     */
    public function markAllReadUser(Request $request)
    {
        try {
            $userType = auth()->user()->tipo_usuario ?? 'usuario';

            if ($userType === 'usuario') {
                // Para personal: todos o personal
                $afectadas = Notificacion::whereIn('destinatarios', ['todos', 'personal'])
                    ->noLeidas()
                    ->update(['leida' => true]);
            } else {
                // Para aspirantes: solo todos
                $afectadas = Notificacion::where('destinatarios', 'todos')
                    ->noLeidas()
                    ->update(['leida' => true]);
            }

            return response()->json([
                'success' => true,
                'message' => "Se marcaron {$afectadas} notificaciones como leídas"
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if notification is for current user type
     */
    private function isNotificationForUser($notificacion)
    {
        $userType = auth()->user()->tipo_usuario ?? 'usuario';

        if ($userType === 'usuario') {
            // Personal puede ver: todos o personal
            return in_array($notificacion->destinatarios, ['todos', 'personal']);
        } else {
            // Aspirante puede ver: solo todos
            return $notificacion->destinatarios === 'todos';
        }
    }
}
