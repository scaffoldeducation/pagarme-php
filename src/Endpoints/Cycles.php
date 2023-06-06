<?php

namespace PagarMe\Endpoints;

use PagarMe\Routes;
use PagarMe\Endpoints\Endpoint;

class Cycles extends Endpoint
{
    /**
     * @param array $payload
     *
     * @return \ArrayObject
     */
    public function renew(array $payload)
    {
        return $this->client->request(
            self::POST,
            Routes::cycles()->base($payload['subscriptionId'])
        );
    }

    /**
     * @param array $payload
     *
     * @return \ArrayObject
     */
    public function get(array $payload)
    {
        return $this->client->request(
            self::GET,
            Routes::cycles()->details($payload['id'])
        );
    }

    /**
     * @param array|null $payload
     *
     * @return \ArrayObject
     */
    public function getList(array $payload = null)
    {
        return $this->client->request(
            self::GET,
            Routes::cycles()->base(),
            ['query' => $payload]
        );
    }
}
