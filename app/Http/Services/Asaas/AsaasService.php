<?php

namespace App\Http\Services\Asaas;

use App\Http\Services\Asaas\Data\Customer\CustomerInputData;
use App\Http\Services\Asaas\Data\Customer\CustomerOutputData;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Data\Payment\Output\PaymentOutputData;
use App\Http\Services\Asaas\Data\Payment\Output\PixData;
use App\Http\Services\Asaas\Exception\AsaasClientException;
use JsonException;

final class AsaasService
{
    public function __construct(
        private readonly AsaasClient $asaasClient,
    ) {
    }

    /**
     * @throws AsaasClientException
     * @throws JsonException
     */
    public function createOrUpdateCustomer(?string $id, CustomerInputData $customerInputData): CustomerOutputData
    {
        if ($id) {
            return $this->asaasClient->updateCustomer($id, $customerInputData);
        }

        return $this->asaasClient->createCustomer($customerInputData);
    }

    public function createPayment(PaymentInputData $data): PaymentOutputData
    {
        try {
            return $this->asaasClient->createPayment($data);
        } catch (AsaasClientException|JsonException $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode());
        }
    }


    public function pixQrCode(string $asass_id): PixData
    {
        try {
            return $this->asaasClient->pixQrCode($asass_id);
        } catch (AsaasClientException|JsonException $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode());
        }
    }

    public function payment(string $asass_id): PaymentOutputData
    {
        try {
            return $this->asaasClient->payment($asass_id);
        } catch (AsaasClientException|JsonException $exception) {
            throw new \RuntimeException($exception->getMessage(), $exception->getCode());
        }
    }

}
