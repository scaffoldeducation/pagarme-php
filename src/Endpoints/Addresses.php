<?php

namespace PagarMe\Endpoints;

use PagarMe\Exceptions\PagarMeException;
use PagarMe\Routes;

class Addresses extends Endpoint
{
    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function create(array $payload): array
    {
        return $this->client->request(
            self::POST,
            Routes::addresses()->base($payload["customer_id"]),
            ['json' => $payload]
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function update(array $payload): array
    {
        return $this->client->request(
            self::PUT,
            Routes::addresses()->details($payload["customer_id"], $payload["address_id"]),
            ['json' => $payload]
        );
    }

    /**
     * @param array|null $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function getList(array $payload = null): array
    {
        return $this->client->request(
            self::GET,
            Routes::addresses()->base($payload["customer_id"]),
            ['query' => $payload]
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function get(array $payload): array
    {
        return $this->client->request(
            self::GET,
            Routes::addresses()->details($payload["customer_id"], $payload["address_id"])
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function delete(array $payload): array
    {
        return $this->client->request(
            self::DELETE,
            Routes::addresses()->delete($payload["customer_id"], $payload["address_id"])
        );
    }
}
