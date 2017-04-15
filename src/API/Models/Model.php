<?php

namespace API\Models;

use API\Lib\Container;
use API\Lib\Interfaces\Models\IModel;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

abstract class Model implements IModel {

    /**
     *
     * @var ActiveRecordInterface
     */
    protected $model;

    /**
     *
     * @var Container
     */
    protected $container;

    function __construct(Container $container) {
        $this->container = $container;
    }

    public function setModel(ActiveRecordInterface $model)
    {
        $this->model = $model;
    }

    public function getModel() : ActiveRecordInterface {
        return $this->model;
    }

    public function clear() {
        return $this->model->clear();
    }

    public function delete() {
        return $this->model->delete();
    }

    public function isNew() {
        return $this->model->isNew();
    }

    public function save() {
        return $this->model->save();
    }

    public function toArray() {
        return $this->model->toArray();
    }
}