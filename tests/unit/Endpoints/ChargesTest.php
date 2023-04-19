<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class ChargesTest extends PagarMeTestCase
{
    public function chargeProvider(): array
    {
        return [[[
            'charge' => new MockHandler([
                new Response(200, [], self::jsonMock('ChargeMock'))
            ]),
            'list' => new MockHandler([
                new Response(200, [], self::jsonMock('ChargeListMock')),
                new Response(200, [], '[]')
            ])
        ]]];
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeCapture($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->capture([
            'charge_id' => '1',
            'amount' => '10000',
            'code' => '123'
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/capture',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('ChargeMock'), true),
            $response
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeUpdateBillingDue($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->updateBillingDue([
            'charge_id' => '1',
            'due_at' => '2022-10-10'
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/due-date',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::PATCH,
            self::getRequestMethod($requestsContainer[0])
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeUpdateCard($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->updateCard([
            'charge_id' => '1',
            'update_subscription' => false,
            'card_id' => '123',
            'card_token' => '12345'
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/card',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::PATCH,
            self::getRequestMethod($requestsContainer[0])
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeUpdatePaymentMethod($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->updatePaymentMethod([
            'charge_id' => '1',
            'update_subscription' => false,
            'payment_method' => 'Pix',
            'Pix' => [
                'expires_in' => 1000,
                'expires_at' => '2022-10-10',
                'additional_information' => [
                    'Name' => 'Teste',
                    'Value' => 'Este é um teste.'
                ]
            ]
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/payment-method',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::PATCH,
            self::getRequestMethod($requestsContainer[0])
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeGetList($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['list']);

        $response = $client->charges()->getList();

        $this->assertEquals(
            '/core/v5/charges',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('ChargeListMock'), true),
            $response
        );

        $response = $client->charges()->getList([
            'status' => 'paid',
            'customer_id' => '1'
        ]);

        $query = self::getQueryString($requestsContainer[1]);

        $this->assertStringContainsString('status=paid', $query);
        $this->assertStringContainsString('customer_id=1', $query);
        $this->assertEquals(
            json_decode('[]', true),
            $response
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeGet($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->get(['id' => 1]);

        $this->assertEquals(
            '/core/v5/charges/1',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('ChargeMock'), true),
            $response
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeConfirmCash($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->confirmCash([
            'charge_id' => '1',
            'amount' => 12322,
            'code' => '123',
            'description' => 'Descrição'
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/confirm-payment',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeHoldManually($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->holdCharge([
            'id' => '1'
        ]);

        $this->assertEquals(
            '/core/v5/charges/1/retry',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
    }

    /**
     * @dataProvider chargeProvider
     */
    public function testChargeCancel($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['charge']);

        $response = $client->charges()->cancel(['charge_id' => 1]);

        $this->assertEquals(
            '/core/v5/charges/1',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::DELETE,
            self::getRequestMethod($requestsContainer[0])
        );
    }
}
