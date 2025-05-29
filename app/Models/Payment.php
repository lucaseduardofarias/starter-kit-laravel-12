<?php

namespace App\Models;

use App\Http\Services\Payment\Enum\PaymentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'client_id',
        'asaas_id',
        'customer',
        'billing_type',
        'due_date',
        'value',
        'installment',
        'installment_token',
        'description',
        'bank_slip_url',
        'status',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected function casts(): array
    {
        return [
            'client_id' => 'int',
            'asaas_id' => 'string',
            'customer' => 'string',
            'billing_type' => PaymentTypeEnum::class,
            'due_date' => 'date:Y-m-d',
            'value' => 'float',
            'installment' => 'int',
            'installment_token' => 'string',
            'description' => 'string',
            'bank_slip_url' => 'string',
            'status' => 'string',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
}
