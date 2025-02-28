<?php

// app/Repositories/CommentRepository.php
namespace App\Repositories;

use App\Models\Comment;

class CommentRepository
{
    public function getByPostId($postId, $perPage = 15)
    {
        return Comment::with(['user', 'likes'])
                    ->where('post_id', $postId)
                    ->latest()
                    ->paginate($perPage);
    }
    
    public function getById($id)
    {
        return Comment::with(['user', 'likes'])
                    ->findOrFail($id);
    }
    
    public function create(array $data)
    {
        return Comment::create($data);
    }
    
    public function update(Comment $comment, array $data)
    {
        $comment->update($data);
        return $comment;
    }
    
    public function delete(Comment $comment)
    {
        return $comment->delete();
    }
}
