<?php

declare(strict_types=1);

namespace Gadget\Util;

use Gadget\Io\JSON;

/**
 * @template TElement
 * @extends Collection<TElement>
 * @implements \IteratorAggregate<string,TElement>
 */
class Set extends Collection implements \IteratorAggregate
{
    /**
     * @param TElement[] $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();
        foreach ($elements as $element) {
            $this->set($element);
        }
    }


    /**
     * @param string|TElement $keyOrElement
     * @return string
     */
    public function hash(mixed $keyOrElement): string
    {
        return base64_encode(hash('SHA256', gettype($keyOrElement) . JSON::encode($keyOrElement), true));
    }


    /**
     * @param string|TElement $keyOrElement
     * @return bool
     */
    public function has(mixed $keyOrElement): bool
    {
        return (is_string($keyOrElement) && isset($this->elements[$keyOrElement]))
            || isset($this->elements[$this->hash($keyOrElement)]);
    }


    /**
     * @param string|TElement $keyOrElement
     * @return TElement|null
     */
    public function get(mixed $keyOrElement): mixed
    {
        if (is_string($keyOrElement) && isset($this->elements[$keyOrElement])) {
            return $this->elements[$keyOrElement] ?? null;
        }
        return $this->elements[$this->hash($keyOrElement)] ?? null;
    }


    /**
     * @param TElement $element
     * @return void
     */
    public function set(mixed $element): void
    {
        $this->elements[$this->hash($element)] = $element;
    }


    /**
     * @param string|TElement $keyOrElement
     * @return TElement|null
     */
    public function remove(mixed $keyOrElement): mixed
    {
        $element = $this->get($keyOrElement);
        if ($element !== null) {
            unset($this->elements[$this->hash($element)]);
        }
        return $element;
    }


    /**
     * @return \Traversable<string,TElement>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->elements;
    }
}
