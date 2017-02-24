<?php
namespace API\Lib\Helpers;

use API\Lib\Exceptions\GeneralException;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use function mb_substr;

class JsonToModel implements IJsonToModel
{
    public function convert(array $json, $model)
    {
        foreach ($json as $key => $value) {
            if (is_numeric($key) && is_array($value) && $model instanceof Collection) {
                $this->handleCollection($value, $model);
            } elseif (is_array($value) && $model instanceof ActiveRecordInterface) {
                $this->handleRow($key, $value, $model);
            } elseif ($value !== null && $value !== 0) {
                $this->setValue($key, $value, $model);
            }
        }

        return $model;
    }

    private function handleCollection(array $value, Collection $collection)
    {
        $modelClassName = $collection->getFullyQualifiedModel();
        $tableMapClassName = $modelClassName::TABLE_MAP;
        $modelTableMap = $tableMapClassName::getTableMap();
        $columns = $modelTableMap->getColumns();
        $primaryKey = reset($columns);
        $primaryKeyName = $primaryKey->getPhpName();

        $existingKeys = $collection->getPrimaryKeys(false);

        $model = null;

        foreach ($value as $item) {
            if (isset($item[$primaryKeyName]) && $key = array_search($item[$primaryKeyName], $existingKeys)) {
                $model = $collection[$key];
                break;
            }
        }

        if ($model === null) {
            $model = new $modelClassName();
            $collection->append($model);
        }

        $this->convert($value, $model);
    }

    private function handleRow(string $key, array $value, ActiveRecordInterface $propelModel)
    {
        $tableMapName = $propelModel::TABLE_MAP;
        $tableMap = $tableMapName::getTableMap();

        if (!$tableMap->hasRelation($key) && mb_substr($key, -1) == 's') {
            $key = mb_substr($key, 0, -1);
        } elseif (!$tableMap->hasRelation($key)) {
            throw new GeneralException('Invalid Array Format given');
        }

        $relation = $tableMap->getRelation($key);

        $name = $key;
        if ($relation->getPluralName() !== null) {
            $name = $relation->getPluralName();
        }

        $methodName = "get$name";

        if (method_exists($propelModel, $methodName)) {
            $this->convert($value, $propelModel->$methodName());
        }
    }

    private function setValue(string $key, $value, ActiveRecordInterface $propelModel)
    {
        $methodName = "set$key";

        if (method_exists($propelModel, $methodName)) {
            $propelModel->$methodName($value);
            return;
        }

        $$propelModel->setVirtualColumn($key, $value);
    }
}
