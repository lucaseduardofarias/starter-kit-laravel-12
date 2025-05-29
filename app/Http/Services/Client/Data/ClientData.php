<?php

namespace App\Http\Services\Client\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class ClientData extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $cpf_cnpj,
        public readonly int|null|Optional $id,
        public readonly string|null|Optional $asaas_id,
        public readonly string|null|Optional $email,
        public readonly string|null|Optional $phone,
        public readonly string|null|Optional $postal_code,
        public readonly string|null|Optional $address,
        public readonly string|null|Optional $address_number,
        public readonly string|null|Optional $complement,
        public readonly string|null|Optional $province,
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
