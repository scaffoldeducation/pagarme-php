<?php

namespace PagarMe;

use PagarMe\Endpoints\Addresses;
use PagarMe\Endpoints\Cards;
use PagarMe\Endpoints\Charges;
use PagarMe\Endpoints\Customers;
use PagarMe\Endpoints\OrderItems;
use PagarMe\Endpoints\Orders;
use PagarMe\Exceptions\PagarMeException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException as ClientException;
use PagarMe\Endpoints\Cycles;
use PagarMe\Endpoints\Invoices;
use PagarMe\Endpoints\Plans;
use PagarMe\Endpoints\Subscriptions;
use PagarMe\Exceptions\InvalidJsonException;

class Client
{
    /**
     * @var string
     */
    const BASE_URI = 'https://api.pagar.me/core/v5/';

    /**
     * @var string header used to identify application's requests
     */
    const PAGARME_USER_AGENT_HEADER = 'X-PagarMe-User-Agent';

    /**
     * @var HttpClient
     */
    private HttpClient $http;

    /**
     * @var string
     */
    private string $apiKey;

    /**
     * @var Orders
     */
    private Orders $orders;

    /**
     * @var OrderItems
     */
    private OrderItems $orderItems;

    /**
     * @var Charges
     */
    private Charges $charges;

    /**
     * @var Customers
     */
    private Customers $customers;

    /**
     * @var Addresses
     */
    private Addresses $addresses;

    /**
     * @var Cards
     */
    private Cards $cards;

    /**
     * @var Plans
     */
    private Plans $plans;

    /**
     * @var Subscriptions
     */
    private Subscriptions $subscriptions;

    /**
     * @var Cycles
     */
    private Cycles $cycles;

    /**
     * @var Invoices
     */
    private Invoices $invoices;

    /**
     * @param string $apiKey
     * @param array|null $extras
     */
    public function __construct(string $apiKey, array $extras = null)
    {
        $this->apiKey = $apiKey;

        $options = ['base_uri' => self::BASE_URI];

        if (!is_null($extras)) {
            $options = array_merge($options, $extras);
        }

        $userAgent = $options['headers']['User-Agent'] ?? '';

        $options['headers']['User-Agent'] = $this->addUserAgentHeaders($userAgent);
        $options['headers']['X-PagarMe-User-Agent'] = $this->addUserAgentHeaders($userAgent);

        $this->http = new HttpClient($options);

        $this->orders = new Orders($this);
        $this->orderItems = new OrderItems($this);
        $this->charges = new Charges($this);
        $this->customers = new Customers($this);
        $this->addresses = new Addresses($this);
        $this->cards = new Cards($this);
        $this->plans = new Plans($this);
        $this->subscriptions = new Subscriptions($this);
        $this->cycles = new Cycles($this);
        $this->invoices = new Invoices($this);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return array
     *
     * @throws PagarMeException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->http->request(
                $method,
                $uri,
                RequestHandler::bindApiKeyToHeader(
                    $options,
                    $this->apiKey
                )
            );

            return ResponseHandler::success((string)$response->getBody());
        } catch (InvalidJsonException $exception) {
            throw $exception;
        } catch (ClientException $exception) {
            ResponseHandler::failure($exception);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * Build a user-agent string to be informed on requests
     *
     * @param string $customUserAgent
     *
     * @return string
     */
    private function buildUserAgent(string $customUserAgent = ''): string
    {
        return trim(sprintf(
            '%s pagarme-php/%s php/%s',
            $customUserAgent,
            PagarMe::VERSION,
            phpversion()
        ));
    }

    /**
     * Append new keys (the default and pagarme) related to user-agent
     *
     * @param string $customUserAgent
     * @return string
     */
    private function addUserAgentHeaders(string $customUserAgent = ''): string
    {
        return $this->buildUserAgent($customUserAgent);
    }

    /**
     * @return Orders
     */
    public function orders(): Orders
    {
        return $this->orders;
    }

    /**
     * @return OrderItems
     */
    public function orderItems(): OrderItems
    {
        return $this->orderItems;
    }

    /**
     * @return Charges
     */
    public function charges(): Charges
    {
        return $this->charges;
    }

    /**
     * @return Customers
     */
    public function customers(): Customers
    {
        return $this->customers;
    }

    /**
     * @return Addresses
     */
    public function addresses(): Addresses
    {
        return $this->addresses;
    }

    /**
     * @return Cards
     */
    public function cards(): Cards
    {
        return $this->cards;
    }

    /**
     * @return Plans
     */
    public function plans(): Plans
    {
        return $this->plans;
    }

    /**
     * @return Subscriptions
     */
    public function subscriptions(): Subscriptions
    {
        return $this->subscriptions;
    }

    /**
     * @return Cycles
     */
    public function cycles(): Cycles
    {
        return $this->cycles;
    }

    /**
     * @return Invoices
     */
    public function invoices(): Invoices
    {
        return $this->invoices;
    }
}
