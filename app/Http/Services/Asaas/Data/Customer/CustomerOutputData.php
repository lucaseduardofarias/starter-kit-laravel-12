<?php

namespace App\Http\Services\Asaas\Data\Customer;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(CamelCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class CustomerOutputData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $cpfCnpj,
        public readonly string|Optional $externalReference,
        public readonly string|Optional $id,
        public readonly string|Optional $email,
        public readonly string|Optional $phone,
        public readonly string|Optional $postalCode,
        public readonly string|Optional $address,
        public readonly string|Optional $addressNumber,
        public readonly string|Optional $complement,
        public readonly string|Optional $province,
    ) {}
}
