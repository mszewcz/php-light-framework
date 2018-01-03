<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL;

use MS\LightFramework\Db\MySQL\Query\Delete;
use MS\LightFramework\Db\MySQL\Query\Insert;
use MS\LightFramework\Db\MySQL\Query\ReplaceInto;
use MS\LightFramework\Db\MySQL\Query\Select;
use MS\LightFramework\Db\MySQL\Query\Update;


/**
 * Class Query
 *
 * @package MS\LightFramework\Db\MySQL
 */
final class Query
{
    /* @var QueryInterface */
    private $query;

    /**
     * Prepares SELECT query
     *
     * @param null $fields
     * @return Select
     */
    public function select($fields = null)
    {
        $this->query = new Select($fields);
        return $this->query;
    }

    /**
     * Prepares INSERT query
     *
     * @param null $fields
     * @return Insert
     */
    public function insert($fields = null)
    {
        $this->query = new Insert($fields);
        return $this->query;
    }

    /**
     * Prepares UPDATE query
     *
     * @param null $table
     * @return Update
     */
    public function update($table = null)
    {
        $this->query = new Update($table);
        return $this->query;
    }

    /**
     * Prepares REPLACE query
     *
     * @param null $table
     * @return ReplaceInto
     */
    public function replaceInto($table = null)
    {
        $this->query = new ReplaceInto($table);
        return $this->query;
    }

    /**
     * Prepares DELETE query
     *
     * @return Delete
     */
    public function delete()
    {
        $this->query = new Delete();
        return $this->query;
    }

    /**
     * Builds query
     *
     * @return string
     */
    public function ___build(): string
    {
        return $this->query->___build();
    }
}
