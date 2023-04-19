<?php

namespace PagarMe\Test\Endpoints;

use PagarMe\Endpoints\Endpoint;
use PagarMe\Test\Endpoints\PagarMeTestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

final class OrdersTest extends PagarMeTestCase
{
    public function orderProvider(): array
    {
        return [
            [
                [
                    'order' => new MockHandler([
                        new Response(200, [], self::jsonMock('OrderMock'))
                    ]),
                    'list' => new MockHandler([
                        new Response(200, [], self::jsonMock('OrderListMock')),
                        new Response(200, [], '[]')
                    ])
                ]
            ]
        ];
    }
    
    /**
     * @dataProvider orderProvider
     */
    public function testOrderCreate($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['order']);
        
        $response = $client->orders()->create([
            'customer_id' => 1,
            'items' => [
                [
                    'amount' => 12000,
                    'description' => 'Bike OGGI Azul',
                    'quantity' => '3',
                    'code' => 'CODIGO_DO_ITEM_NO_SEU_SISTEMA'
                ]
            ],
            'shipping' => [
                'amount' => '1300',
                'description' => 'Descrição',
                'recipient_name' => 'João Gomes',
                'recipient_phone' => '557999235940',
                'address' => [
                    'country' => 'Brasil',
                    'state' => 'Bahia',
                    'city' => 'Barreiras',
                    'zip_code' => '00000000',
                    'line_1' => '120, Rua dos Anjos, São Gonçalo',
                    'line_2' => 'apartamento, 1º andar'
                ],
            ],
            'payments' => [
                [
                    'payment_method' => 'pix',
                    'Pix' => [
                        'expires_in' => 1000,
                        'expires_at' => '2022-10-10',
                        'additional_information' => [
                            'Name' => 'Teste',
                            'Value' => 'Este é um teste.'
                        ]
                    ],
                    'amount' => 10000
                ]
            ],
            'closed' => false,
            'device' => 'mobile',
            'ip' => '192.168.0.1',
            'session_id' => '0wesdf34-asdaas2-fds2423',
            'antifraud_enabled' => true
        ]);
        
        $this->assertEquals(
            '/core/v5/orders',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('OrderMock'), true),
            $response
        );
    }
    
    /**
     * @dataProvider orderProvider
     */
    public function testOrderAddCharge($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['order']);
        
        $response = $client->orders()->addCharge([
            'order_id' => 'ID_DO_PEDIDO',
            'amount' => 40000,
            'payment' => [
                'boleto' => [
                    'bank' => 237,
                    'instructions' => 'Instruções do boleto',
                    'due_at' => '2022-10-10',
                    'nosso_numero' => '242534544-P',
                    'type' => 'DM',
                    'document_number' => 'Identificador do boleto'
                ]
            ],
            'due_at' => '2022-10-10',
            'customer_id' => 'ID_DO_CLIENTE'
        ]);
        
        $this->assertEquals(
            '/core/v5/charges',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::POST,
            self::getRequestMethod($requestsContainer[0])
        );
    }
    
    /**
     * @dataProvider orderProvider
     */
    public function testOrderGetList($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['list']);
        
        $response = $client->orders()->getList();
        
        $this->assertEquals(
            '/core/v5/orders',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('OrderListMock'), true),
            $response
        );
        
        $response = $client->orders()->getList([
            'status' => 'paid',
            'page' => 2
        ]);
        
        $query = self::getQueryString($requestsContainer[1]);
        
        $this->assertStringContainsString('status=paid', $query);
        $this->assertStringContainsString('page=2', $query);
        $this->assertEquals(
            json_decode('[]', true),
            $response
        );
    }
    
    /**
     * @dataProvider orderProvider
     */
    public function testOrderGet($mock): void
    {
        $requestsContainer = [];
        $client = self::buildClient($requestsContainer, $mock['order']);
        
        $response = $client->orders()->get(['id' => 1]);
        
        $this->assertEquals(
            '/core/v5/orders/1',
            self::getRequestUri($requestsContainer[0])
        );
        $this->assertEquals(
            Endpoint::GET,
            self::getRequestMethod($requestsContainer[0])
        );
        $this->assertEquals(
            json_decode(self::jsonMock('OrderMock'), true),
            $response
        );
    }
}
