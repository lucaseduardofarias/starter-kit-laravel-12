<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Response;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class ShowController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly PaymentService $service,
    ) {
    }

    public function __invoke(int $clientId, string $paymentId): JsonResponse
    {
        $payment = $this->service->paymentByBillingType($clientId, $paymentId);

        return $this->response->jsonData($payment);
    }
}
