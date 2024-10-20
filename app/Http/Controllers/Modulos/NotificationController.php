<?php

namespace App\Http\Controllers\Modulos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class NotificationController extends Controller
{
    //
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

    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->take(5)->get(); // Ajusta el número según tus necesidades

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
}
