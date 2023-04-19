<?php

namespace PagarMe\Test;

use PagarMe\RequestHandler;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    public function testBindApiKey(): void
    {
        $this->assertEquals(
            ['auth' => ['katiau', '']],
            RequestHandler::bindApiKeyToHeader([], 'katiau')
        );
    }
}
