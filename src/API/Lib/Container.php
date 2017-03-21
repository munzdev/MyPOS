<?php

namespace API\Lib;

use Slim\Container as SlimContainer;
use Slim\Exception\ContainerException as SlimContainerException;

class Container extends SlimContainer
{
    private $registeredServices = [];

    public function registerService(string $id, callable $callback, string $interface = null)
    {
        if($interface == null) {
            $interface = $id;
        }
        #

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

}