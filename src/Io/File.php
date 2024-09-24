<?php

declare(strict_types=1);

namespace Gadget\Io;

use Gadget\Io\Exception\FileException;

final class File
{
    /**
     * @param string $pattern
     * @param int $flags
     * @return string[]
     */
    public static function glob(
        string $pattern,
        int $flags = 0
    ): array {
        $glob = glob($pattern, $flags);
        return is_array($glob) ? $glob : [];
    }


    /**
     * @param string $path
     * @return string
     */
    public static function getPath(string $path): string
    {
        $_path = realpath($path);
        return is_string($_path)
            ? $_path
            : throw new FileException(["File not found: %s", $path]);
    }


    /**
     * @param string $path
     * @return string
     */
    public static function getContents(string $path): string
    {
        $contents = file_get_contents(self::getPath($path));
        return is_string($contents)
            ? $contents
            : throw new FileException(["Unable to read file: %s", $path]);
    }


    /**
     * @param string $path
     * @param string $contents
     * @return int
     */
    public static function putContents(
        string $path,
        string $contents
    ): int {
        $bytes = file_put_contents($path, $contents);
        return is_int($bytes)
            ? $bytes
            : throw new FileException(["Unable to read file: %s", $path]);
    }
}
