<?php
namespace App\Services;

use App\Repositories\PostRepository;
use App\Events\PostCreated;

class PostService
{
    protected $postRepository;
    
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }
    
    public function getAllPosts($perPage = 15)
    {
        return $this->postRepository->getAll($perPage);
    }
    
    public function getPostById($id)
    {
        return $this->postRepository->getById($id);
    }
    
    public function createPost(array $data)
    {
        $post = $this->postRepository->create($data);
        
        // Disparar eventos si es necesario
        event(new PostCreated($post));
        
        return $post;
    }
    
    public function updatePost($id, array $data)
    {
        $post = $this->postRepository->getById($id);
        return $this->postRepository->update($post, $data);
    }
    
    public function deletePost($id)
    {
        $post = $this->postRepository->getById($id);
        return $this->postRepository->delete($post);
    }
}