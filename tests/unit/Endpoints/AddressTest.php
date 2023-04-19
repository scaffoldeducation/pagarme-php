<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class AddressTest extends PagarMeTestCase
{
    public function addressMockProvider(): array
    {
        return [[[
            'address' => new MockHandler([
                new Response(200, [], self::jsonMock('AddressMock'))
            ]),
            'list' => new MockHandler([
                new Response(200, [], self::jsonMock('AddressListMock')),
                new Response(200, [], '[]')
            ]),
        ]]];
    }

    /**
     * @dataProvider addressMockProvider
     */
    public function testAddressCreate($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['address']);

        $response = $client->addresses()->create([
            'customer_id' => '1',
            'country' => 'Brasil',
            'state' => 'Bahia',
            'city' => 'Barreiras',
            'zip_code' => '00000000',
            'line_1' => '120, Rua dos Anjos, São Gonçalo',
            'line_2' => 'apartamento, 1º andar'
        ]);

        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/addresses',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('AddressMock'), true),
            $response
        );
    }

    /**
     * @dataProvider addressMockProvider
     */
    public function testAddressUpdate($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['address']);

        $response = $client->addresses()->update([
            'address_id' => '1',
            'customer_id' => '1',
            'line_2' => 'apartamento, 1 andar'
        ]);

        $requestBody = self::getBody($container[0]);

        $this->assertEquals(
            Endpoint::PUT,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/addresses/1',
            self::getRequestUri($container[0])
        );
        $this->assertStringContainsString(
            '"line_2":"apartamento, 1 andar"',
            $requestBody
        );
    }

    /**
     * @dataProvider addressMockProvider
     */
    public function testAddressList($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['list']);

        $response = $client->addresses()->getList(["customer_id" => 1]);
        
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/addresses',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('AddressListMock'), true),
            $response
        );
    }

    /**
     * @dataProvider addressMockProvider
     */
    public function testAddressGet($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['address']);

        $response = $client->addresses()->get([
            'address_id' => '1',
            'customer_id' => '1'
        ]);

        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/addresses/1',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('AddressMock'), true),
            $response
        );
    }

    /**
     * @dataProvider addressMockProvider
     */
    public function testAddressDelete($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['address']);

        $response = $client->addresses()->delete([
            'address_id' => '1',
            'customer_id' => '1'
        ]);

        $this->assertEquals(
            Endpoint::DELETE,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/addresses/1',
            self::getRequestUri($container[0])
        );
    }
}
