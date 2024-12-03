<?php

declare(strict_types=1);

namespace Gadget\Exception;

/** @implements \IteratorAggregate<string> */
class StackTraceIterator implements \IteratorAggregate
{
    /** @var string[]|null $seen */
    private array|null $seen = null;


    /**
     * @param \Throwable $root
     */
    public function __construct(private \Throwable $root)
    {
    }


    /** @inheritdoc */
    public function getIterator(): \Traversable
    {
        $this->seen = null;
        yield from $this->getStackTraceDetail($this->root);
    }


    /**
     * @param \Throwable $t
     * @return iterable<string>
     */
    private function getStackTraceDetail(\Throwable $t): iterable
    {
        yield sprintf(
            '%s%s: %s',
            is_array($this->seen) ? 'Caused by: ' :  '',
            get_class($t),
            $t->getMessage()
        );

        if ($this->seen === null) {
            $this->seen = [];
        }

        list($file, $line, $trace, $prev) = $this->getThrowableParts($t);

        $last = false;
        do {
            $current = "{$file}:{$line}";
            if (in_array($current, $this->seen, true)) {
                yield sprintf(' ... %d more', count($trace) + 1);
                break;
            }

            $this->seen[] = $current;
            list(
                $traceFile,
                $traceLine,
                $traceClass,
                $traceFunction
            ) = $this->getTraceParts(array_shift($trace) ?? []);

            yield sprintf(
                ' at %s%s%s(%s)',
                str_replace('\\', '.', ($traceClass ?? '')),
                is_string($traceClass) && is_string($traceFunction) ? '.' : '',
                str_replace('\\', '.', ($traceFunction ?? '(main)')),
                $line === null ? $file : basename($file) . ':' . $line
            );

            if ($last) {
                break;
            }
            $file = $traceFile;
            $line = $traceLine;
            $last = count($trace) === 0;
        } while (true);

        if ($prev !== null) {
            yield from self::getStackTraceDetail($prev);
        }
    }


    /**
     * @param \Throwable $t
     * @return array{string,int,array<int,array<string,string|null>>,\Throwable|null}
     */
    private function getThrowableParts(\Throwable $t): array
    {
        $file = $t->getFile();
        $line = $t->getLine();
        /** @var array<int,array<string,string|null>> $trace */
        $trace = $t->getTrace();
        $prev = $t->getPrevious();

        return [$file, $line, $trace, $prev];
    }


    /**
     * @param array<string,string|null> $trace
     * @return array{string,int|null,string|null,string|null}
     */
    private function getTraceParts(array $trace): array
    {
        $traceFile =  $trace['file'] ?? 'Unknown Source';
        $traceLine = intval(isset($trace['file']) ? ($trace['line'] ?? 0) : 0);
        if ($traceLine < 1) {
            $traceLine = null;
        }
        $traceClass = $trace['class'] ?? null;
        $traceFunction = $trace['function'] ?? null;
        return [$traceFile, $traceLine, $traceClass, $traceFunction];
    }
}
