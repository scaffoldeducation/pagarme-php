<?php

namespace PagarMe;

use GuzzleHttp\Exception\ClientException;
use PagarMe\Exceptions\PagarMeException;
use PagarMe\Exceptions\InvalidJsonException;

class ResponseHandler
{
    /**
     * @param string $payload
     *
     * @return array
     *@throws InvalidJsonException
     */
    public static function success(string $payload): array
    {
        return self::toArray($payload);
    }

    /**
     * @param ClientException $originalException
     *
     * @throws PagarMeException
     * @return void
     */
    public static function failure(\Exception $originalException): void
    {
        throw self::parseException($originalException);
    }

    /**
     * @param ClientException $guzzleException
     *
     * @return PagarMeException
     */
    private static function parseException(ClientException $guzzleException): PagarMeException
    {
        $response = $guzzleException->getResponse();
    
        $body = $response->getBody()->getContents();

        try {
            $responseAsArray = self::toArray($body);
        } catch (InvalidJsonException $invalidJson) {
            $responseAsArray = [];
        }

        return new PagarMeException(
            $responseAsArray["message"] ?? "An error occurred",
            $response->getStatusCode(),
            $responseAsArray["errors"] ?? [],
            $body
        );
    }
    
    /**
     * @param string $json
     * @return array
     * @throws InvalidJsonException
     */
    private static function toArray(string $json): array
    {
        $result = json_decode($json, true);

        if (json_last_error() != \JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error_msg());
        }

        return $result;
    }
}
