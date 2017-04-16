<?php

namespace API\Models;

use API\Lib\Container;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\CollectionIterator as CollectionIteratorORM;

/**
 * Iterator class for iterating over Collection data
 */
class CollectionIterator extends CollectionIteratorORM
{
    /**
     *
     * @var string
     */
    private $modelServiceName;

    /**
     *
     * @var Container
     */
    private $container;

    function __construct(Collection $collection, string $modelServiceName, Container $container)
    {
        $this->modelServiceName = $modelServiceName;
        $this->container = $container;

        parent::__construct($collection);
    }

    function current()
    {
        $value = parent::current();

        $model = $this->container->get($this->modelServiceName);
        $model->setModel($value);
        return $model;
    }
}
