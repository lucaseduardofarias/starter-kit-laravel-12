<?php

namespace App\Http\Controllers\Asaas\Customer;


use App\Http\Controllers\Response;
use App\Http\Services\Asaas\AsaasClient;
use App\Http\Services\Asaas\Data\CustomerInputData;
use App\Http\Services\Asaas\Exception\AsaasClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use JsonException;

final class UpdateController extends Controller
{
    public function __construct(
        private readonly Response $response,
        private readonly AsaasClient $service,
    ) {
    }

    public function __invoke(string $id, CustomerInputData $customerInputData): JsonResponse
    {
        try {
            $asaasCustomer = $this->service->updateCustomer($id, $customerInputData);
        } catch (AsaasClientException|JsonException $exception) {
            return $this->response->withError($exception->getMessage(), $exception->getCode());
        }

        return $this->response->jsonData($asaasCustomer);
    }
}
