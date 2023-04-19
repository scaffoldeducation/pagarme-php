<?php

namespace PagarMe\Endpoints;

use PagarMe\Exceptions\PagarMeException;
use PagarMe\Routes;

class Orders extends Endpoint
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
            Routes::orders()->base(),
            ['json' => $payload]
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function addCharge(array $payload): array
    {
        return $this->client->request(
            self::POST,
            Routes::orders()->addCharge(),
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
            Routes::orders()->base(),
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
            Routes::orders()->details($payload['id'])
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function closed(array $payload): array
    {
        return $this->client->request(
            self::PATCH,
            Routes::orders()->closed($payload['id']),
            ['json' => $payload]
        );
    }
}
