<?php

namespace PagarMe\Endpoints;

use PagarMe\Exceptions\PagarMeException;
use PagarMe\Routes;

class Customers extends Endpoint
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
            Routes::customers()->base(),
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
            Routes::customers()->details($payload['customer_id']),
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
            Routes::customers()->base(),
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
            Routes::customers()->details($payload['id'])
        );
    }
}
