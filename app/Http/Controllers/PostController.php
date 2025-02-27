<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\PostRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $posts = Post::with('likes')->get()->map(function ($post) use ($userId) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    'likes_count' => $post->likes()->count(),
                    'liked_by_user' => $post->likes()->where('user_id', $userId)->exists(),
                    'author' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                    ],
                ];
            });

            return response()->json([
                'data' => $posts,
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al obtener los comentarios: ' . $th->getMessage());
            return ResponseHelper::error('error', 'No se pudieron obtener los comentarios', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => Auth::id(),
            ]);
            return ResponseHelper::success('success', 'Post registrado con exito', $post, 201);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al registrar el post', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $post = Post::with('user')->find($id);
            if (!$post) {
                return ResponseHelper::error('error', 'Post no encontrado', 404);
            }
            return ResponseHelper::success('success', 'Post encontrado con exito', $post, 200);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al buscar el post', 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        try {
            $post = Post::find($id);
            if (!$post) {
                return ResponseHelper::error('error', 'Post no encontrado', 404);
            }
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return ResponseHelper::success('success', 'Post actualizado con exito', $post, 200);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al actualizar el post', 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user_id = Auth::id();
        $post = Post::find($id);
        if (!$post) {
            return ResponseHelper::error('error', 'Post no encontrado', 404);
        }
        if ($post->user_id === $user_id) {
            try {
                $post->delete();
                return ResponseHelper::success('success', 'Post eliminado con exito', $post, 200);
            } catch (\Throwable $th) {
                Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
                return ResponseHelper::error('error', 'Error al actualizar el post', 400);
            }
        }
        return ResponseHelper::error('error', 'No tienes Permiso', 400);
    }


    public function posts()
    {
        try {
            $totalPosts = Post::count();
            $totalUsers = User::count();
            $totalComments = Comment::count();
            $totalLikes = Like::count();

            return ResponseHelper::success(
                'success',
                'Lista de Posts obtenida con éxito',
                [
                    'totalUsers' => $totalUsers,
                    'totalComments' => $totalComments,
                    'totalLikes' => $totalLikes,
                    'totalPosts' => $totalPosts
                ],
                200
            );
        } catch (\Throwable $th) {
            Log::error('Error al obtener los comentarios: ' . $th->getMessage());
            return ResponseHelper::error('error', 'No se pudieron obtener los comentarios', 500);
        }
    }
}
