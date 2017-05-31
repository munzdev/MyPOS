<?php
namespace API\Lib\Helpers;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Models\ICollection;
use API\Lib\Interfaces\Models\IModel;

class JsonToModel implements IJsonToModel
{
    public function convert(array $json, $model)
    {
        foreach ($json as $key => $value) {
            if (is_numeric($key) && is_array($value) && $model instanceof ICollection) {
                $this->handleCollection($value, $model);
            } elseif (is_array($value) && $model instanceof IModel) {
                $this->handleRow($key, $value, $model);
            } elseif ($value !== null && $value !== 0) {
                $this->setValue($key, $value, $model);
            }
        }

        return $model;
    }

    private function handleCollection(array $value, ICollection $collection)
    {
        $modelTemplate = $collection->getModel();
        $primaryKeyName = $modelTemplate->getPrimaryKeyName();

        $model = null;

        foreach ($value as $item) {
            if (isset($item[$primaryKeyName]) && $collection->offsetExists($item[$primaryKeyName])) {
                $model = $collection[$item[$primaryKeyName]];
                break;
            }
        }

        if ($model === null) {
            $model = $collection->getModel();
            $collection->append($model);
        }

        $this->convert($value, $model);
    }

    private function handleRow(string $key, array $value, IModel $model)
    {
        $methodName = "get$key";

        if (method_exists($model, $methodName)) {
            $this->convert($value, $model->$methodName());
            return;
        }
    }

    private function setValue(string $key, $value, IModel $model)
    {
        $methodName = "set$key";

        if (method_exists($model, $methodName)) {
            $model->$methodName($value);
            return;
        }
    }
}
