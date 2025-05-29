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
final class InterestData extends Data
{
    public function __construct(
        public readonly string|Optional $id,
        public readonly string|Optional $due_date,
        public readonly float|Optional $value,
        public readonly string|Optional $status,
    ) {
    }
}
