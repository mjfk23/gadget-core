<?php

declare(strict_types=1);

namespace Gadget\Io\Exception;

class CastException extends IOException
{
    /** @inheritdoc */
    protected function formatMessage(array &$message): string
    {
        if (count($message) === 1) {
            $message = [gettype($message[0] ?? null)];
            return "Invalid type: %s";
        }
        return parent::formatMessage($message);
    }
}
