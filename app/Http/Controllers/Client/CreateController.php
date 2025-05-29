<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Response;
use App\Http\Services\Client\Data\ClientData;
use App\Http\Services\Client\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class CreateController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly ClientService $service,
    ) {
    }

    public function __invoke(ClientData $clientData): JsonResponse
    {
        $client = $this->service->create($clientData);

        return $this->response->withCreated($client);
    }
}
