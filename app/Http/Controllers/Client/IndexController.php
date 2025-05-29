<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Response;
use App\Http\Services\Client\Data\ClientData;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class IndexController extends Controller
{
    public function __construct(
        private readonly Response $response,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $client = Client::query()->first();

        return $this->response->jsonData($client);
    }
}
