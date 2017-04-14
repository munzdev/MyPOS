<?php

namespace API\Models;

use API\Lib\Interfaces\Models\ICollection;
use API\Lib\Interfaces\Models\IModel;
use Propel\Runtime\Collection\Collection as PropelCollection;
use Propel\Runtime\Collection\ObjectCollection;
use Traversable;

class Collection implements ICollection {

    /**
     *
     * @var PropelCollection
     */
    private $collection;
    
    /**
     * 
     */
    private $model;

    function __construct() {
        $this->setCollection(new ObjectCollection());
    }

    public function setCollection(PropelCollection $collection) {
        $this->collection = $collection;
    }
    
    public function setModel(IModel $model) {
        $this->model = $model;
    }

    public function toArray() {
        return $this->collection->toArray();
    }

    public function count(): int {
        return $this->collection->count();
    }

    public function getFirst() {
        $model = clone $this->model;
        $model->setModel($this->collection->getFirst());
        return $model;
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
        $model = clone $this->model;
        $model->setModel($this->collection->offsetGet($offset));
        return $model;
    }

    public function offsetSet($offset, $value): void {
        $this->collection->offsetSet($offset, $value);
    }

    public function offsetUnset($offset): void {
        $this->collection->offsetUnset($offset);
    }

    public function serialize(): string {
        return $this->collection->serialize();
    }

    public function unserialize($serialized): void {
        $this->collection->unserialize($serialized);
    }

    public function append($value)
    {
        return $this->collection->append($value);
    }

    public function clear()
    {
        return $this->collection->clear();
    }

    public function contains($element)
    {
        return $this->collection->contains($element);
    }

    public function exchangeArray($input)
    {
        return $this->collection->exchangeArray($input);
    }

    public function get($key)
    {
        $model = clone $this->model;
        $model->setModel($this->collection->get($key));
        return $model;
    }

    public function getArrayCopy()
    {
        return $this->collection->getArrayCopy();
    }

    public function getData()
    {
        return $this->collection->getData();
    }

    public function getLast()
    {
        $model = clone $this->model;
        $model->setModel($this->collection->getLast());
        return $model;
    }

    public function pop()
    {
        $model = clone $this->model;
        $model->setModel($this->collection->pop());
        return $model;
    }

    public function prepend($value)
    {
        return $this->collection->prepend($value);
    }

    public function push($value)
    {
        return $this->collection->push($value);
    }

    public function remove($key)
    {
        return $this->collection->remove($key);
    }

    public function search($element)
    {
        return $this->collection->search($element);
    }

    public function set($key, $value)
    {
        return $this->collection->set($key, $value);
    }

    public function setData($data)
    {
        return $this->collection->setData($data);
    }

    public function shift()
    {
        return $this->collection->shift();
    }

}