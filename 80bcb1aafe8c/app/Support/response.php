<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

function response_json(?string $message = null, array|JsonResource|string $data = [], int $code = Response::HTTP_OK): JsonResponse
{
    return response()->json([
        'message' => $message ?? __('Successful'),
        'data' => $data,
    ], $code);
}

function error_msg(?string $message = null): string
{
    return __($message ?? 'Error');
}

function code(mixed $code): int
{
    if (gettype($code) !== 'integer') {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    return ($code >= 200 && $code <= 599) ? $code : Response::HTTP_INTERNAL_SERVER_ERROR;
}
