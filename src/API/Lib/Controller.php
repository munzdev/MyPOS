<?php

namespace API\Lib;

use API\Lib\Exceptions\InvalidRequestException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Map\RelationMap;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Controller
{
    protected $o_app;
    protected $o_logger;
    protected $a_json;
    protected $o_request;
    protected $o_response;
    protected $a_args;


    public function __construct(App $o_app)
    {
        $this->o_app = $o_app;        
        $this->o_logger = $o_app->getContainer()->get('logger');                
    }
    
    public function __invoke(Request $o_request, Response $o_response, $a_args) : void
    {
        $this->o_request = $o_request;
        $this->o_response = $o_response;
        $this->a_args = $a_args;
                
        try
        {                    
            $this->a_json = $o_request->getParsedBody();
            
            $this->ANY($o_request, $o_response, $a_args);

            if($o_request->isGet())
                $this->GET();
            elseif($o_request->isPost())
                $this->POST();
            elseif($o_request->isPut())
                $this->PUT();
            elseif($o_request->isDelete())
                $this->DELETE();
            elseif($o_request->isHead())
                $this->HEAD();
            elseif($o_request->isPatch())
                $this->PATCH();
            elseif($o_request->isOptions())
                $this->OPTIONS();        
        } catch (InvalidRequestException $o_exception) {                  
            $this->GenerateJSONErrorFromException($o_exception, 400);
        } catch (\Exception $o_exception) {
            $this->GenerateJSONErrorFromException($o_exception, 500);
        }
    }
    
    protected function validate($a_validators, $a_array = null) : void
    {
        if($a_array == null) 
            $a_array = $this->a_json;
        
        $a_errors = $this->recursiveValidate($a_array, $a_validators);
        
        if($a_errors)
        {
            $str_error = "";
            
            foreach ($a_errors as $str_context => $str_message)
            {
                $str_error .= "$str_context: $str_message\n";
            }
            
            throw new InvalidRequestException(trim($str_error));
        }
    }
    
    protected function jsonToPropel(array $a_json, $o_propel)
    {
        foreach($a_json as $str_key => $value) {
            if(is_numeric($str_key) && is_array($value) && $o_propel instanceOf Collection)
            {
                $str_modelClassName = $o_propel->getFullyQualifiedModel();
                $str_tableMapClassName = $str_modelClassName::TABLE_MAP;                                        
                $o_modelTableMap = $str_tableMapClassName::getTableMap();     
                $a_columns = $o_modelTableMap->getColumns();
                $o_primaryKey = reset($a_columns);
                $str_primaryKeyName = $o_primaryKey->getPhpName();
                
                $a_existingKeys = $o_propel->getPrimaryKeys(false);
                
                $o_model = null;
                         
                foreach($value as $a_model)
                {                    
                    if(isset($a_model[$str_primaryKeyName]) && $str_key = array_search($a_model[$str_primaryKeyName], $a_existingKeys))
                    {
                        $o_model = $o_propel[$str_key];
                        break;
                    }
                }
                
                if($o_model === null)
                {                    
                    $o_model = new $str_modelClassName();
                    $o_propel->append($o_model);
                }
                
                $this->jsonToPropel($value, $o_model);                
            } elseif(is_array($value) && $o_propel instanceOf ActiveRecordInterface) {
                
                $str_tableMapName = $o_propel::TABLE_MAP;                
                $o_tableMap = $str_tableMapName::getTableMap();
                
                if(!$o_tableMap->hasRelation($str_key) && mb_substr($str_key, -1) == 's') {                   
                    $str_key = mb_substr($str_key, 0, -1);                                            
                } elseif(!$o_tableMap->hasRelation($str_key)) {
                    throw new GeneralException('Invalid Array Format given');
                }
                
                $o_relation = $o_tableMap->getRelation($str_key);                
                
                $str_name = $str_key;
                if($o_relation->getPluralName() !== null)
                    $str_name = $o_relation->getPluralName();
                
                $str_methodName = "get$str_name";
                
                if(method_exists($o_propel, $str_methodName)) {
                    $this->jsonToPropel($value, $o_propel->$str_methodName());
                }                                               
            } elseif($value !== null && $value !== 0) {
                $str_methodName = "set$str_key";
                
                if(method_exists($o_propel, $str_methodName))
                    $o_propel->$str_methodName($value); 
                else
                    $o_propel->setVirtualColumn($str_key, $value);
            }
        }
        
        return $o_propel;
    }
    
    private function recursiveValidate($a_json = [], $a_validators = [], $a_actualKeys = []) : array
    {
        $a_errors = [];
        
        foreach ($a_validators as $str_key => $o_validator) {
            $a_actualKeys[] = $str_key;
            $str_value = $this->getNestedParam($a_json, $a_actualKeys);
            if (is_array($o_validator)) {
                $this->recursiveValidate($a_json, $o_validator, $a_actualKeys);
            } else {
                try {
                    $o_validator->assert($str_value);
                } catch (NestedValidationException $exception) {                  
                    $a_errors[implode('.', $a_actualKeys)] = $exception->getFullMessage();
                }
            }

            //Remove the key added in this foreach
            array_pop($a_actualKeys);
        }
        
        return $a_errors;
    }

    /**
     * Get the nested parameter value.
     *
     * @param array $a_json An array that represents the values of the parameters.
     * @param array $a_keys   An array that represents the tree of keys to use.
     *
     * @return mixed The nested parameter value by the given params and tree of keys.
     */
    private function getNestedParam($a_json = [], $a_keys = []) // : ?array
    {
        if (empty($a_keys)) {
            return $a_json;
        } else {
            $str_firstKey = array_shift($a_keys);
            if (array_key_exists($str_firstKey, $a_json)) {
                $a_json = (array) $a_json;
                $str_jsonValue = $a_json[$str_firstKey];

                return $this->getNestedParam($str_jsonValue, $a_keys);
            } else {
                return null;
            }
        }
    }
    
    private function GenerateJSONErrorFromException(\Exception $o_exception, int $i_statusCode) : void
    {        
        $this->o_response = $this->o_response->withJson(array(
            'status' => $i_statusCode,
            'code' => $o_exception->getCode(),
            'title' => $o_exception->getMessage(),
            'detail' => $o_exception->__toString()          
        ), $i_statusCode);
        
        $this->o_app->respond($this->o_response);
        exit;
    }
    
    protected function ANY() : void {}    
    protected function POST() : void {}
    protected function GET() : void {}
    protected function PUT() : void {}
    protected function DELETE() : void {}
    protected function HEAD() : void {}
    protected function PATCH() : void {}
    protected function OPTIONS() : void {}
}