<?php

namespace App\Http\Services\Payment;

use App\Http\Services\Asaas\AsaasService;
use App\Http\Services\Asaas\Data\Customer\CustomerInputData;
use App\Http\Services\Asaas\Data\Payment\Input\PaymentInputData;
use App\Http\Services\Asaas\Exception\AsaasClientException;
use App\Http\Services\Payment\Enum\PaymentTypeEnum;
use App\Models\Client;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\DB;

final readonly class PaymentService
{
    public function __construct(
        private AsaasService $service,
    ) {
    }

    /**
     * @throws AsaasClientException
     * @throws \JsonException
     * @throws Exception
     */
    public function create(int $clientId, PaymentInputData $data): Payment
    {
        /** @var Client $client */
        $client = Client::query()->findOrFail($clientId);

        DB::beginTransaction();

        $this->setCustomer($client);

        if (!$client->asaas_id) {
            throw new Exception('Não foi possivel criar o cliente na integração de pagamento, tente mais tarde');
        }

        $data->customer = $client->asaas_id;

        $payment = Payment::query()->create([
            'client_id' => $client->id,
            'customer' => $client->asaas_id,
            'billing_type' => $data->billingType,
            'due_date' => $data->dueDate,
            'value' => $data->value,
            'installment' => $data->installment ?? null,
            'description' => $data->description ?? null,
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

    public function paymentByBillingType(string $clientId, string $paymentId): array
    {
        $payment = Payment::query()
            ->where('client_id', '=', $clientId)
            ->whereKey($paymentId)
            ->firstOrFail();

        switch ($payment->billing_type){
            case PaymentTypeEnum::PIX:
                $data = $this->service->pixQrCode($payment->asaas_id)->toArray();
                break;
            case PaymentTypeEnum::BOLETO:
                $data = ['bank_slip_url' => $payment->bank_slip_url];
                break;
        }

        return $data;
    }


    public function updateStatusPayment(string $clientId, string $paymentId): Payment
    {
        $payment = Payment::query()
            ->where('client_id', '=', $clientId)
            ->whereKey($paymentId)
            ->firstOrFail();


        $newStatus = $this->service->payment($payment->asaas_id);

        $payment->update([
            'status' => $newStatus->status,
        ]);

        return $payment;
    }

    /**
     * @throws AsaasClientException
     * @throws \JsonException
     */
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
