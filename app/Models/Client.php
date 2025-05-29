<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $asaas_id
 * @property string $cpf_cnpj
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $postal_code
 * @property string $address
 * @property string $address_number
 * @property string $complement
 * @property string $province
 */
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
