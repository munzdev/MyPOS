<?php

namespace API\Models;

use API\Lib\Container;
use API\Lib\Interfaces\Models\IModel;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\TableMap;

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

    public function getModel() : ActiveRecordInterface {
        return $this->model;
    }

    public function setModel(ActiveRecordInterface $model)
    {
        $this->model = $model;
    }

    public function clear() {
        return $this->model->clear();
    }

    public function delete() {
        return $this->model->delete();
    }

    public function isNew() : bool {
        return $this->model->isNew();
    }

    public function save() {
        return $this->model->save();
    }

    public function toArray(bool $recursive = false) {
        $array = $this->model->toArray(TableMap::TYPE_PHPNAME, true, array(), $recursive);
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
}