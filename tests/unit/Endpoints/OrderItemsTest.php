<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class OrderItemsTest extends PagarMeTestCase
{
    public function orderItemMockProvider(): array
    {
        return [[[
            'orderItem' => new MockHandler([
                new Response(200, [], self::jsonMock('OrderItemMock'))
            ])
        ]]];
    }

    /**
     * @dataProvider orderItemMockProvider
     */
    public function testOrderItemCreate($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['orderItem']);

        $response = $client->orderItems()->create([
            'order_id' => '1',
            'amount' => 12234,
            'description' => 'Descrição do item',
            'quantity' => '4',
            'category' => 'Bikes'
        ]);

        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/orders/1/items',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('OrderItemMock'), true),
            $response
        );
    }

    /**
     * @dataProvider orderItemMockProvider
     */
    public function testOrderItemUpdate($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['orderItem']);

        $response = $client->orderItems()->update([
            'order_id' => '1',
            'item_id' => '1',
            'amount' => 12234,
            'description' => 'Bicicleta OGGI V2',
            'quantity' => '4',
            'category' => 'Bikes'
        ]);

        $requestBody = self::getBody($container[0]);

        $this->assertEquals(
            Endpoint::PUT,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/orders/1/items/1',
            self::getRequestUri($container[0])
        );
        $this->assertStringContainsString(
            '"description":"Bicicleta OGGI V2"',
            $requestBody
        );
    }

    /**
     * @dataProvider orderItemMockProvider
     */
    public function testOrderItemGet($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['orderItem']);

        $response = $client->orderItems()->get([
            'order_id' => '1',
            'item_id' => '1',
        ]);

        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/orders/1/items/1',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('OrderItemMock'), true),
            $response
        );
    }

    /**
     * @dataProvider orderItemMockProvider
     */
    public function testOrderItemDelete($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['orderItem']);

        $response = $client->orderItems()->delete([
            'order_id' => '1',
            'item_id' => '1'
        ]);

        $this->assertEquals(
            Endpoint::DELETE,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/orders/1/items/1',
            self::getRequestUri($container[0])
        );
    }

    /**
     * @dataProvider orderItemMockProvider
     */
    public function testOrderItemDeleteAll($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['orderItem']);

        $response = $client->orderItems()->deleteAll([
            'order_id' => '1'
        ]);

        $this->assertEquals(
            Endpoint::DELETE,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/orders/1/items',
            self::getRequestUri($container[0])
        );
    }
}
