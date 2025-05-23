<?php

namespace App\Http\Controllers\Asaas;


use App\Http\Controllers\Response;
use App\Http\Services\Asaas\AsaasClient;
use App\Http\Services\Asaas\Data\CustomerInputData;
use App\Http\Services\Asaas\Exception\AsaasClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use JsonException;

final class CreateCustomersController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly AsaasClient $service,
    ) {
    }

    public function __invoke(CustomerInputData $customerInputData): JsonResponse
    {
        try {
            $asaasCustomer = $this->service->createCustomer($customerInputData);
        } catch (AsaasClientException|JsonException $exception) {
            return $this->response->withError($exception->getMessage(), $exception->getCode());
        }

        return $this->response->withCreated($asaasCustomer);
    }
}
