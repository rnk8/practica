<?php
namespace App\Repositories;

use App\Models\Post;

class PostRepository
{
    public function getAll($perPage = 15)
    {
        return Post::with(['user', 'comments', 'likes'])
                  ->latest()
                  ->paginate($perPage);
    }
    
    public function getById($id)
    {
        return Post::with(['user', 'comments.user', 'likes'])
                  ->findOrFail($id);
    }
    
    public function create(array $data)
    {
        return Post::create($data);
    }
    
    public function update(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }
    
    public function delete(Post $post)
    {
        return $post->delete();
    }
    
    public function getPopular($limit = 5)
    {
        return Post::popular()->limit($limit)->get();
    }
}