<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentNotification;
use App\Events\LikesNotifications;
use App\Helpers\Helpers;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\CommentCollection;
use App\Notifications\CommentPost;
use App\Notifications\CommentPostNotification;
use App\Post;
use App\User;
use Illuminate\Http\Request;


/**
 * <b>Name=</b>  Comment <br>
 * <b>Description=</b>  Métodos para agregar, eliminar y actualizar o editar un comentario a una publicación.
 */
class CommentController extends Controller
{
    public function index(Request $request, Post $post)
    {
        return new CommentCollection($post->comments()->orderBy('created_at', 'asc')->paginate($request->has('per_page') ? $request->per_page : 2));
    }

    /**
     * @access public 
     * Método para agregar un nuevo comentario a una publicación (post). El comentario está asociado al usuario que lo crea
     * Se envía nueva notificación
     * 
     * @author Kevin Cifuentes 
     * @param CommentRequest $request texto o cuerpo del comentario
     * @param Post $post id del popst
     * 
     * @return Comentario asociado a una publicación
     */
    public function store(CommentRequest $request, Post $post)
    {
        try {
            $user = $request->user();

            $comment = new Comment([
                'body' => $request->body,
            ]);

            $comment->user()->associate($user);
            $post->comments()->save($comment);

            if($post->user->id !== $user->id){
                $post->user->notify(new CommentPostNotification($post, $user, $comment));
                event(new CommentNotification($post->user, $post, $user, $comment));
            }
        } catch (\Exception $e) {
            return response($e, 500);
        }


        return new CommentResource($comment);
    }

    /**
     * @access public 
     * Método para actualizar o editar un comentario asociado a una publicación
     * Se valida solo el autor del comentario está autorizado para editar el comentario
     * 
     * @author Kevin Cifuentes
     * @param CommentRequest $request texto o cuerpo del comentario
     * @param Comment $comment id del comentario que se actualiza
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        if ($request->user()->id != $comment->user->id)
            return response(['message' => __('validation.error_users_id')], 409);

        $comment->body = $request->body;
        $comment->save();

        return new CommentResource($comment);
    }

    /** 
     * @access public 
     * Método para eliminar un comentario 
     * Se valida que solo el autor del comentario está autorizado para realizar la acción
     * 
     * @author Kevin Cifuentes
     * @param Request $request 
     * @param Comment $comment id del comentario
     * 
    */
    public function delete(Request $request, Comment $comment)
    {
        if ($request->user()->id != $comment->user->id)
            return response(['message' => __('validation.error_users_id')], 409);

        $comment->delete();

        return response()->json([
            'message' => "Success",
            "data"    => [],
        ]);
    }
}
