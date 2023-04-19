<?php

namespace PagarMe\Exceptions;

final class PagarMeException extends \Exception
{
    /**
     * @var array
     */
    private array $errors;

    /**
     * @var string
     */
    private string $response;

    /**
     * @param string $message
     * @param int $code
     * @param array $errors
     * @param string $response
     */
    public function __construct(
        string $message,
        int $code = 0,
        array $errors = [],
        string $response = "",
    )
    {
        $this->errors = $errors;
        $this->response = $response;

        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }
}
