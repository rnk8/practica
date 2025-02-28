<?php
// app/Services/CommentService.php
namespace App\Services;

use App\Exceptions\UnauthorizedException;
use App\Repositories\CommentRepository;
use App\Events\CommentCreated;

class CommentService
{
    protected $commentRepository;
    
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function getPostComments($postId, $perPage = 15)
    {
        return $this->commentRepository->getByPostId($postId, $perPage);
    }
    
    public function getCommentById($id)
    {
        return $this->commentRepository->getById($id);
    }
    
    public function createComment(array $data)
    {
        $comment = $this->commentRepository->create($data);
        
        // Disparar evento
        event(new CommentCreated($comment));
        
        return $comment;
    }
    
    public function updateComment($id, array $data)
    {
        $comment = $this->commentRepository->getById($id);
        
        // Verificar autorización (alternativa a las policies)
        if (auth()->id() !== $comment->user_id) {
            throw new UnauthorizedException('No estás autorizado para editar este comentario');
        }
        
        return $this->commentRepository->update($comment, $data);
    }
    
    public function deleteComment($id)
    {
        $comment = $this->commentRepository->getById($id);
        
        // Verificar autorización
        if (auth()->id() !== $comment->user_id) {
            throw new UnauthorizedException('No estás autorizado para eliminar este comentario');
        }
        
        return $this->commentRepository->delete($comment);
    }
}
