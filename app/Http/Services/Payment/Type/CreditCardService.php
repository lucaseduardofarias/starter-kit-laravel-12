<?php

namespace App\Http\Services\Payment\Type;

use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Data\Payment\Output\PaymentOutputData;
use App\Http\Services\Payment\Interfaces\PaymentServiceInterface;

class CreditCardService implements PaymentServiceInterface
{

    public function payment(PaymentInputData $data): PaymentOutputData
    {
        // TODO: Implement payment() method.
    }
}
