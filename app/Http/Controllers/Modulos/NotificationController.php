<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationController extends Controller
{
    
    /**
     * Authenticate a Pusher channel for a user.
     *
     * This function ensures that the user is authenticated before allowing
     * them to connect to a Pusher channel. If the user is not authenticated,
     * a 403 Unauthorized response is returned. Otherwise, it configures the
     * Pusher instance and returns the socket authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function auth(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Configuración de Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        // Autenticación del socket
        return response($pusher->socket_auth($request->input('channel_name'), $request->input('socket_id')));
    }

    /**
     * Devuelve las 5 últimas notificaciones del usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(5)->get(); // Ajusta el número según tus necesidades
        return response()->json($notifications);
    }

    /**
     * Marca como leído una notificación específica del usuario autenticado.
     *
     * @param int $id El ID de la notificación a marcar como leído.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}
