<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class DatabaseNotification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        Date::setLocale(app()->getLocale());

        $type = null;
        $comment = null;

        $created_at = new Date($this->created_at);
        $updated_at = new Date($this->updated_at);

        $resp = [
            "id" => $this->id,
            "isRead" => $this->read(),
            "isSeen" => $this->seen,
            'marck_read_or_unread_link' => route('api.users.notifications.update', $this->id),
            "created_at" => ucfirst($created_at->toFormattedDateString()),
            "updated_at" => ucfirst($updated_at->toFormattedDateString()),
            "created_at_diff" => Helpers::decHumanDiffDate($created_at),
            "updated_at_diff" => Helpers::decHumanDiffDate($updated_at),
        ];

        switch ($this->type) {
            case 'App\\Notifications\\CommentPostNotification':
                $resp['type'] = 'new_comment_post';
                $resp['commet'] = $this->data['comment'];
                $resp['post'] = $this->data['post']['body'];
                break;
            case 'App\\Notifications\\FollowerNotification':
                $resp['type'] = 'new_follewer';
                break;
            case 'App\\Notifications\\LikePostNotification':
                $resp['type'] = 'new_like';
                $resp['post'] = $this->data['post']['body'];
                break;
            case 'App\\Notifications\\UserConversationNotification':
                $resp['type'] = 'new_chat_message';
                $resp['message'] = $this->data['message']['body'];
                break;
            case 'App\\Notifications\\FriendRequestNotification':
                $resp['type'] = 'new_friend_request';
                break;
        }

        $resp['data'] = $this->data;

        return $resp;
    }
}
