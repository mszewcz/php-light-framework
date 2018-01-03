<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Validator;

use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Validator\Specific\AbstractSpecific;


/**
 * Class Chain
 *
 * @package MS\LightFramework\Validator
 */
class Chain
{
    private $chain = [];
    private $results = [];
    private $errors = [];

    /**
     * Chain constructor.
     *
     * @param mixed $chain
     */
    public function __construct($chain = [])
    {
        $this->parseChain($chain);
    }

    /**
     * Parses chain array
     *
     * @param array $chain
     */
    private function parseChain($chain = []): void
    {
        if (!\is_array($chain)) {
            throw new InvalidArgumentException('Validators chain must be an array');
        }
        foreach ($chain as $k => $v) {
            if ($v instanceof AbstractSpecific) {
                // value is an instance of AbstractSpecific
                $reflection = new \ReflectionClass($v);
                $name = \str_replace($reflection->getNamespaceName().'\\', '', $reflection->getName());
                $this->chain[$name] = $v;
            } elseif (\is_string($k) && \is_array($v)) {
                // key is a string (validator name) and value is an array (validator options)
                $className = '\\MS\\LightFramework\\Validator\\Specific\\'.$k;
                if (!\class_exists($className)) {
                    throw new InvalidArgumentException('Validator "'.$k.'" not found');
                }
                $this->chain[$k] = new $className($v);
            } elseif (\is_numeric($k) && \is_array($v)) {
                // key is a numeric and value is an array (validator name => validator options)
                $this->parseChain($v);
            } else {
                // incorrect chain format
                $exMsg = 'Validator chain element must be either instance of AbstractValidator ';
                $exMsg .= 'or array("validatorName" => array(options))';
                throw new InvalidArgumentException($exMsg);
            }
        }
    }

    /**
     * Adds new validator to chain
     *
     * @param null $validator
     * @return Chain
     */
    public function add($validator = null): Chain
    {
        $this->parseChain([$validator]);
        return $this;
    }

    /**
     * Removes validator from chain
     *
     * @param string $validator
     * @return Chain
     */
    public function remove($validator = ''): Chain
    {
        if (!\is_string($validator)) {
            throw new InvalidArgumentException('Validator name must be a string');
        }
        if (isset($this->chain[$validator])) {
            unset($this->chain[$validator]);
        }
        return $this;
    }

    /**
     * Removes all validators from chain and clears the result
     */
    public function reset(): void
    {
        $this->chain = [];
        $this->results = [];
    }

    /**
     * Parses option value for getChain method
     *
     * @param mixed $value
     * @return mixed
     */
    private function parseOptionValue($value = null)
    {
        if (\is_bool($value)) {
            $value = $value === true ? 'true' : 'false';
        } elseif (\is_null($value)) {
            $value = 'null';
        } elseif (\is_string($value)) {
            $value = '\''.$value.'\'';
        }
        return $value;
    }

    /**
     * Returns validation chain
     *
     * @param bool $includeOptions
     * @return string
     */
    public function getChain(bool $includeOptions = true): string
    {
        $chain = [];
        foreach ($this->chain as $validatorName => $validatorObj) {
            if ($includeOptions === true) {
                $options = [];
                $tmpOptions = $validatorObj->getOptions();
                foreach ($tmpOptions as $optionName => $optionVal) {
                    $options[] = \sprintf('\'%s\'=>%s', $optionName, $this->parseOptionValue($optionVal));
                }
                $chain[] = \sprintf('%s [%s]', $validatorName, \implode(', ', $options));
            } else {
                $chain[] = $validatorName;
            }
        }
        return \implode(",\n", $chain);
    }

    /**
     * Returns validation chain results
     *
     * @return array
     */
    public function getChainResults(): array
    {
        return $this->results;
    }

    /**
     * Returns validation chain errors
     *
     * @return array
     */
    public function getChainErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validates value
     *
     * @param null  $value
     * @param array $chain
     * @return bool
     */
    public function isValid($value = null, array $chain = []): bool
    {
        $this->parseChain($chain);

        if (empty($this->chain)) {
            throw new RuntimeException('No validators found in chain');
        }

        $this->results = [];
        $isValid = true;
        foreach ($this->chain as $name => $validator) {
            $result = $validator->isValid($value);
            $this->errors[$name] = $validator->getErrors();
            if ($result === true) {
                $this->results[$name] = true;
            } else {
                $this->results[$name] = false;
                $isValid = false;
            }
        }

        return $isValid;
    }
}
