<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Response;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class IndexController extends Controller
{
    public function __construct(
        private readonly Response $response,
    ) {
    }

    public function __invoke(string $cpfCnpj): JsonResponse
    {
        $cpfCnpj = str_replace(['.', '-', '/'], '', $cpfCnpj);

        try {
            $client = Client::query()
                ->where('cpf_cnpj', '=', $cpfCnpj)
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            return $this->response->withNotFound('Client not found');
        }


        return $this->response->jsonData($client);
    }
}
