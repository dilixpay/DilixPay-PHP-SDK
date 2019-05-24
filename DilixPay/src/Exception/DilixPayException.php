<?php
namespace DilixPayPHPSDK\DilixPay\Exception;

/**
 * DilixPay's generic exception.
 * You can catch this exception to catch every DilixPay related exception.
 */
abstract class DilixPayException extends \Exception
{
    /**
     * A normalized string representation of the exception.
     *
     * @return  string  A string representation of the exception, containing the exception class name, the error code \
     * and the message of the exception.
     */
    public function __toString()
    {
        return get_class($this) . ": [{$this->code}]: {$this->message}";
    }
}
