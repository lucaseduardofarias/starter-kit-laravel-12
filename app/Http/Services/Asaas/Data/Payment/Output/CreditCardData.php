<?php

namespace App\Http\Services\Asaas\Data\Payment\Output;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
final class CreditCardData extends Data
{
    public function __construct(
        public string $holderName,
        public string $number,
        public string $expiryMonth,
        public string $expiryYear,
        public string $ccv,
    ) {
    }

    public static function rules(): array
    {
        return [
            'number' => [
                'required',
                'string',
                'min:13',
                'max:16',
            ],
            'holder_name' => [
                'required',
                'string',
                'max:50',
            ],
            'expiry_month' => [
                'required',
                'string',
                'min:1',
                'max:2',
            ],
            'expiry_year' => [
                'required',
                'string',
                'min:4',
                'max:4',
            ],
            'ccv' => [
                'required',
                'string',
                'min:3',
                'max:3',
            ],
        ];
    }
}
