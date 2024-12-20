<?php

declare(strict_types=1);

namespace Gadget\Exception;

use Gadget\Io\FormattedString;

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
            strval(is_array($message) ? new FormattedString(...$message) : $message),
            $code,
            $previous
        );
    }
}
