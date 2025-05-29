<?php

namespace App\Http\Services\Asaas\Data\Payment\Output;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(CamelCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class PaymentOutputData extends Data
{
    public function __construct(
        public string $id,
        public float $value,
        public string $dueDate,
        public string $status,
        public null|string $bankSlipUrl,
    ) {
    }
}
