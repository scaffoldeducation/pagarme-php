<?php

namespace PagarMe\Endpoints;

use PagarMe\Exceptions\PagarMeException;
use PagarMe\Routes;

class OrderItems extends Endpoint
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
            Routes::orderItems()->base($payload["order_id"]),
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
            Routes::orderItems()->update($payload['order_id'], $payload['item_id']),
            ['json' => $payload]
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
            Routes::orderItems()->details($payload['order_id'], $payload['item_id'])
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
            Routes::orderItems()->delete($payload['order_id'], $payload['item_id'])
        );
    }

    /**
     * @param array $payload
     *
     * @return array
     * @throws PagarMeException
     */
    public function deleteAll(array $payload): array
    {
        return $this->client->request(
            self::DELETE,
            Routes::orderItems()->deleteAll($payload['order_id'])
        );
    }
}