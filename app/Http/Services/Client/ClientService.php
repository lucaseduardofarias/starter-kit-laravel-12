<?php

namespace App\Http\Services\Client;

use App\Http\Services\Client\Data\ClientData;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ClientService
{
    public function create(ClientData $data): Client
    {
        return Client::query()->create([
            'cpf_cnpj' => $this->cleanInput($data->cpf_cnpj),
        ]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(int $id, ClientData $data): Client
    {
        $client = Client::query()->findOrFail($id);

        $client->update([
            'cpf_cnpj' => $this->cleanInput($data->cpf_cnpj),
            'asaas_id' => $data->asaas_id,
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $this->cleanInput($data->phone),
            'postal_code' => $this->cleanInput($data->postal_code),
            'address' => $data->address,
            'address_number' => $this->cleanInput($data->address_number),
            'complement' => $data->complement,
            'province' => $data->province,
        ]);

        return $client;
    }

    private function cleanInput(string $input): string
    {
        return preg_replace('/\D/', '', $input);
    }
}
