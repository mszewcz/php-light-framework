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
 * Class Delete
 *
 * @package MS\LightFramework\Db\MySQL\Query
 */
final class Delete implements QueryInterface
{
    private $namesClass;
    private $from = [];
    private $where = '';
    private $limit = 0;

    /**
     * Delete constructor.
     */
    public function __construct()
    {
        $this->namesClass = new Names();
    }

    /**
     * Adds table names for delete
     *
     * @param string|null $table
     * @return Delete
     */
    public function from(string $table = null): Delete
    {
        $this->from = [];
        if (!\is_string($table) && !\is_array($table)) {
            throw new InvalidArgumentException('$table has to be an array or a string');
        }
        $this->from = $this->namesClass->parse($table, true);

        return $this;
    }

    /**
     * Adds condition to select
     *
     * @param array|null $condition
     * @return Delete
     */
    public function where(array $condition = null): Delete
    {
        if (!\is_array($condition)) {
            throw new InvalidArgumentException('$condition has to be an array');
        }
        $this->where = (new Condition($this->namesClass->getAliases()))->parse($condition);

        return $this;
    }

    /**
     * Adds limit to select
     *
     * @param int $limit
     * @return Delete
     */
    public function limit(int $limit = 0): Delete
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
        $tables = \implode(', ', $this->from);
        $where = $limit = '';

        if ($this->where !== '') {
            $where = \sprintf(' WHERE %s', $this->where);
        }
        if ($this->limit !== 0) {
            $limit = \sprintf(' LIMIT %s', $this->limit);
        }

        return \sprintf('DELETE FROM %s%s%s', $tables, $where, $limit);
    }
}
