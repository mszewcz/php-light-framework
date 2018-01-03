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

use MS\LightFramework\Db\MySQL\Query\Utilities\Condition;
use MS\LightFramework\Db\MySQL\Query\Utilities\Escape;
use MS\LightFramework\Db\MySQL\Query\Utilities\Names;
use MS\LightFramework\Db\MySQL\QueryInterface;
use MS\LightFramework\Exceptions\InvalidArgumentException;


/**
 * Class Update
 *
 * @package MS\LightFramework\Db\MySQL\Query
 */
final class Update implements QueryInterface
{
    private $namesClass;
    private $escapeClass;
    private $fields = [];
    private $tables = [];
    private $where = '';
    private $orderBy = [];
    private $limit = 0;

    /**
     * Update constructor.
     *
     * @param mixed|null $table
     */
    public function __construct($table = null)
    {
        if (!\is_string($table) && !\is_array($table)) {
            throw new InvalidArgumentException('$table has to be an array or a string');
        }

        $this->namesClass = new Names();
        $this->escapeClass = new Escape();
        $this->tables = $this->namesClass->parse($table, true);
    }

    /**
     * Adds table names for select
     *
     * @param array|null $fields
     * @return Update
     */
    public function set(array $fields = null): Update
    {
        if (!\is_array($fields)) {
            throw new InvalidArgumentException('$fields has to be an array or a string');
        }

        $this->namesClass->addAliases(\array_keys($fields));
        foreach ($fields as $name => $value) {
            $this->fields[] = \sprintf(
                '%s=%s',
                $name,
                $this->escapeClass->escape($value, $this->namesClass->getAliases())
            );
        }

        return $this;
    }

    /**
     * Adds condition to select
     *
     * @param array|null $condition
     * @return Update
     */
    public function where(array $condition = null): Update
    {
        if (!\is_array($condition)) {
            throw new InvalidArgumentException('$condition has to be an array');
        }
        $this->where = (new Condition($this->namesClass->getAliases()))->parse($condition);

        return $this;
    }

    /**
     * Adds order by to select
     *
     * @param mixed|null $orderBy
     * @return Update
     */
    public function orderby($orderBy = null): Update
    {
        if (!\is_string($orderBy) && !\is_array($orderBy)) {
            throw new InvalidArgumentException('$orderBy has to be an array or a string');
        }
        $this->orderBy = $this->namesClass->parse($orderBy);

        return $this;
    }

    /**
     * Adds limit to select
     *
     * @param int $limit
     * @return Update
     */
    public function limit(int $limit = 0): Update
    {
        if (!\is_int($limit) || $limit <= 0) {
            throw new InvalidArgumentException('$limit have to be a positive integer');
        }
        $this->limit = $limit;
        return $this;
    }

    /**
     * Builds query and returns it.
     *
     * @return string
     */
    public function ___build(): string
    {
        $tables = \implode(', ', $this->tables);
        $fields = \implode(', ', $this->fields);
        $where = $orderBy = $limit = '';

        if ($this->where != '') {
            $where = \sprintf(' WHERE %s', $this->where);
        }
        if (\count($this->orderBy) > 0) {
            $orderBy = \sprintf(' ORDER BY %s', \implode(', ', $this->orderBy));
        }
        if ($this->limit != 0) {
            $limit = \sprintf(' LIMIT %s', $this->limit);
        }

        return \sprintf('UPDATE %s SET %s%s%s%s', $tables, $fields, $where, $orderBy, $limit);
    }
}
