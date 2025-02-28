<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        $posts = $this->postService->getAllPosts($request->per_page ?? 15);
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = $this->postService->getPostById($id);
        return new PostResource($post);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $post = $this->postService->createPost($data);
        return new PostResource($post);
    }

    public function update(StorePostRequest $request, $id)
    {
        $data = $request->validated();
        $post = $this->postService->updatePost($id, $data);
        return new PostResource($post);
    }

    public function destroy($id)
    {
        $this->postService->deletePost($id);
        return response()->json(['message' => 'Post eliminado correctamente'], 200);
    }
}
