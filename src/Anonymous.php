<?php

namespace PagarMe;

use Exception;

class Anonymous extends \stdClass
{
    /**
     * @param string $methodName
     * @param array $params
     * @throws Exception
     */
    public function __call($methodName, $params)
    {
        if (!isset($this->{$methodName})) {
            throw new Exception('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()');
        }

        return $this->{$methodName}->__invoke(... $params);
    }
}
