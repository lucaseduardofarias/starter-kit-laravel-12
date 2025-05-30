<?php

namespace App\Http\Services\Asaas\Data\Payment\Input;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
final class CreditCardData extends Data
{
    public function __construct(
        public string $holderName,
        public string|int $number,
        public int $expiryMonth,
        public int $expiryYear,
        public int $ccv,
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
                'integer',
                'min_digits:2',
                'max_digits:2',
            ],
            'expiry_year' => [
                'required',
                'integer',
                'min_digits:4',
                'max_digits:4',
            ],
            'ccv' => [
                'required',
                'integer',
                'min_digits:3',
                'max_digits:3',
            ],
        ];
    }
}
