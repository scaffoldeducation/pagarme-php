<?php

namespace PagarMe\Test;

use GuzzleHttp\Exception\ClientException;
use PagarMe\PagarMe;
use PagarMe\Client;
use PagarMe\Exceptions\PagarMeException;
use PagarMe\Endpoints\Endpoint;
use PagarMe\Endpoints\Customers;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

final class ClientTest extends TestCase
{
    public function testSuccessfulResponse(): void
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], '{"status":"Ok!"}'),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Client('apiKey', ['handler' => $handler]);

        $response = $client->request(Endpoint::POST, 'orders');

        $this->assertEquals($response["status"], "Ok!");
    }
    
    public function testPagarMeFailedResponse(): void
    {
        $this->expectException(PagarMeException::class);
        $mock = new MockHandler([
            new Response(401, [], '{   
                "message": "api_key está faltando"  
            }')
        ]);

        $handler = HandlerStack::create($mock);

        $client = new Client('apiKey', ['handler' => $handler]);

        $message = 'api_key está faltando';
        $expectedExceptionMessage = sprintf(
            '%s',
            $message
        );

        try {
            $response = $client->request(Endpoint::POST, 'orders');
        } catch (\PagarMe\Exceptions\PagarMeException $exception) {
            $this->assertEquals($expectedExceptionMessage, $exception->getMessage());

            throw $exception;
        }
    }
    
    public function testRequestFailedResponse(): void
    {
        $this->expectException(\GuzzleHttp\Exception\ServerException::class);
        $mock = new MockHandler([
            new Response(502, [], '<div>Bad Gateway</div>')
        ]);

        $handler = HandlerStack::create($mock);

        $client = new Client('apiKey', ['handler' => $handler]);

        $response = $client->request(Endpoint::POST, 'transactions');
    }

    public function testSuccessfulResponseWithCustomUserAgentHeader()
    {
        $container = [];
        $history = Middleware::history($container);
        $mock = new MockHandler([
            new Response(200, [], '{"status":"Ok!"}'),
        ]);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Client(
            'apiKey',
            [
                'handler' => $handler,
                'headers' => [
                  'User-Agent' => 'MyCustomApplication/10.2.2',
                  'X-PagarMe-Version' => '2017-07-17',
                  'Custom-Header' => 'header',
                ]
            ]
        );

        $response = $client->request(Endpoint::POST, 'orders');

        $this->assertEquals("Ok!", $response["status"]);

        $expectedUserAgent = sprintf(
            'MyCustomApplication/10.2.2 pagarme-php/%s php/%s',
            PagarMe::VERSION,
            phpversion()
        );

        $this->assertEquals(
            '2017-07-17',
            $container[0]['request']->getHeaderLine('X-PagarMe-Version')
        );

        $this->assertEquals(
            'header',
            $container[0]['request']->getHeaderLine('Custom-Header')
        );

        $this->assertEquals(
            $expectedUserAgent,
            $container[0]['request']->getHeaderLine('User-Agent')
        );
        $this->assertEquals(
            $expectedUserAgent,
            $container[0]['request']->getHeaderLine(
                Client::PAGARME_USER_AGENT_HEADER
            )
        );
    }

    public function testCustomers(): void
    {
        $client = new Client('apiKey');

        $customers = $client->customers();

        $this->assertInstanceOf(Customers::class, $customers);
    }
}
