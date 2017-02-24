<?php
namespace API\Lib\Helpers;

use API\Lib\Exceptions\InvalidRequestException;
use API\Lib\Interfaces\Helpers\IValidate;
use Respect\Validation\Exceptions\NestedValidationException;

class Validate implements IValidate
{
    /**
     *
     * @param type $validators
     * @param type $data
     * @throws InvalidRequestException
     */
    public function assert(array $validators, array $data): void
    {
        $errors = $this->recursiveValidate($data, $validators);

        if ($errors) {
            $error = "";

            foreach ($errors as $context => $message) {
                $error .= "$context: $message\n";
            }

            throw new InvalidRequestException(trim($error));
        }
    }

    private function recursiveValidate(array $data, array $validators, array $actualKeys = []): array
    {
        $errors = [];

        foreach ($validators as $key => $validator) {
            $actualKeys[] = $key;
            $value = $this->getNestedParam($data, $actualKeys);

            if (is_array($validator)) {
                $this->recursiveValidate($data, $validator, $actualKeys);
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
     * @param array $data An array that represents the values of the parameters.
     * @param array $keys An array that represents the tree of keys to use.
     *
     * @return mixed The nested parameter value by the given params and tree of keys.
     */
    private function getNestedParam($data, $keys) // : ?array
    {
        if (empty($keys)) {
            return $data;
        }

        $firstKey = array_shift($keys);

        if (!array_key_exists($firstKey, $data)) {
            return null;
        }

        $value = $data[$firstKey];

        return $this->getNestedParam($value, $keys);
    }
}
