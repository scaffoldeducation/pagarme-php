<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class CardsTest extends PagarMeTestCase
{
    public function cardMockProvider(): array
    {
        return [[[
            'card' => new MockHandler([
                new Response(200, [], self::jsonMock('CardMock'))
            ]),
            'list' => new MockHandler([
                new Response(200, [], self::jsonMock('CardListMock')),
                new Response(200, [], '[]')
            ]),
        ]]];
    }

    /**
     * @dataProvider cardMockProvider
     */
    public function testCardCreate($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['card']);

        $response = $client->cards()->create([
            'card_number' => '4111111111111111',
            'card_expiration_date' => '0722',
            'card_cvv' => '123',
            'card_holder_name' => 'Davy Jones',
            'customer_id' => 1,
            'card_hash' => null,
        ]);

        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/cards',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CardMock'), true),
            $response
        );
    }

    /**
     * @dataProvider cardMockProvider
     */
    public function testCardList($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['list']);

        $response = $client->cards()->getList(["customer_id" => 1]);
        
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/cards',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CardListMock'), true),
            $response
        );
    }

    /**
     * @dataProvider cardMockProvider
     */
    public function testCardGet($mock): void
    {
        $container = [];
        $client = self::buildClient($container, $mock['card']);

        $response = $client->cards()->get([
            'card_id' => 'card_abc1234abc1234abc1234abc1',
            'customer_id' => 1
        ]);

        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($container[0])
        );
        $this->assertEquals(
            '/core/v5/customers/1/cards/card_abc1234abc1234abc1234abc1',
            self::getRequestUri($container[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('CardMock'), true),
            $response
        );
    }
}
