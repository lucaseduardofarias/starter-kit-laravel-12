<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Response;
use App\Http\Services\Payment\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class UpdateStatusController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly PaymentService $service,
    ) {
    }

    public function __invoke(int $clientId, string $paymentId): JsonResponse
    {
        try {
            $payment = $this->service->updateStatusPayment($clientId, $paymentId);
        } catch (Exception $exception) {
            return $this->response->withError($exception->getMessage(), 400);
        }

        return $this->response->jsonData($payment);
    }
}
