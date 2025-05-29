<?php

namespace App\Http\Services\Payment\Enum;

use App\Http\Services\Payment\Interfaces\PaymentServiceInterface;
use App\Http\Services\Payment\Type\BankTicketService;
use App\Http\Services\Payment\Type\CreditCardService;
use App\Http\Services\Payment\Type\PixService;

enum PaymentTypeEnum: string
{
    case BOLETO = 'BOLETO';
    case CREDIT_CARD = 'CREDIT_CARD';
    case PIX = 'PIX';

    public function service(): PaymentServiceInterface
    {
        return match ($this) {
            self::BOLETO => app(BankTicketService::class),
            self::CREDIT_CARD => app(CreditCardService::class),
            self::PIX => app(PixService::class),
        };
    }
}
