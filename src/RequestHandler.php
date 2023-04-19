<?php

namespace PagarMe;

class RequestHandler
{
    /**
     * @param array $options
     * @param string $apiKey
     *
     * @return array
     */
    public static function bindApiKeyToHeader(array $options, string $apiKey): array
    {
        $options['auth'][] = $apiKey;
        $options['auth'][] = "";

        return $options;
    }
}
