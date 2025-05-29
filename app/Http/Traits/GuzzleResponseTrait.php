<?php

namespace App\Http\Traits;

use InvalidArgumentException;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Spatie\LaravelData\Data;

trait GuzzleResponseTrait
{
    private ?array $contentJson = null;

    /**
     * @throws JsonException
     */
    public static function json(ResponseInterface $response, mixed $key = null, mixed $default = null): mixed
    {
        $body = $response->getBody()->getContents();
        $data = json_decode($body !== '' ? $body : '{}', true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($data)) {
            throw new JsonException('Invalid JSON content in response');
        }

        return data_get($data, $key, $default);
    }

    /**
     * @template T of Data
     * @param ResponseInterface $response
     * @param class-string<T> $dtoClass
     * @param mixed|null $key
     * @param array $default
     * @return array
     * @throws JsonException
     */
    public static function toDataCollection(
        ResponseInterface $response,
        string $dtoClass,
        mixed $key = null,
        array $default = []
    ): array {
        self::assertDtoClass($dtoClass);
        $data = self::json($response, $key, $default);
        return $dtoClass::collect($data);
    }

    /**
     * @template T<Data>
     *
     * @param class-string<T> $dtoClass
     *
     * @return T
     *
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public static function toDataObject(
        ResponseInterface $response,
        string $dtoClass,
        mixed $key = null,
        array $default = []
    ): Data {
        self::assertDtoClass($dtoClass);
        $data = self::json($response, $key, $default);
        return $dtoClass::validateAndCreate($data);
    }

    /**
     * @param class-string<Data> $dtoClass
     *
     * @throws InvalidArgumentException
     */
    private static function assertDtoClass(string $dtoClass): void
    {
        if (!class_exists($dtoClass) || !is_subclass_of($dtoClass, Data::class)) {
            throw new InvalidArgumentException('o argumento recebido não é um Laravel Data');
        }
    }
}
