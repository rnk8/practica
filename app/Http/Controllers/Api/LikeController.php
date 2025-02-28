<?php
// app/Http/Controllers/Api/LikeController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Services\LikeService;

class LikeController extends Controller
{
    protected $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function likePost(Post $post)
    {
        $userId = auth()->id();

        if ($this->likeService->hasLiked($userId, $post)) {
            return response()->json(['message' => 'Ya has dado like a este post'], 422);
        }

        $this->likeService->like($userId, $post);
        return response()->json(['message' => 'Like registrado correctamente'], 201);
    }

    public function unlikePost(Post $post)
    {
        $userId = auth()->id();

        if (!$this->likeService->hasLiked($userId, $post)) {
            return response()->json(['message' => 'No has dado like a este post'], 422);
        }

        $this->likeService->unlike($userId, $post);
        return response()->json(['message' => 'Like eliminado correctamente'], 200);
    }

    public function likeComment(Comment $comment)
    {
        $userId = auth()->id();

        if ($this->likeService->hasLiked($userId, $comment)) {
            return response()->json(['message' => 'Ya has dado like a este comentario'], 422);
        }

        $this->likeService->like($userId, $comment);
        return response()->json(['message' => 'Like registrado correctamente'], 201);
    }

    public function unlikeComment(Comment $comment)
    {
        $userId = auth()->id();

        if (!$this->likeService->hasLiked($userId, $comment)) {
            return response()->json(['message' => 'No has dado like a este comentario'], 422);
        }

        $this->likeService->unlike($userId, $comment);
        return response()->json(['message' => 'Like eliminado correctamente'], 200);
    }
}
