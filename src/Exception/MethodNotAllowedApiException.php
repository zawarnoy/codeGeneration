<?php
/**
 * Created by PhpStorm.
 * User: Влад
 * Date: 02.11.2018
 * Time: 0:11
 */

namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MethodNotAllowedApiException extends \Exception implements ApiExceptionInterface
{
    public function __construct(string $message = "Method not allowed", int $code = Response::HTTP_METHOD_NOT_ALLOWED, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}