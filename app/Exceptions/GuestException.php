<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class GuestException extends Exception
{
    public function __construct($message = "", $code = 404)
    {
        parent::__construct($message, $code);
    }

    public function render($request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

    public function report(): void
    {
        Log::error('Guest error', (array)$this->getMessage());
    }
}
