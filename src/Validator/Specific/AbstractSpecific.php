<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Validator\Specific;


/**
 * Class AbstractSpecific
 *
 * @package MS\LightFramework\Validator\Specific
 */
abstract class AbstractSpecific
{
    protected $value = null;
    protected $defaultOptions = [];
    protected $options = [];
    protected $errors = [];

    /**
     * Merges default options with user provided options
     *
     * @param array $options
     */
    protected function setOptions(array $options = []): void
    {
        $this->options = \array_merge($this->defaultOptions, $options);
    }

    /**
     * Returns validator options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Sets validator error
     *
     * @param string $text
     */
    public function setError($text = ''): void
    {
        $this->errors[] = $text;
    }

    /**
     * Returns validator errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Sets value
     *
     * @param mixed $value
     */
    protected function setValue($value = null): void
    {
        $this->value = $value;
    }

    /**
     * Validates value
     *
     * @param mixed $value
     * @param array $options
     * @return boolean
     */
    abstract public function isValid($value = null, array $options = []): bool;
}
