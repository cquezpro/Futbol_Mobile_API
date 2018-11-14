<?php

namespace App\Http\Controllers;

use App\Http\Resources\DatabaseNotificationCollection;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification as DatabaseNotificationModel;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = null;

        if ($request->has('type') && !empty($request->type)) {
            $types = explode(',', $request->type);

            $newTypes = [];
            foreach ($types as $key => $value) {
                switch ($value) {
                    case 'new_comment_post':
                        $newTypes[$key] = 'App\\Notifications\\CommentPostNotification';
                        break;
                    case 'new_follewer':
                        $newTypes[$key] = 'App\\Notifications\\FollowerNotification';
                        break;
                    case 'new_like':
                        $newTypes[$key] = 'App\\Notifications\\LikePostNotification';
                        break;
                }
            }

            $notifications = $request->user()->notifications()->whereIn("type", $newTypes)->get();

        } else {
            $notifications = $request->user()->notifications()
                ->where('type', '<>', 'App\\Notifications\\UserConversationNotification')->where("type", '<>', 'App\\Notifications\\FriendRequestNotification')
                ->get();
        }

        $friendRequest = $request->user()->unreadNotifications()
            ->where("type", 'App\\Notifications\\FriendRequestNotification')
            ->get();

        return response([
            'all' => new DatabaseNotificationCollection($notifications),
            'friendRequests' => new DatabaseNotificationCollection($friendRequest),
        ]);
    }

    public function update(DatabaseNotificationModel $notification)
    {
        if ($notification->read()) {
            $notification->markAsUnread();
        } else {
            $notification->markAsRead();
        }

        return response([
            'message' => '',
            'data' => $notification,
        ]);
    }

    public function marckUnSeen(Request $request)
    {
        if ($request->has("notifications")) {
            foreach ($request->notifications as $notification) {
                $notification = DatabaseNotificationModel::find($notification["id"]);
                if (!$notification->seen) {
                    $notification->seen = true;
                } else if ($notification->seen) {
                    $notification->seen = false;
                }

                $notification->save();
            }
        }

        return response()->json(["status" => "ok"]);
    }
}
