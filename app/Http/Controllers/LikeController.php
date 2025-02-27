<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function LikePost($postId)
    {
        $userId = Auth::id();
        $post = Post::find($postId);
    
        if (!$post) {
            return ResponseHelper::error('error', 'Post not found', 404);
        }
    
        if ($post->likes()->where('user_id', $userId)->exists()) {
            return ResponseHelper::error('error', 'Ya le diste like', 400);
        }
    
        try {
            // Crear y guardar el like
            $like = new Like(['user_id' => $userId]);
            $post->likes()->save($like);
    
            // Recargar el post para obtener la cantidad correcta de likes
            $post->refresh();
    
            return ResponseHelper::success('Post liked successfully.', 201, [
                'likes_count' => $post->likes_count
            ]);
        } catch (\Exception $th) {
            Log::error('No Se Pudo ' . $th->getMessage() . ' En la línea ' . $th->getLine());
            return ResponseHelper::error('error', 'Ocurrió un error al dar like', 500);
        }
    }
    


    /**
     * Display the specified resource.
     */public function Unlike($postId)
{
    $userId = Auth::id();
    $like = Like::where('post_id', $postId)
                ->where('user_id', $userId)
                ->first();

    if (!$like) {
        return ResponseHelper::error('error', 'No has dado like a este post', 400);
    }

    $like->delete();
    return ResponseHelper::success('Post unliked successfully.', 200, ['likes_count' => Post::find($postId)->likes()->count()]);
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
    public function destroy(string $id)
    {
        //
    }
}
