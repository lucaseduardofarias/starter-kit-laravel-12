<?php

namespace App\Http\Services\Asaas\Data\Payment\Input;

use App\Http\Services\Payment\Enum\PaymentTypeEnum;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
final class PaymentInputData extends Data
{
    public function __construct(
        public ?string $customer,
        #[WithCast(EnumCast::class, PaymentTypeEnum::class)]
        public PaymentTypeEnum $billingType,
        public float $value,
        public string $dueDate,
        public ?string $description,
        public ?string $installment,
        public string|Optional $externalReference,
        public int|Optional $daysAfterDueDateToRegistrationCancellation,
        public int|Optional $installmentCount,
        public float|Optional $totalValue,
        public float|Optional $installmentValue,
        public Optional|DiscountData $discount,
        public Optional|InterestData $interest,
        public Optional|FineData $fine,
        public Optional|CreditCardData $creditCard,
        public Optional|CreditCardHolderInfoData $creditCardHolderInfo,
    ) {
    }
}
