<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

final class Response
{
    private ResponseFactory $response;
    private int $statusCode = FoundationResponse::HTTP_OK;

    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = !empty(FoundationResponse::$statusTexts[$statusCode]) ? $statusCode : FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return a 201 response with the given created resource.
     */
    public function withCreated(mixed $data = null): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_CREATED);

        return $this->json([
            'success' => true,
            'message' => JsonResponse::$statusTexts[FoundationResponse::HTTP_CREATED],
            'data' => $data,
        ]);
    }

    /**
     * Return a 202 response.
     */
    public function withAccepted(?string $message = null, mixed $data = null): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_ACCEPTED);

        return $this->json([
            'success' => true,
            'message' => $message ?? JsonResponse::$statusTexts[FoundationResponse::HTTP_ACCEPTED],
            'data' => $data ?? [],
        ]);
    }

    /**
     * Make a 204 response.
     */
    public function withNoContent(): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_NO_CONTENT);

        return $this->json();
    }

    /**
     * Return a 400 response.
     */
    public function withBadRequest(string $message = 'Bad Request'): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_BAD_REQUEST);

        return $this->withError($message);
    }

    /**
     * Return a 401 response.
     */
    public function withUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_UNAUTHORIZED);

        return $this->withError($message);
    }

    /**
     * Return a 403 response.
     */
    public function withForbidden(string $message = 'Forbidden'): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_FORBIDDEN);

        return $this->withError($message);
    }

    /**
     * Return a 404 response.
     */
    public function withNotFound(string $message = 'Not Found'): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_NOT_FOUND);

        return $this->withError($message);
    }

    /**
     * Return a 422 response.
     */
    public function withUnprocessableEntity(?string $message = null, mixed $data = null): JsonResponse
    {
        $this->setStatusCode(FoundationResponse::HTTP_UNPROCESSABLE_ENTITY);

        return $this->json([
            'success' => false,
            'message' => $message ?? JsonResponse::$statusTexts[FoundationResponse::HTTP_UNPROCESSABLE_ENTITY],
            'data' => $data ?? [],
        ]);
    }

    /**
     * Make an error response.
     */
    public function withError(mixed $message, ?int $statusCode = null): JsonResponse
    {
        if (!is_null($statusCode)) {
            $this->setStatusCode($statusCode);
        }

        return $this->json([
            'success' => false,
            'message' => (is_array($message) ? $message : [$message]),
        ]);
    }

    /**
     * Make a JSON Data response.
     */
    public function jsonData(mixed $dataRaw = [], array $headers = []): JsonResponse
    {
        $data = [
            'success' => true,
            'message' => JsonResponse::$statusTexts[FoundationResponse::HTTP_OK],
            'data' => $dataRaw,
        ];

        return $this->json($data, $headers);
    }

    /**
     * Make a JSON response.
     */
    public function json(mixed $data = [], array $headers = []): JsonResponse
    {
        return $this->response->json($data, $this->statusCode, $headers);
    }
}
