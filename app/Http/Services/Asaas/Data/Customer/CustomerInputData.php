<?php

namespace App\Http\Services\Asaas\Data\Customer;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class CustomerInputData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $cpf_cnpj,
        public readonly string $external_reference,
        public readonly string|Optional $id,
        public readonly string|Optional $email,
        public readonly string|Optional $phone,
        public readonly string|Optional $postal_code,
        public readonly string|Optional $address,
        public readonly string|Optional $address_number,
        public readonly string|Optional $complement,
        public readonly string|Optional $province,
    ) {}

    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'cpf_cnpj' => [
                'required',
                'string',
                'cpf_ou_cnpj'
            ],
        ];
    }
}
