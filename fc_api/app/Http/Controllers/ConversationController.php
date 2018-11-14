<?php

namespace App\Http\Controllers;

use App\User;
use App\ChatMessage;
use App\Conversation;
use Illuminate\Http\Request;
use App\Events\UserConversationEvent;
use App\Http\Resources\ChatMessageCollection;
use App\Http\Resources\ConversationCollection;
use App\Notifications\UserConversationNotification;
use App\Http\Resources\ChatMessage as ChatMessageResource;
use App\Http\Resources\Conversation as ConversationResources;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $localUser = $request->user();
        //$localUser = User::find(1);
        try {
            $conversations = $localUser->conversations;

            return response([
                'conversations' => new ConversationCollection($conversations),
            ]);
        } catch (Exception $e) {
            return response($e);
        }
    }

    public function show(Request $request, User $user)
    {
        # code...
    }

    private function chek_conversation_users(...$users)
    {
        
    }

    public function store(Request $request, User $user)
    {
        //$localUser = \App\User::find(1);
        $localUser = $request->user();
        try {
            $conv1 = $localUser->conversations->pluck("id")->toArray();
            $conv2 = $user->conversations->pluck("id")->toArray();
            
            //TODO: Realizar metodo para agregar Gruposreturn count(call_user_func_array("array_intersect", [$conv1, $conv2]));

            $conversation = false;
            if(count(array_intersect($conv1, $conv2)) > 0){
                $conversation = Conversation::find(array_intersect($conv1, $conv2)[0]);
            }else{
                $conversation = Conversation::create(['data' => '']);
                $conversation->users()->attach([$localUser->id, $user->id]);
            }

            if(!$conversation)
                return response([
                    'message' => "Error"
                ]);
            return response(new ConversationResources($conversation));
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getMessages(Request $request, Conversation $conversation)
    {
        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return response(new ChatMessageCollection($messages));
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $localUser = $request->user();
        //$localUser = User::find(1);
        try {
            $chatMessage = new ChatMessage([
                'from'   => $localUser->id,
                'body'   => $request->body,
                'read' => false,
            ]);

            $conversation->messages()->save($chatMessage);

            $users = $conversation->users()->where('user_id', '<>',$localUser)->get();
            
            foreach ($users as $user) {
                $user->notify(new UserConversationNotification($localUser, $conversation, $chatMessage));
            }

            broadcast(new UserConversationEvent($localUser, $conversation, $chatMessage))->toOthers();

            return response([
                'message'    => new ChatMessageResource($chatMessage),
            ]);
        } catch (Exception $e) {
            return response($e);
        }
    }
}
