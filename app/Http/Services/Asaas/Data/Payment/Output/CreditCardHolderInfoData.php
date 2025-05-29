<?php

namespace App\Http\Services\Asaas\Data\Payment\Output;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class CreditCardHolderInfoData extends Data
{
    public function __construct(
        public string  $name,
        public string  $phone,
        public string  $email,
        public string  $cpfCnpj,
        public string  $postalCode,
        public string  $addressNumber,
        public ?string $addressComplement,
    ) {
    }
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'phone' => [
                'required',
                'string',
                'max:11',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:50',
            ],
            'cpf_cnpj' => [
                'required',
                'string',
                'cpf_ou_cnpj',
                'min:11',
                'max:14',
            ],
            'postal_code' => [
                'required',
                'string',
                'min:8',
                'max:8',
            ],
            'address_number' => [
                'required',
                'string',
                'max:10',
            ],
            'address_complement' => [
                'nullable',
                'string',
                'max:50',
            ],
        ];
    }
}
