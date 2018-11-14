<?php

namespace App\Events;

use App\ChatMessage;
use App\Conversation;
use App\Http\Resources\ChatMessage as ChatMessageResource;
use App\Http\Resources\Conversation as ConversationResource;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @author @Kevin Cifuentes
 */
class UserConversationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $senderUser;
    /**
     * @var Conversation
     */
    public $conversation;
    /**
     * @var ChatMessage
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param User $senderUser
     * @param Conversation $conversation
     * @param ChatMessage $message
     */
    public function __construct(User $user, Conversation $conversation, ChatMessage $message)
    {
        $this->user = new UserResource($user);
        $this->conversation = new ConversationResource($conversation);
        $this->message = new ChatMessageResource($message);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("App.Conversation." . $this->conversation->id);
    }

    public function broadcastAs()
    {
        return 'chat.new.message';
    }

    public function broadcastWith()
    {
        $avatar_path = ($this->user->avatar) ? $this->user->avatar->avatar_path : null;

        $resp = [
            'message' => $this->message,
        ];
        return $resp;
    }
}
