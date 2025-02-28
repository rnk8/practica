<?php
// app/Events/ContentLiked.php
namespace App\Events;

use App\Models\Like;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContentLiked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }
}

