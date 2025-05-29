<?php

namespace App\Http\Services\Payment;

use App\Http\Services\Asaas\AsaasService;
use App\Http\Services\Asaas\Data\Customer\CustomerInputData;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

final class PaymentService
{
    public function __construct(
        private readonly AsaasService $service,
    ) {
    }

    public function create(int $clientId, PaymentInputData $data): Payment
    {
        /** @var Client $client */
        $client = Client::query()->findOrFail($clientId);

        DB::beginTransaction();

        $this->setCustomer($client);

        if (!$client->asaas_id) {
            throw new \RuntimeException('Failed to retrieve or create customer for client ID: ' . $clientId);
        }

        $data->customer = $client->asaas_id;

        $payment = Payment::query()->create([
            'client_id' => $client->id,
            'customer' => $client->asaas_id,
            'billing_type' => $data->billingType,
            'due_date' => $data->dueDate,
            'value' => $data->value,
            'installment' => $data->installment,
            'description' => $data->description,
        ]);

        $service = $data->billingType->service();

        $paymentData = $service->payment($data);

        $payment->update([
            'asaas_id' => $paymentData->id,
            'due_date' => $paymentData->dueDate,
            'value' => $paymentData->value,
            'bank_slip_url' => $paymentData->bankSlipUrl,
            'status' => $paymentData->status,
        ]);

        DB::commit();

        return $payment;
    }

    public function setCustomer(Client $client): void
    {
        if ($client->asaas_id) {
            return;
        }

        $customer = $this->service->createOrUpdateCustomer(
            null,
            CustomerInputData::validateAndCreate([
                'cpf_cnpj' => $client->cpf_cnpj,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'postal_code' => $client->postal_code,
                'address' => $client->address,
                'address_number' => $client->address_number,
                'complement' => $client->complement,
                'province' => $client->province,
                'external_reference' => (string) $client->id,
            ])
        );

        $client->update(['asaas_id' => $customer->id]);
    }
}
