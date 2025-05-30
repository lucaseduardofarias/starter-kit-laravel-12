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
        public readonly string $cpf_cnpj,
        public readonly ?string $name,
        public readonly int|null $id,
        public readonly string|null $asaas_id,
        public readonly string|null $email,
        public readonly string|null $phone,
        public readonly string|null $postal_code,
        public readonly string|null $address,
        public readonly string|null $address_number,
        public readonly string|null $complement,
        public readonly string|null $province,
    ) {}

    public static function rules(): array
    {
        return [
            'cpf_cnpj' => [
                'required',
                'string',
                'cpf_ou_cnpj'
            ],
        ];
    }
}
