<?php

namespace App\Http\Controllers;

use App\Support\Enums\LogLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class Controller
{
    public function __invoke(): JsonResponse
    {
        try {
            return $this->handle();
        } catch (ValidationException $e) {
            log_msg(LogLevel::WARNING, $e->getMessage(), $e->getTrace());

            return response_json(
                message: error_msg('Invalid data'),
                data: (array)$e->validator->errors()->messages(),
                code: Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (Throwable $e) {
            log_msg(LogLevel::ERROR, $e->getMessage(), $e->getTrace());

            return response_json(
                message: config('app.debug') ? $e->getMessage() : error_msg(),
                data: config('app.debug') ? (array)$e->getFile() : [],
                code: code($e->getCode()),
            );
        }
    }

    abstract protected function handle(): JsonResponse;
}
