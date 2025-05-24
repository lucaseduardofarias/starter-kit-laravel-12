<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'asaas_id',
        'cpf_cnpj',
        'name',
        'email',
        'phone',
        'postal_code',
        'address',
        'address_number',
        'complement',
        'province',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    protected function casts(): array
    {
        return [
            'asaas_id' => 'string',
            'cpf_cnpj' => 'string',
            'name' => 'string',
            'email' => 'string',
            'phone' => 'string',
            'postal_code' => 'string',
            'address' => 'string',
            'address_number' => 'string',
            'complement' => 'string',
            'province' => 'string',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }
}
