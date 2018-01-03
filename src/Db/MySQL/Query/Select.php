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
use MS\LightFramework\Db\MySQL\Query\Utilities\Names;
use MS\LightFramework\Db\MySQL\QueryInterface;
use MS\LightFramework\Exceptions\InvalidArgumentException;


/**
 * Class Select
 *
 * @package MS\LightFramework\Db\MySQL\Query
 */
final class Select implements QueryInterface
{
    private $namesClass;
    private $fields = [];
    private $from = [];
    private $join = [];
    private $where = '';
    private $groupBy = [];
    private $having = '';
    private $orderBy = [];
    private $limit = 0;
    private $offset = 0;

    /**
     * Select constructor.
     *
     * @param mixed|null $fields
     */
    public function __construct($fields = null)
    {
        if (!\is_null($fields) && !\is_string($fields) && !\is_array($fields)) {
            throw new InvalidArgumentException('$fields has to be an array or a string');
        }

        $this->namesClass = new Names();
        $this->fields = $this->namesClass->parse($fields);
    }

    /**
     * Adds table names for select
     *
     * @param mixed|null $table
     * @return Select
     */
    public function from($table = null): Select
    {
        $this->from = [];
        if (!\is_string($table) && !\is_array($table)) {
            throw new InvalidArgumentException('$table has to be an array or a string');
        }
        $this->from = $this->namesClass->parse($table, true);

        return $this;
    }

    /**
     * Adds single join to select
     *
     * @param mixed|null $type
     * @param mixed|null $table
     * @param array|null $joinOn
     * @return Select
     */
    public function join($type = null, $table = null, array $joinOn = null): Select
    {
        if (!\is_string($type) || !\preg_match('/left|right|inner|full/i', $type)) {
            throw new InvalidArgumentException('$type has to be left|right|inner|full');
        }
        if (!\is_string($table) && !\is_array($table)) {
            throw new InvalidArgumentException('$table has to be an array or a string');
        }
        if (!\is_array($joinOn)) {
            throw new InvalidArgumentException('$on has to be an array');
        }

        $this->join[] = \sprintf(
            ' %s JOIN %s ON %s',
            \strtoupper($type),
            \implode(', ', $this->namesClass->parse($table, true)),
            (new Condition($this->namesClass->getAliases()))->parse($joinOn)
        );

        return $this;
    }

    /**
     * Adds condition to select
     *
     * @param array|null $condition
     * @return Select
     */
    public function where(array $condition = null): Select
    {
        if (!\is_array($condition)) {
            throw new InvalidArgumentException('$condition has to be an array');
        }
        $this->where = (new Condition($this->namesClass->getAliases()))->parse($condition);

        return $this;
    }

    /**
     * Adds group by to select
     *
     * @param mixed|null $groupBy
     * @return Select
     */
    public function groupby($groupBy = null): Select
    {
        if (!\is_string($groupBy) && !\is_array($groupBy)) {
            throw new InvalidArgumentException('$groupBy has to be an array or a string');
        }
        $this->groupBy = $this->namesClass->parse($groupBy);

        return $this;
    }

    /**
     * Adds having condition to select
     *
     * @param array|null $condition
     * @return Select
     */
    public function having(array $condition = null): Select
    {
        if (!\is_array($condition)) {
            throw new InvalidArgumentException('$condition has to be an array');
        }
        $this->having = (new Condition($this->namesClass->getAliases()))->parse($condition);

        return $this;
    }

    /**
     * Adds order by to select
     *
     * @param mixed|null $orderBy
     * @return Select
     */
    public function orderby($orderBy = null): Select
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
     * @return Select
     */
    public function limit(int $limit = 0): Select
    {
        if (!\is_int($limit) || $limit <= 0) {
            throw new InvalidArgumentException('$limit have to be a positive integer');
        }
        $this->limit = $limit;
        return $this;
    }

    /**
     * Adds offset to select
     *
     * @param int $offset
     * @return Select
     */
    public function offset(int $offset = 0): Select
    {
        if (!\is_int($offset) || $offset < 0) {
            throw new InvalidArgumentException('$offset have to be a positive integer or a zero');
        }
        $this->offset = $offset;
        return $this;
    }

    /**
     * Builds query and returns it.
     *
     * @return string
     */
    public function ___build(): string
    {
        $fields = \implode(', ', $this->fields);
        $from = \implode(', ', $this->from);
        $join = $where = $groupBy = $having = $orderBy = $limit = $offset = '';

        if (\count($this->join) > 0) {
            $join = \implode('', $this->join);
        }
        if ($this->where !== '') {
            $where = \sprintf(' WHERE %s', $this->where);
        }
        if (\count($this->groupBy) > 0) {
            $groupBy = \sprintf(' GROUP BY %s', \implode(', ', $this->groupBy));
        }
        if ($this->having !== '') {
            $having = \sprintf(' HAVING %s', $this->having);
        }
        if (\count($this->orderBy) > 0) {
            $orderBy = \sprintf(' ORDER BY %s', \implode(', ', $this->orderBy));
        }
        if ($this->limit !== 0) {
            $limit = \sprintf(' LIMIT %s', $this->limit);
        }
        if ($this->offset !== 0) {
            $offset = \sprintf(' OFFSET %s', $this->offset);
        }

        return \sprintf(
            'SELECT %s FROM %s%s%s%s%s%s%s%s',
            $fields,
            $from,
            $join,
            $where,
            $groupBy,
            $having,
            $orderBy,
            $limit,
            $offset
        );
    }
}
