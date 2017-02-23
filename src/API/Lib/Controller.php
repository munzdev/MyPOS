<?php

namespace API\Lib;

use API\Lib\Exceptions\GeneralException;
use API\Lib\Exceptions\InvalidRequestException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use const API\DEBUG;
use function mb_substr;

abstract class Controller
{
    protected $app;
    protected $logger;
    protected $json;
    protected $request;
    protected $response;
    protected $args;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->logger = $app->getContainer()->get('logger');
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            $this->json = $request->getParsedBody();

            $this->any($request, $response, $args);

            if ($request->isGet()) {
                $this->get();
            } elseif ($request->isPost()) {
                $this->post();
            } elseif ($request->isPut()) {
                $this->put();
            } elseif ($request->isDelete()) {
                $this->delete();
            } elseif ($request->isHead()) {
                $this->head();
            } elseif ($request->isPatch()) {
                $this->patch();
            } elseif ($request->isOptions()) {
                $this->options();
            }
        } catch (InvalidRequestException $exception) {
            $this->generateJSONErrorFromException($exception, 400);
        } catch (\Exception $exception) {
            $this->generateJSONErrorFromException($exception, 500);
        }

        return $this->response;
    }

    protected function validate($validators, $array = null): void
    {
        if ($array == null) {
            $array = $this->json;
        }

        $errors = $this->recursiveValidate($array, $validators);

        if ($errors) {
            $error = "";

            foreach ($errors as $context => $message) {
                $error .= "$context: $message\n";
            }

            throw new InvalidRequestException(trim($error));
        }
    }

    protected function jsonToPropel(array $json, $propel)
    {
        foreach ($json as $key => $value) {
            if (is_numeric($key) && is_array($value) && $propel instanceof Collection) {
                $modelClassName = $propel->getFullyQualifiedModel();
                $tableMapClassName = $modelClassName::TABLE_MAP;
                $modelTableMap = $tableMapClassName::getTableMap();
                $columns = $modelTableMap->getColumns();
                $primaryKey = reset($columns);
                $primaryKeyName = $primaryKey->getPhpName();

                $existingKeys = $propel->getPrimaryKeys(false);

                $model = null;

                foreach ($value as $item) {
                    if (isset($item[$primaryKeyName]) && $key = array_search($item[$primaryKeyName], $existingKeys)) {
                        $model = $propel[$key];
                        break;
                    }
                }

                if ($model === null) {
                    $model = new $modelClassName();
                    $propel->append($model);
                }

                $this->jsonToPropel($value, $model);
            } elseif (is_array($value) && $propel instanceof ActiveRecordInterface) {
                $tableMapName = $propel::TABLE_MAP;
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

                if (method_exists($propel, $methodName)) {
                    $this->jsonToPropel($value, $propel->$methodName());
                }
            } elseif ($value !== null && $value !== 0) {
                $methodName = "set$key";

                if (method_exists($propel, $methodName)) {
                    $propel->$methodName($value);
                    continue;
                }

                $propel->setVirtualColumn($key, $value);
            }
        }

        return $propel;
    }

    private function recursiveValidate($json = [], $validators = [], $actualKeys = []): array
    {
        $errors = [];

        foreach ($validators as $key => $validator) {
            $actualKeys[] = $key;
            $value = $this->getNestedParam($json, $actualKeys);

            if (is_array($validator)) {
                $this->recursiveValidate($json, $validator, $actualKeys);
                array_pop($actualKeys);
                continue;
            }

            try {
                $validator->assert($value);
            } catch (NestedValidationException $exception) {
                $errors[implode('.', $actualKeys)] = $exception->getFullMessage();
            }

            //Remove the key added in this foreach
            array_pop($actualKeys);
        }

        return $errors;
    }

    /**
     * Get the nested parameter value.
     *
     * @param array $json An array that represents the values of the parameters.
     * @param array $keys An array that represents the tree of keys to use.
     *
     * @return mixed The nested parameter value by the given params and tree of keys.
     */
    private function getNestedParam($json = [], $keys = []) // : ?array
    {
        if (empty($keys)) {
            return $json;
        }

        $firstKey = array_shift($keys);

        if (!array_key_exists($firstKey, $json)) {
            return null;
        }

        $json = (array) $json;
        $jsonValue = $json[$firstKey];

        return $this->getNestedParam($jsonValue, $keys);
    }

    private function generateJSONErrorFromException(\Exception $exception, int $statusCode): void
    {
        $result = array('status' => $statusCode,
            'code' => $exception->getCode(),
            'detail' => get_class($exception) . ': ' .
                        $exception->getMessage() . ' in ' .
                        $exception->getFile() . ':' .
                        $exception->getLine());

        if (DEBUG) {
            $result['trace'] = (array) $exception->getTrace();
        }

        $this->response = $this->response->withJson($result, $statusCode);
    }

    protected function cleanupRecursionData(array $array)
    {
        $relationsFound = [];
        foreach ($array as $key => $item) {
            if ($item === ["*RECURSION*"] || $item == "*RECURSION*") {
                unset($array[$key]);
            } elseif (is_array($item)) {
                $array[$key] = $this->cleanupRecursionData($item);
                $relationsFound[] = $key;
            }
        }

        /* foreach ($a_relationsFound as $relation) {
          $str_key = $relation . 'Id';
          if (isset($a_array[$str_key])) {
          unset($a_array[$str_key]);
          }
          } */

        return $array;
    }

    protected function cleanupUserData(array $user)
    {
        $user['Password'] = null;
        $user['AutologinHash'] = null;
        $user['IsAdmin'] = null;
        $user['CallRequest'] = null;

        if (isset($user['EventUser'])) {
            $user['EventUser']['BeginMoney'] = null;
        }

        return $user;
    }

    protected function withJson($json)
    {
        $this->response = $this->response->withJson($json);
    }

    protected function any(): void
    {
    }

    protected function post(): void
    {
    }

    protected function get(): void
    {
    }

    protected function put(): void
    {
    }

    protected function delete(): void
    {
    }

    protected function head(): void
    {
    }

    protected function patch(): void
    {
    }

    protected function options(): void
    {
    }
}
