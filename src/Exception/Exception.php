<?php

declare(strict_types=1);

namespace Gadget\Exception;

class Exception extends \Exception
{
    /**
     * @param string|\Stringable|array{
     *   0: string|\Stringable,
     *   ...<int,string|\Stringable|int|float|bool|null>
     * } $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string|\Stringable|array $message = "",
        int $code = 0,
        \Throwable|null $previous = null
    ) {
        parent::__construct(
            $this->formatMessage(...(is_array($message) ? $message : [$message])),
            $code,
            $previous
        );
    }


    /**
     * @param string|\Stringable $message
     * @param string|\Stringable|int|float|bool|null ...$values
     * @return string
     */
    protected function formatMessage(
        string|\Stringable $message,
        string|\Stringable|int|float|bool|null ...$values
    ): string {
        return sprintf(
            $message instanceof \Stringable ? $message->__toString() : $message,
            ...array_map($this->formatMessageValue(...), $values)
        );
    }


    /**
     * @param string|\Stringable|int|float|bool|null $v
     * @return string
     */
    protected function formatMessageValue(string|\Stringable|int|float|bool|null $v): string|int|float
    {
        return match (true) {
            $v instanceof \Stringable => $v->__toString(),
            $v === null => 'null',
            is_bool($v) => $v ? 'true' : 'false',
            default => $v
        };
    }
}
