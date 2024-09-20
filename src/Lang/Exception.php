<?php

declare(strict_types=1);

namespace Gadget\Lang;

class Exception extends \Exception
{
    /**
     * @param string|mixed[] $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string|array $message = "",
        int $code = 0,
        \Throwable|null $previous = null
    ) {
        parent::__construct(
            is_array($message)
                ? $this->createMessage($message)
                : $message,
            $code,
            $previous
        );
    }


    /**
     * @param mixed[] &$message
     * @return string
     */
    protected function createMessage(array &$message): string
    {
        return sprintf(
            $this->formatMessage($message),
            ...array_filter(
                $message,
                fn(mixed $v) => is_float($v) || is_int($v) || is_string($v) || $v instanceof \Stringable
            )
        );
    }


    /**
     * @param mixed[] &$message
     * @return string
     */
    protected function formatMessage(array &$message): string
    {
        /** @var mixed $format */
        $format = count($message) > 1 ? array_shift($message) : null;

        $format = match (true) {
            is_string($format) => $format,
            is_scalar($format) || $format instanceof \Stringable => strval($format),
            default => null
        };

        if ($format === null) {
            $format = "%s";
            $message = [""];
        }

        return $format;
    }
}
