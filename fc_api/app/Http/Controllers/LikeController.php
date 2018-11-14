<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\User;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use App\Events\LikesNotifications;
use App\Notifications\LikePostNotification;
use App\Http\Resources\Post as PostResource;

/**
 * @author Kevin Cifuentes
 * 
 * Controlador de like, se asocia a la publicación y al usuario quien lo realiza
 */
class LikeController extends Controller
{
    /**
     * Asigna un nuevo like a una publicación o post. Se crea una instancia de la clase LikesNotifications que envía una notificación. 
     * 
     * @access public
     * @author Kevin Cifuentes
     * 
     * @param Request $request
     * @param Request $post id del post o publicación a la se que añade un like
     */
    public function store(Request $request, Post $post)
    {
        try {
            $user = $request->user();

            $likeExists = Like::where('user_id', $user->id)
                ->where('likeable_id', $post->id)
                ->first();

            if (!$likeExists) {
                $like = new Like();

                $like->user()->associate($user);
                $post->likes()->save($like);

                if($post->user->id !== $user->id){
                    event(new LikesNotifications($post->user, $user, $post));
                    $post->user->notify(new LikePostNotification($post, $user));
                }
            } else {
                $likeExists->delete();
            }
        } catch (\Exception $e) {
            return $e;
        }

        return new PostResource($post);
    }
}
