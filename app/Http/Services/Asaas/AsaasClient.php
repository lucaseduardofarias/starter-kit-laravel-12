<?php

namespace App\Http\Services\Asaas;

use App\Http\Services\Asaas\Data\CustomerInputData;
use App\Http\Services\Asaas\Data\CustomerOutputData;
use App\Http\Services\Asaas\Exception\AsaasClientException;
use App\Http\Traits\GuzzleResponseTrait;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonException;

/**
 * @internal
 */
final class AsaasClient
{
    use GuzzleResponseTrait;

    public const ENDPOINT_CUSTOMERS = 'customers';
    private Client $clientGuzzle;
    public function __construct()
    {
        $this->clientGuzzle = new Client([
            'base_uri' => config('asaas.base_url'),
            RequestOptions::HEADERS  => [
                'accept'  => 'application/json',
                'content-type' => 'application/json',
                'access_token'  => config('asaas.token'),
            ],
        ]);
    }

    /**
     * @throws AsaasClientException
     * @throws JsonException
     */
    public function createCustomer(CustomerInputData $data): CustomerOutputData
    {
        dd('nem chega aqui');
        $options = [
            RequestOptions::JSON => [
                'externalReference' => $data->external_reference,
                'cpfCnpj' => $data->cpf_cnpj,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'mobilePhone' => $data->mobile_phone,
                'postalCode' => $data->postal_code,
                'address' => $data->address,
                'addressNumber' => $data->address_number,
                'complement' => $data->complement,
                'province' => $data->province,
            ],
        ];

        try {
            $response = $this->clientGuzzle->post(self::ENDPOINT_CUSTOMERS, $options);
        } catch (GuzzleException $exception) {
            $responseData = json_decode($exception->getResponse()?->getBody()?->getContents() ?? '', true);

            $errors = $responseData['errors'] ?? [];
            $messages = array_map(fn(array $err) => $err['description'] ?? $err['code'] ?? 'Erro desconhecido', $errors);
            $fullMessage = count($messages) ? implode('; ', $messages) : 'Erro não identificado no Asaas.';
            throw new AsaasClientException(
                message: $fullMessage,
                code: $exception->getCode(),
                previous: $exception,
            );
        } catch (Exception $exception) {
            throw new AsaasClientException('Problema não identificado na criação de conta do Asaas.', 400, $exception);
        }

        return $this->toDataObject($response, CustomerOutputData::class);
    }


}
