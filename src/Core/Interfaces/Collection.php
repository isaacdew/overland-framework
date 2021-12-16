<?php

namespace Overland\Core\Interfaces;

use ArrayAccess;
use Countable;
use Iterator;

class Collection implements Iterator, ArrayAccess, Countable
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function push($item) {
        $this->items[] = $item;
    }

    public function current(): mixed
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): mixed
    {
        return key($this->items);
    }

    public function valid():bool
    {
        return key($this->items) !== null;
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }
}
