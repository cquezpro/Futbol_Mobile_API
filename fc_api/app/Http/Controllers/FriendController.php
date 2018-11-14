<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Notifications\FriendRequestNotification;
use App\User;
use Illuminate\Http\Request;

/**
 * <b>Name=</b>  Fiends<br>
 * <b>Description=</b>  Consta de las acciones que realiza un usuario para enviar, aceptar, rechazar y eliminar un amigo.
 *
 */
class FriendController extends Controller
{
    public function index(Request $request, User $user)
    {
        if (!$user) {
            return abort('404', __('app.users.invalid'));
        }

        $resp = $user->getFriends();

        return response(new UserCollection($resp));
    }
    /**
     * Método para enviar una solicitud de amistad
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request
     * @param User $recipient Identificador del usuario que solicita la amistad
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response Usuario que solicita la amistad.
     */
    public function sendAFriendRequest(Request $request, $user)
    {
        $user = User::find($user);
        $resp = $request->user()->befriend($user);

        $user->notify(new FriendRequestNotification($resp, $request->user()));

        return response([
            'message' => 'Send',
            'data' => $resp,
        ]);
    }

    public function cancelFriendRequest(Request $request, User $user)
    {

        $resp = $request->user()->unfriend($user);

        foreach ($user->unreadNotifications as $notification) {
            if ($notification->type == 'App\\Notifications\\FriendRequestNotification') {
                $senderUser = $notification->data;
                //Log::info($request->user()->id . " || " . $senderUser["userSender"]["id"]);
                if ($request->user()->id === $senderUser["userSender"]["id"]) {
                    $notification->markAsRead();
                }

            }

        }

        return response([
            'message' => 'Send',
            'data' => $resp,
        ]);
    }

    /**
     * Método para aceptar solicitud de amistad
     * $sender es el usuario que envía la solicitud de amistad
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request
     * @param Request $sender id del usuario que envío la solicitud de amistad
     * @param Request $sender
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function acceptAFriendRequest(Request $request, User $user)
    {
        $resp = $request->user()->acceptFriendRequest($user);
        return response([
            'message' => null,
            'data' => $resp,
        ]);
    }

    /**
     * Rechazar o eliminar una solicitud de amistad
     * $sender es el usuario que solicita de amistad
     *
     * @access public
     * @author Kevin Cifuentes
     * @param Request $request
     * @param Request $sender
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function denyAFriendRequest(Request $request, $sender)
    {
        $resp = $request->user()->denyFriendRequest($sender);
        return response([
            'message' => null,
            'data' => $resp,
        ]);
    }

    /**
     * Método para eliminar un amigo de la lista de amigos
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request
     * @param Request $friend el usuario que se elimina de la lista de amigos
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function removeFriend(Request $request, $friend)
    {
        $resp = $request->user()->unfriend($friend);
        return response([
            'message' => null,
            'data' => $resp,
        ]);
    }
}
