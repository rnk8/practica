<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Comment $post)
    {
        try {
            $comments = $post->get();
            return ResponseHelper::success(
                'success',
                'Lista de comentarios obtenida con éxito',
                $comments,
                200
            );
        } catch (\Throwable $th) {
            Log::error('Error al obtener los comentarios: ' . $th->getMessage());
            return ResponseHelper::error('error', 'No se pudieron obtener los comentarios', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, string $id)
    {
        try {
            $comment = Comment::create([
                'content' => $request->content,
                'post_id' => $id,
                'user_id' => Auth::id(),
            ]);
            return ResponseHelper::success('success', 'Comentario registrado con exito', $comment, 201);
        } catch (\Throwable $th) {
            Log::error('No Se Pudo' . $th->getMessage() . 'En la linea' . $th->getLine());
            return ResponseHelper::error('error', 'Error al registrar el Comentario', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comments = Comment::where('post_id', $id)->with('user')->get()->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ],
                ];
            });
    
            return response()->json(['data' => $comments]);
        } catch (\Throwable $th) {
            Log::error('Error al obtener los comentarios: ' . $th->getMessage());
            return ResponseHelper::error('error', 'No se pudieron obtener los comentarios', 500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $post, $id)
    {
        $user_id = Auth::id();
        $id = Comment::find($id);
        if ($post->user_id !== $user_id){
            return ResponseHelper::error('error', 'No tienes Permiso', 400);
        }
        return $id->delete();
    }
}
