<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Resources\Post as PostResource;
use App\Http\Resources\PostCollection;
use App\Post;
use App\PostImage;
use App\PostVideo;
use App\User;
use App\UserImage;
use App\UserVideo;
use Illuminate\Http\Request;

/**
 * Controlador de post (publicaciones).  Contiene las acciones para crear, actualizar, mostrar y eliminar un post.
 *
 * @author Kevin Cifuentes
 */
class PostController extends Controller
{
    public function getPostsByUser(Request $request, User $user)
    {
        //Listar los post del usuario en usuario en su perfil, si no se envía type=all
        $posts = $user->posts()->orderBy('created_at', 'desc')->get();
        return response(new PostCollection($posts));
    }

    public function index(Request $request)
    {
        $posts = [];
        $followers = $request->user()->followings()->pluck('leader_id');
        $friends = $request->user()->getFriends()->pluck('id');

        if (count($followers) > 0) {
            $followers->push($request->user()->id);
            $posts = Post::whereIn('user_id', $followers)
                ->orderBy('created_at', 'desc')
                ->get();
        } else if (count($friends) > 0) {
            $friends->push($request->user()->id);
            $posts = Post::whereIn('user_id', $friends)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $posts = $request->user()->posts()->orderBy('created_at', 'desc')->get();
        }

        return response(new PostCollection($posts));

    }

    /**
     * Método para crear un post.  Se valida que el cuerpo del post no sea vacío y requiere autenticación de usuario.
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param CreatePostRequest $request
     * @param Request body, video y/o imagen
     * @return PostResource|void
     */
    public function create(CreatePostRequest $request)
    {
        $user = $request->user();

        $post = new Post([
            'body' => $request->body,
        ]);

        $user->posts()->save($post);

        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $posImage = new PostImage();
                $userImage = UserImage::find($image);

                $posImage->post()->associate($post->id);
                $posImage->userImage()->associate($userImage->id);

                $posImage->save();
            }
        }

        if ($request->has('videos')) {
            foreach ($request->videos as $video) {
                $postVideo = new PostVideo();
                $userVideo = UserVideo::find($video);

                $postVideo->post()->associate($post->id);
                $postVideo->userVideo()->associate($userVideo->id);

                $postVideo->save();
            }
        }

        return new PostResource($post);
    }

    /**
     * Listar o mostrar un  post.  Se realiza una instancia de PostResourse (array)
     *
     * @access public
     * @author Kevin Cifuentes
     * @param Post $post
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Actualizar un post
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request body, video y/o imagen
     * @param Post $post
     */
    public function update(CreatePostRequest $request, Post $post)
    {
        $post->body = $request->body;
        $post->save();

        return new PostResource($post);
    }

    /**
     * Eliminar un post
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request id del post
     * @param Post $post
     */
    public function delete(Request $request, Post $post)
    {
        $post->delete();

        return response([
            'message' => __('app.post.delete_post'),
            'data' => null,
        ]);
    }

}
