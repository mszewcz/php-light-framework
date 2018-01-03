<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL\Query;

use MS\LightFramework\Db\MySQL\Query\Utilities\Escape;
use MS\LightFramework\Db\MySQL\Query\Utilities\Names;
use MS\LightFramework\Db\MySQL\QueryInterface;
use MS\LightFramework\Exceptions\InvalidArgumentException;


/**
 * Class Insert
 *
 * @package MS\LightFramework\Db\MySQL\Query
 */
final class Insert implements QueryInterface
{
    private $namesClass;
    private $escapeClass;
    private $fields = [];
    private $into = [];

    /**
     * Insert constructor.
     *
     * @param array|null $fields
     */
    public function __construct(array $fields = null)
    {
        if (!\is_array($fields)) {
            throw new InvalidArgumentException('$fields has to be an array');
        }

        $this->namesClass = new Names();
        $this->namesClass->addAliases(\array_keys($fields));
        $this->escapeClass = new Escape();
        $this->fields = $fields;
    }

    /**
     * Adds table names for insert
     *
     * @param mixed|null $table
     * @return Insert
     */
    public function into($table = null): Insert
    {
        $this->into = [];
        if (!\is_string($table) && !\is_array($table)) {
            throw new InvalidArgumentException('$table has to be an array or a string');
        }
        $this->into = $this->namesClass->parse($table, true);

        return $this;
    }

    /**
     * Builds query and returns it.
     *
     * @return string
     */
    public function ___build(): string
    {
        $this->escapeClass->doNotEscape($this->namesClass->getAliases());

        $tables = \implode(', ', $this->into);
        $fields = \implode(',', \array_keys($this->fields));
        $values = \implode(',', \array_map([$this->escapeClass, 'escape'], \array_values($this->fields)));

        return \sprintf('INSERT INTO %s (%s) VALUES (%s)', $tables, $fields, $values);
    }
}
