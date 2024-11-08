<?php

declare(strict_types=1);

namespace Gadget\Io;

use Gadget\Io\Exception\FileException;

final class File
{
    /**
     * @param string|string[] $pattern
     * @param int $flags
     * @return string[]
     */
    public static function glob(
        string|array $pattern,
        int $flags = 0
    ): array {
        if (is_array($pattern)) {
            return array_unique(array_merge(...array_map(
                fn(string $p): array => self::glob($p, $flags),
                $pattern
            )));
        }

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


    /**
     * @param string $filename
     * @param string $mode
     * @param bool $use_include_path
     * @param resource|null $context
     * @return resource
     */
    public static function open(
        string $filename,
        string $mode,
        bool $use_include_path = false,
        mixed $context = null
    ): mixed {
        $file = fopen($filename, $mode, $use_include_path, $context);
        return is_resource($file)
            ? $file
            : throw new FileException(["Unable to open file: %s", $filename]);
    }


    /**
     * @template T
     * @param string $filename
     * @param string $mode
     * @param (callable(resource $file): T) $callback
     * @param bool $use_include_path
     * @param resource|null $context
     * @return T
     */
    public function use(
        string $filename,
        string $mode,
        callable $callback,
        bool $use_include_path = false,
        mixed $context = null
    ): mixed {
        $file = self::open($filename, $mode, $use_include_path, $context);
        try {
            return $callback($file);
        } finally {
            fclose($file);
        }
    }
}
