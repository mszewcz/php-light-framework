<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Config;

use MS\LightFramework\Exceptions\RuntimeException;


/**
 * Class Config
 *
 * @package MS\LightFramework\Config
 */
final class Config extends AbstractConfig
{
    private $readOnly = false;

    /**
     * Config constructor.
     *
     * @param array $config
     * @param bool  $readOnly
     */
    public function __construct(array $config = [], bool $readOnly = true)
    {
        $this->readOnly = (bool)$readOnly;

        /** @var self $value */
        foreach ($config as $key => $value) {
            $this->data[$key] = \is_array($value) ? new static($value, $this->readOnly) : $value;
        }
    }

    /**
     * __clone() overloading
     */
    public function __clone()
    {
        $array = [];
        foreach ($this->data as $key => $value) {
            $array[$key] = $value instanceof self ? clone $value : $value;
        }
        $this->data = $array;
    }

    /**
     * __get overloading
     *
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return \array_key_exists($name, $this->data) ? $this->data[$name] : null;
    }

    /**
     * __set overloading
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value): void
    {
        if ($this->readOnly) {
            throw new RuntimeException('Config is read only');
        }

        if (\is_array($value)) {
            $value = new static($value, $this->readOnly);
        }

        if ($name === null) {
            $this->data[] = $value;
            return;
        }

        $this->data[$name] = $value;
    }

    /**
     * __isset overloading
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return isset($this->data[$name]);
    }

    /**
     * __unset overloading
     *
     * @param mixed $name
     */
    public function __unset($name): void
    {
        if ($this->readOnly) {
            throw new RuntimeException('Config is read only');
        }
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
            $this->skipNextIteration = true;
        }
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $data = $this->data;

        /** @var self $value */
        foreach ($data as $key => $value) {
            $array[$key] = $value instanceof self ? $value->toArray() : $value;
        }
        return $array;
    }

    /**
     * Marks this Config instance as readOnly
     */
    public function markReadOnly(): void
    {
        $this->readOnly = true;

        /** @var Config $value */
        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->markReadOnly();
            }
        }
    }

    /**
     * Unmarks this Config instance as readOnly (allows modifications)
     */
    public function unmarkReadOnly(): void
    {
        $this->readOnly = false;

        /** @var Config $value */
        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->unmarkReadOnly();
            }
        }
    }

    /**
     * Returns whether this Config object is read only or not.
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }
}
