<?php
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request, $postId)
    {
        $comments = $this->commentService->getPostComments($postId, $request->per_page ?? 15);
        return CommentResource::collection($comments);
    }

    public function show($id)
    {
        $comment = $this->commentService->getCommentById($id);
        return new CommentResource($comment);
    }

    public function store(StoreCommentRequest $request, $postId)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['post_id'] = $postId;

        $comment = $this->commentService->createComment($data);
        return new CommentResource($comment);
    }

    public function update(StoreCommentRequest $request, $id)
    {
        $data = $request->validated();
        $comment = $this->commentService->updateComment($id, $data);
        return new CommentResource($comment);
    }

    public function destroy($id)
    {
        $this->commentService->deleteComment($id);
        return response()->json(['message' => 'Comentario eliminado correctamente'], 200);
    }
}

