<?php

namespace API\Lib;

use Serializable;
use Slim\Container as SlimContainer;
use Slim\Exception\ContainerException as SlimContainerException;

class Container extends SlimContainer implements Serializable
{
    private $registeredServices = [];

    private static $static;

    function __construct(array $values = array())
    {
        parent::__construct($values);
        self::$static = $this;
    }

    public function registerService(string $id, callable $callback, string $interface = null)
    {
        if($interface == null) {
            $interface = $id;
        }

        if(!isset($this->registeredServices[$id])) {
            $this->registeredServices[$id] = $interface;
        }

        $this->offsetSet($id, $callback);
    }

    public function get($id)
    {
        if(isset($this->registeredServices[$id])) {
            $result = parent::get($id);

            if(!$result instanceof $this->registeredServices[$id]) {
                throw new SlimContainerException(sprintf('Callback of "%s" did not return an object, that implements interface %s!', $id, $this->registeredServices[$id]));
            }

            return $result;
        }

        return parent::get($id);
    }

    public function serialize(): string
    {
        return "";
    }

    public function unserialize($serialized): void
    {
        $reflectionLibContainer = new \ReflectionClass(self::$static);
        $reflectionSlimContainer = $reflectionLibContainer->getParentClass();
        $reflectionPimpleContainer = $reflectionSlimContainer->getParentClass();

        $propertiesLibContainer = $reflectionLibContainer->getProperties();
        $propertiesPimpleContainer = $reflectionPimpleContainer->getProperties();

        $reflectionNewLib = new \ReflectionClass($this);
        $reflectionNewSlim = $reflectionNewLib->getParentClass();
        $reflectionNewPimple = $reflectionNewSlim->getParentClass();


        foreach ($propertiesLibContainer as $property) {
            if ($property->getName() == 'static') {
                continue;
            }

            $propertyLib = $reflectionLibContainer->getProperty($property->getName());
            $propertyLib->setAccessible(true);
            $value = $propertyLib->getValue(self::$static);
            $propertyLib->setAccessible(false);

            $propertyNew = $reflectionNewLib->getProperty($property->getName());
            $propertyNew->setAccessible(true);
            $propertyNew->setValue($this, $value);
            $propertyNew->setAccessible(false);
        }

        foreach ($propertiesPimpleContainer as $property) {
            $propertyPimple = $reflectionPimpleContainer->getProperty($property->getName());
            $propertyPimple->setAccessible(true);
            $value = $propertyPimple->getValue(self::$static);
            $propertyPimple->setAccessible(false);

            $propertyNew = $reflectionNewPimple->getProperty($property->getName());
            $propertyNew->setAccessible(true);
            $propertyNew->setValue($this, $value);
            $propertyNew->setAccessible(false);
        }
    }

}