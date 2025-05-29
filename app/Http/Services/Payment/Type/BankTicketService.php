<?php

namespace App\Http\Services\Payment\Type;

use App\Http\Services\Asaas\AsaasService;
use App\Http\Services\Asaas\Data\Payment\Input\DiscountData;
use App\Http\Services\Asaas\Data\Payment\Input\FineData;
use App\Http\Services\Asaas\Data\Payment\Input\InterestData;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Data\Payment\Output\PaymentOutputData;
use App\Http\Services\Payment\Enum\PaymentTypeEnum;
use App\Http\Services\Payment\Interfaces\PaymentServiceInterface;

final class BankTicketService implements PaymentServiceInterface
{
    public function __construct(
        private AsaasService $service,
    ) {
    }
    public function payment(PaymentInputData $data): PaymentOutputData
    {
        $dataBankTicket = PaymentInputData::validateAndCreate([
            "customer" => $data->customer,
            "billing_type" => PaymentTypeEnum::BOLETO,
            "value" => $data->value,
            "due_date" => $data->dueDate,
            "description" => $data->description,
            "discount" => [
                "value" => "10",
                "due_date_limit_days" => 1,
                "type" => "PERCENTAGE",
            ],
            "fine" => [
                "value" => 1,
                "type" => "FIXED",
            ],
            "interest" => [
                "value" => 1,
            ],
        ]);

        return $this->service->createPayment($dataBankTicket);
    }
}
