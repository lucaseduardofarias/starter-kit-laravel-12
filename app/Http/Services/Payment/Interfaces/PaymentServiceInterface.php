<?php

namespace App\Http\Services\Payment\Interfaces;

use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Data\Payment\Output\PaymentOutputData;

interface PaymentServiceInterface
{
    public function payment(PaymentInputData $data): PaymentOutputData;
}
