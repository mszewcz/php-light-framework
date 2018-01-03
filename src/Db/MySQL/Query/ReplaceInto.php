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
 * Class ReplaceInto
 *
 * @package MS\LightFramework\Db\MySQL\Query
 */
final class ReplaceInto implements QueryInterface
{
    private $namesClass;
    private $escapeClass;
    private $fields = [];
    private $tables = [];

    /**
     * ReplaceInto constructor.
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
     * @return ReplaceInto
     */
    public function set(array $fields = null): ReplaceInto
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
     * Builds query and returns it.
     *
     * @return string
     */
    public function ___build(): string
    {
        $tables = \implode(', ', $this->tables);
        $fields = \implode(', ', $this->fields);

        return \sprintf('REPLACE INTO %s SET %s', $tables, $fields);
    }
}
