<?php

namespace Calchen\EasyOcr\Exception;

use Throwable;

class Exception extends \Exception
{
    /**
     * 大多数抛异常的地方会只传一个 code，根据 code 可以获取 message
     * 但同时兼容了通常的异常用法
     *
     * Exception constructor.
     *
     * @param                $code
     * @param int            $message
     * @param Throwable|null $previous
     */
    public function __construct($code, $message = 0, Throwable $previous = null)
    {
        $message = $message ?: ErrorCodes::MESSAGES[$code] ?? '';
        if (is_array($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
        parent::__construct($message, $code, $previous);
    }
}
