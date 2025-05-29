<?php

namespace App\Http\Controllers\Client\Payment;

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

    /**
     * @throws ModelNotFoundException
     */
    public function __invoke(string $id): JsonResponse
    {
        $payments = Client::query()->with('payments', function ($query) {
            $query->orderBy('created_at', 'desc');
        })->findOrFail($id);

        return $this->response->jsonData($payments);
    }
}
