<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL\Query\Utilities;

use MS\LightFramework\Db\MySQL;


/**
 * Class Escape
 *
 * @package MS\LightFramework\Db\MySQL\Query\Utilities
 */
final class Escape
{
    private $dbClass;
    private $doNotEscape = [
        'NOW\(\)', 'CURDATE\(\)', 'CURTIME\(\)', '\(SELECT', 'COUNT\(', 'SUM\(', 'AVG\(', 'UNIX_TIMESTAMP\(',
        '\?$', '\:[a-z0-9\_]', 'NULL',
    ];

    /**
     * Escape constructor.
     *
     * @param array $doNotEscape
     */
    public function __construct(array $doNotEscape = [])
    {
        $this->dbClass = MySQL::getInstance();
        $this->doNotEscape = \array_unique(\array_merge($this->doNotEscape, $doNotEscape));
    }

    /**
     * Adds no quotable expressions
     *
     * @param array $doNotEscape
     */
    public function doNotEscape($doNotEscape = []): void
    {
        $this->doNotEscape = \array_unique(\array_merge($this->doNotEscape, $doNotEscape));
    }

    /**
     * Escapes value using database specific method
     *
     * @param mixed|null $value
     * @param array      $doNotEscape
     * @return mixed
     */
    public function escape($value = null, array $doNotEscape = [])
    {
        $this->doNotEscape = \array_unique(\array_merge($this->doNotEscape, $doNotEscape));

        if (\is_null($value)) {
            return 'NULL';
        }
        if (\is_int($value) || \is_float($value)) {
            return $value;
        }
        if (\is_array($value) || \is_object($value)) {
            return $this->dbClass->escape(\serialize($value));
        }

        foreach ($this->doNotEscape as $doNotEscape) {
            if (\preg_match('/^'.\str_replace('*', '\\*', $doNotEscape).'/i', (string)$value)) {
                return $value;
            }
        }
        return $this->dbClass->escape($value);
    }
}
