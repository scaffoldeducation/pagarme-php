<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class CustomersTest extends PagarMeTestCase
{
    public function customerProvider(): array
    {
        return [[[
            'customer' => new MockHandler([
                new Response(200, [], self::jsonMock('CustomerMock'))
            ]),
            'list' => new MockHandler([
                new Response(200, [], self::jsonMock('CustomerListMock')),
                new Response(200, [], '[]')
            ])
        ]]];
    }

    /**
     * @dataProvider customerProvider
     */
    public function testCustomerCreate($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['customer']);

        $response = $client->customers()->create([
            'external_id' => '#123456789',
            'name' => 'João das Neves',
            'type' => 'individual',
            'country' => 'br',
            'email' => 'joaoneves@norte.com',
            'documents' => [
                [
                    'type' => 'cpf',
                    'number' => '11111111111'
                ]
            ],
            'phone_numbers' => [
                '+5511999999999',
                '+5511888888888'
            ],
            'birthday' => '1985-01-01'
        ]);

        $this->assertEquals(
            '/core/v5/customers',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CustomerMock'), true),
            $response
        );
    }

    /**
     * @dataProvider customerProvider
     */
    public function testCustomerGetList($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['list']);

        $response = $client->customers()->getList();
        
        $this->assertEquals(
            '/core/v5/customers',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CustomerListMock'), true),
            $response
        );

        $response = $client->customers()->getList([
            'name' => 'Fulano da Silva',
            'email' => 'fulano@silva.com',
            'id' => '123456'
        ]);

        $query = self::getQueryString($requestsContainer[1]);

        $this->assertStringContainsString('name=Fulano%20da%20Silva', $query);
        $this->assertStringContainsString('email=fulano%40silva.com', $query);
        $this->assertStringContainsString('id=123456', $query);
        $this->assertEquals(
            json_decode('[]', true),
            $response
        );
    }

    /**
     * @dataProvider customerProvider
     */
    public function testCustomerGet($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['customer']);

        $response = $client->customers()->get(['id' => 1]);

        $this->assertEquals(
            '/core/v5/customers/1',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CustomerMock'), true),
            $response
        );
    }
}
