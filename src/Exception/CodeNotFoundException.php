<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CodeNotFoundException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $message = "Code not found", int $code = Response::HTTP_NOT_FOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}