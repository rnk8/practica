<?php
// app/Services/LikeService.php
namespace App\Services;

use App\Repositories\LikeRepository;
use Illuminate\Database\Eloquent\Model;
use App\Events\ContentLiked;

class LikeService
{
    protected $likeRepository;
    
    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }
    
    public function hasLiked(int $userId, Model $likeable): bool
    {
        return $this->likeRepository->hasLiked($userId, $likeable);
    }
    
    public function like(int $userId, Model $likeable)
    {
        $like = $this->likeRepository->create($userId, $likeable);
        
        // Opcional: disparar evento cuando algo recibe un like
        event(new ContentLiked($like));
        
        return $like;
    }
    
    public function unlike(int $userId, Model $likeable)
    {
        return $this->likeRepository->delete($userId, $likeable);
    }
    
    public function getLikes(Model $likeable, $perPage = 15)
    {
        return $this->likeRepository->getLikesByLikeable($likeable, $perPage);
    }
}
