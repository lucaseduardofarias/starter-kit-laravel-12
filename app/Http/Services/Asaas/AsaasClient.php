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
    public const ENDPOINT_CUSTOMERS_WITH_ID = 'customers/%s';

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
            $this->handleRequestException($exception);
        } catch (Exception $exception) {
            throw new AsaasClientException('Problema não identificado na criação da conta', 400, $exception);
        }

        return $this->toDataObject($response, CustomerOutputData::class);
    }

    /**
     * @throws AsaasClientException
     * @throws JsonException
     */
    public function updateCustomer(string $id, CustomerInputData $data): CustomerOutputData
    {
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
            $response = $this->clientGuzzle->put(sprintf(self::ENDPOINT_CUSTOMERS_WITH_ID, $id), $options);
        } catch (GuzzleException $exception) {
            $this->handleRequestException($exception);
        } catch (Exception $exception) {
            throw new AsaasClientException('Problema não identificado na atualização da conta.', 400, $exception);
        }

        return $this->toDataObject($response, CustomerOutputData::class);
    }

    /**
     * @throws AsaasClientException
     */
    private function handleRequestException(GuzzleException $exception): void
    {
        $body = $exception->getResponse()?->getBody()?->getContents() ?? '';
        $data = json_decode($body, true);

        $errors = $data['errors'] ?? [];
        $messages = array_map(
            fn(array $err) => $err['description'] ?? $err['code'] ?? 'Erro desconhecido',
            $errors
        );

        $fullMessage = count($messages) ? implode('; ', $messages) : 'Erro não identificado na integração.';

        throw new AsaasClientException(
            message: $fullMessage,
            code: $exception->getCode(),
            previous: $exception,
        );
    }




}
