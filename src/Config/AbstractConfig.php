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


abstract class AbstractConfig implements \ArrayAccess, \Iterator
{
    protected $skipNextIteration = false;
    protected $data = [];

    /**
     * Defined by ArrayAccess interface
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->__isset($offset);
    }

    /**
     * Defined by ArrayAccess interface
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Defined by ArrayAccess interface
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * Defined by ArrayAccess interface
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
    {
        $this->__unset($offset);
    }

    /**
     * Return data as array
     *
     * @return array
     */
    public function asArray(): array
    {
        return (array)$this->data;
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        $this->skipNextIteration = false;
        return \current($this->data);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return \key($this->data);
    }

    /**
     * Defined by Iterator interface
     */
    public function next(): void
    {
        if ($this->skipNextIteration) {
            $this->skipNextIteration = false;
            return;
        }
        \next($this->data);
    }

    /**
     * Defined by Iterator interface
     */
    public function rewind(): void
    {
        $this->skipNextIteration = false;
        \reset($this->data);
    }

    /**
     * Defined by Iterator interface
     *
     * @return bool
     */
    public function valid(): bool
    {
        return ($this->key() !== null);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    abstract public function __isset($offset): bool;

    /**
     * @param mixed $offset
     * @return mixed
     */
    abstract public function __get($offset);

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    abstract public function __set($offset, $value): void;

    /**
     * @param mixed $offset
     */
    abstract public function __unset($offset): void;
}
