<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

/**
 * Standardized JSON response helpers shared across all API controllers.
 *
 * Envelope:
 *   success → { "success": true, "message": ..., "data": ... }
 *   error   → { "success": false, "message": ..., "errors": ... }
 *
 * Paginated resource collections keep Laravel's native "meta"/"links" keys.
 */
trait ApiResponse
{
    protected function respondSuccess(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        // Let API Resources / paginators render themselves while still wrapping
        // a consistent top-level envelope.
        if ($data instanceof JsonResource || $data instanceof ResourceCollection || $data instanceof AbstractPaginator) {
            return $this->mergeEnvelope($data->response()->getData(true), $message, $status);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function respondCreated(mixed $data = null, string $message = 'Created successfully.'): JsonResponse
    {
        return $this->respondSuccess($data, $message, 201);
    }

    protected function respondNoContent(string $message = 'Deleted successfully.'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => null,
        ]);
    }

    protected function respondError(string $message = 'Something went wrong.', int $status = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    private function mergeEnvelope(array $payload, string $message, int $status): JsonResponse
    {
        // $payload already contains "data" (and "meta"/"links" when paginated).
        return response()->json(array_merge([
            'success' => true,
            'message' => $message,
        ], $payload), $status);
    }
}
