<?php

namespace App\Http\Services\Asaas\Data\Payment\Input;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
final class InterestData extends Data
{
    public function __construct(
        public readonly string|Optional $id,
        public readonly string|Optional $due_date,
        public readonly ?float $value,
        public readonly string|Optional $status,
    ) {
    }
}
