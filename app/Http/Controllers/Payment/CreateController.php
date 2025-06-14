<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Response;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Payment\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class CreateController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly PaymentService $service,
    ) {
    }

    public function __invoke(int $id, PaymentInputData $data): JsonResponse
    {
        try {
            $payment = $this->service->create($id, $data);
        } catch (Exception $exception) {
            return $this->response->withError($exception->getMessage(), 400);
        }

        return $this->response->jsonData($payment);
    }
}
