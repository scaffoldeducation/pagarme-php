<?php

namespace PagarMe\Test;

use PagarMe\ResponseHandler;
use PHPUnit\Framework\TestCase;

class ResponseHandlerTest extends TestCase
{
    public function testReturnTypeOnSuccess(): void
    {
        $handler = new ResponseHandler();

        $response = $handler->success('{"foo": "bar"}');

        $this->assertIsArray($response);
    }

    public function testReturnUsage(): void
    {
        $response = ResponseHandler::success('{"foo": "bar"}');

        $this->assertArrayHasKey('foo', $response);
        $this->assertEquals('bar', $response["foo"]);
    }

    public function testReturnListOfObjects(): void
    {
        $response = ResponseHandler::success('[{"foo": "bar"},{"bar": "baz"}]');
        
        $this->assertArrayHasKey('foo', $response[0], 'The first index must be an array');
        $this->assertEquals('bar', $response[0]["foo"]);
        $this->assertArrayHasKey('bar', $response[1], 'The second index must be an array');
        $this->assertEquals('baz', $response[1]["bar"]);
    }
    
    public function testUnparseablePayload(): void
    {
        $this->expectException(\PagarMe\Exceptions\InvalidJsonException::class);
        $response = ResponseHandler::success('{"foo": "bar"');
    }
}
