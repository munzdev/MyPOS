<?php

namespace API\Models;

use API\Lib\Interfaces\Models\ICollection;
use Propel\Runtime\Collection\Collection as PropelCollection;
use Propel\Runtime\Collection\ObjectCollection;
use Traversable;

class Collection implements ICollection {    

    /**
     *
     * @var PropelCollection
     */
    private $collection;
    
    function __construct() {
        $this->setCollection(new ObjectCollection());
    }
    
    public function setCollection(PropelCollection $collection) {
        $this->collection = $collection;
    }
    
    public function toArray() {
        return $this->collection->toArray();
    }

    public function count(): int {
        return $this->collection->count();
    }

    public function getFirst() {
        return $this->collection->getFirst();
    }

    public function getIterator(): Traversable {
        return $this->collection->getIterator();
    }

    public function isEmpty() {
        return $this->collection->isEmpty();
    }

    public function offsetExists($offset): bool {
        return $this->collection->offsetExists($offset);
    }

    public function offsetGet($offset) {
        return $this->collection->offsetGet();
    }

    public function offsetSet($offset, $value): void {
        return $this->collection->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void {
        return $this->collection->offsetUnset($offset);
    }

    public function serialize(): string {
        return $this->collection->serialize();
    }

    public function unserialize(string $serialized): void {
        return $this->collection->unserialize($serialized);
    }

}