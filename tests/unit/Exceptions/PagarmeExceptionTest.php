<?php

namespace PagarMe\Test;

use PagarMe\Exceptions\PagarMeException;
use PHPUnit\Framework\TestCase;

final class PagarMeExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function buildExceptionMessage(): void
    {
        $errorCode = 400;
        $message = 'value must be array';

        $exception = new PagarMeException(
            $message,
            $errorCode
        );

        $expectedMessage = sprintf(
            '%s',
            $message
        );
        $this->assertEquals($expectedMessage, $exception->getMessage());
        $this->assertEquals($errorCode, $exception->getCode());
    }
}
