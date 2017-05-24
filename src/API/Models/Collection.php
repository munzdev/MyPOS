<?php

namespace API\Models;

use API\Lib\Container;
use API\Lib\Interfaces\Models\ICollection;
use Propel\Runtime\Collection\Collection as PropelCollection;
use Propel\Runtime\Collection\ObjectCollection;
use Traversable;

class Collection implements ICollection {

    /**
     *
     * @var Container
     */
    protected $container;

    /**
     *
     * @var PropelCollection
     */
    private $collection;

    /**
     * @var string
     */
    private $modelServiceName;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setCollection(new ObjectCollection());
    }

    public function setCollection(PropelCollection $collection) {
        $this->collection = $collection;
    }

    public function setModelServiceName(string $model) {
        $this->modelServiceName = $model;
    }

    public function toArray() {
        $array =  $this->collection->toArray();
        return $this->cleanupRecursionStringFromToArray($array);
    }

    private function cleanupRecursionStringFromToArray(array $array)
    {
        $relationsFound = [];
        foreach ($array as $key => $item) {
            if ($item === ["*RECURSION*"] || $item === "*RECURSION*") {
                unset($array[$key]);
            } elseif (is_array($item)) {
                $array[$key] = $this->cleanupRecursionStringFromToArray($item);
                $relationsFound[] = $key;
            }
        }

        return $array;
    }

    public function count(): int {
        return $this->collection->count();
    }

    public function getFirst() {
        $model = $this->container->get($this->modelServiceName);
        $model->setModel($this->collection->getFirst());
        return $model;
    }

    public function getIterator(): Traversable {
        return new CollectionIterator($this->collection, $this->modelServiceName, $this->container);
    }

    public function isEmpty() {
        return $this->collection->isEmpty();
    }

    public function offsetExists($offset): bool {
        return $this->collection->offsetExists($offset);
    }

    public function offsetGet($offset) {
        $model = $this->container->get($this->modelServiceName);
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
        return $this->collection->append($value->getModel());
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
        $model = $this->container->get($this->modelServiceName);
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
        $model = $this->container->get($this->modelServiceName);
        $model->setModel($this->collection->getLast());
        return $model;
    }

    public function pop()
    {
        $model = $this->container->get($this->modelServiceName);
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