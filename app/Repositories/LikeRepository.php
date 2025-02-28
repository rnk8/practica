<?php

// app/Repositories/LikeRepository.php
namespace App\Repositories;

use App\Models\Like;
use Illuminate\Database\Eloquent\Model;

class LikeRepository
{
    public function hasLiked(int $userId, Model $likeable): bool
    {
        return Like::where('user_id', $userId)
                  ->where('likeable_id', $likeable->id)
                  ->where('likeable_type', get_class($likeable))
                  ->exists();
    }
    
    public function create(int $userId, Model $likeable): Like
    {
        return Like::create([
            'user_id' => $userId,
            'likeable_id' => $likeable->id,
            'likeable_type' => get_class($likeable)
        ]);
    }
    
    public function delete(int $userId, Model $likeable): bool
    {
        return Like::where('user_id', $userId)
                  ->where('likeable_id', $likeable->id)
                  ->where('likeable_type', get_class($likeable))
                  ->delete();
    }
    
    public function getLikesByLikeable(Model $likeable, $perPage = 15)
    {
        return Like::with('user')
                  ->where('likeable_id', $likeable->id)
                  ->where('likeable_type', get_class($likeable))
                  ->paginate($perPage);
    }
}
