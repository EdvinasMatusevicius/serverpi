<?php

declare(strict_types = 1);

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class ApiResponse
 * @package App\Http\Responses
 */
class ApiResponse
{
    /**
     * @var null
     */
    private $status = null;

    /**
     * @param null $data
     * @return JsonResponse
     */
    public function success($data = null): JsonResponse
    {
        $response = $this->base();

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, JsonResponse::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function modelNotFound(): JsonResponse
    {
        $response = $this->setStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)->base();
        $response['message'] = 'No result found.';

        return response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function exception(?string $message = null): JsonResponse
    {
        $response = $this->setStatus(JsonResponse::HTTP_BAD_REQUEST)->base();
        $response['message'] = $message ?? 'Something wrong.';

        return response()->json($response, JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function unauthorized(?string $message = null): JsonResponse
    {
        $response = $this->setStatus(JsonResponse::HTTP_UNAUTHORIZED)->base();
        $response['message'] = $message ?? 'Unauthorized.';

        return response()->json($response, JsonResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * @param int|null $status
     * @return $this
     */
    public function setStatus(int $status = null): ApiResponse
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return array
     */
    protected function base(): array
    {
        return [
            'status' => $this->status ?? JsonResponse::HTTP_OK,
            'time' => Carbon::now()->timestamp,
            'message' => '',
        ];
    }

}