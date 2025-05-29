<?php

namespace App\Http\Services\Payment\Type;

use App\Http\Services\Asaas\AsaasService;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Data\Payment\Output\PaymentOutputData;
use App\Http\Services\Payment\Enum\PaymentTypeEnum;
use App\Http\Services\Payment\Interfaces\PaymentServiceInterface;

final readonly class PixService implements PaymentServiceInterface
{
    public function __construct(
        private AsaasService $service,
    ) {
    }
    public function payment(PaymentInputData $data): PaymentOutputData
    {
        $dataPix = PaymentInputData::validateAndCreate([
            "customer" => $data->customer,
            "billing_type" => PaymentTypeEnum::PIX,
            "value" => $data->value,
            "due_date" => $data->dueDate,
        ]);

        return $this->service->createPayment($dataPix);
    }
}
