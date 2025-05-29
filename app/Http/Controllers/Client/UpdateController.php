<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Response;
use App\Http\Services\Client\ClientService;
use App\Http\Services\Client\Data\ClientData;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class UpdateController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly ClientService $service,
    ) {
    }

    public function __invoke(string $id, ClientData $clientData): JsonResponse
    {
        $client = $this->service->update($id, $clientData);

        return $this->response->jsonData($client);
    }
}
