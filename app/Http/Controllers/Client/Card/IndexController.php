<?php

namespace App\Http\Controllers\Client\Card;

use App\Http\Controllers\Response;
use App\Models\Card;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class IndexController extends Controller
{
    public function __construct(
        private readonly Response $response,
    ) {
    }

    public function __invoke(string $id): JsonResponse
    {
        $cards = Card::query()
            ->where('client_id', '=', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->response->jsonData($cards);
    }
}
