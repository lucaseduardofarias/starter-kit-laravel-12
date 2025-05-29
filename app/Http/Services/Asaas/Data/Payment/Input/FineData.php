<?php

namespace App\Http\Services\Asaas\Data\Payment\Input;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
final class FineData extends Data
{
    public function __construct(
        public ?float $value,
        public string|Optional $type,
    ) {
    }
}
