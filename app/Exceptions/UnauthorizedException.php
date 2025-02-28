<?php

// app/Exceptions/UnauthorizedException.php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UnauthorizedException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => $this->getMessage() ?: 'No estás autorizado para realizar esta acción'
        ], Response::HTTP_FORBIDDEN);
    }
}