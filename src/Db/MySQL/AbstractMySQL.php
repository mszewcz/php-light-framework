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

use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Html\Tags;
use MS\LightFramework\Log\LogInterface;


/**
 * Class AbstractMySQL
 *
 * @package MS\LightFramework\Db\MySQL
 */
abstract class AbstractMySQL
{
    const E_NO_CONNECTION = 1;
    const E_NO_DB_SELECTED = 2;
    const E_NO_SQL = 3;
    const E_ENGINE_ERROR = 4;

    protected $isConnected = false;
    protected $errorLevel = 'exception';
    /* @var LogInterface */
    protected $logHandler = null;
    /* @var \mysqli */
    protected $currentLink = null;
    protected $currentDB = null;
    protected $selectedDB = null;
    protected $currentQuery = '';
    protected $showQuery = false;
    protected $rowCount = 0;
    protected $connectionCount = 0;
    protected $sqlQueries = [];

    /**
     * __set overload
     *
     * @param string $varName
     * @param mixed  $varValue
     */
    public function __set(string $varName = '', $varValue = ''): void
    {
        if (\in_array($varName, ['showQuery', 'errorLevel'])) {
            $this->$varName = $varValue;
        }
    }

    /**
     * Returns current server time in milliseconds.
     *
     * @return float
     */
    protected function getMicrotime(): float
    {
        list ($uSec, $sec) = \explode(' ', \microtime());
        return ((float)$uSec + (float)$sec);
    }

    /**
     * Handles class errors.
     *
     * @param int    $errorType
     * @param string $className
     */
    protected function setError(int $errorType = 0, string $className = ''): void
    {
        $errorMessage = '';
        if ($errorType == self::E_NO_CONNECTION) {
            $errorMessage = \sprintf(
                'FATAL ERROR (%s): Cannot establish connection to database server.',
                $this->currentLink->connect_errno
            );
        } elseif ($errorType == self::E_NO_DB_SELECTED) {
            $errorMessage = \sprintf(
                'FATAL ERROR (%s): Cannot select database \'%s\'.',
                $this->currentLink->errno,
                $this->selectedDB
            );
        } elseif ($errorType == self::E_NO_SQL) {
            $errorMessage = \sprintf(
                'FATAL ERROR (%s): Query was empty.',
                $this->currentLink->errno
            );
        } elseif ($errorType == self::E_ENGINE_ERROR) {
            $errorMessage = \sprintf(
                'FATAL ERROR (%s): %s: %s',
                $this->currentLink->errno,
                $this->currentLink->error,
                $this->currentQuery
            );
        }

        if ($this->logHandler !== null) {
            $this->logHandler->logError($errorMessage, $className);
        }

        if ($this->errorLevel == 'print') {
            $errorText = \sprintf('%s%s%s%s', $errorMessage, Tags::CRLF, Tags::br(), Tags::CRLF);
            echo filter_var($errorText, FILTER_DEFAULT);
        }
        if ($this->errorLevel == 'exception') {
            $this->closeConnection();
            throw new RuntimeException($errorMessage);
        }
    }

    /**
     * Returns selected database name
     *
     * @return null|string
     */
    public function getSelectedDatabaseName(): ?string
    {
        return $this->selectedDB;
    }

    /**
     * Returns all executed queries
     *
     * @return array
     */
    public function getQueries(): array
    {
        return $this->sqlQueries;
    }

    /**
     * Returns number of selected rows or number of rows affected by update/delete
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * Loads configuration, opens new connection, selects database and sets connection charset & collation.
     *
     * @param mixed $config
     */
    abstract public function loadConfig($config = null): void;

    /**
     * Opens new database connection.
     */
    abstract public function openConnection(): void;

    /**
     * Closes existing database connection.
     */
    abstract public function closeConnection(): void;

    /**
     * Selects a database.
     *
     * @param string $databaseName
     */
    abstract public function selectDatabase(string $databaseName = ''): void;

    /**
     * Sets connection's charset.
     *
     * @param string $characterSet
     */
    abstract public function setConnectionCharset(string $characterSet = ''): void;

    /**
     * Returns connection's collation.
     *
     * @return string
     */
    abstract public function getConnectionCollation(): string;

    /**
     * Sets connection's collation.
     *
     * @param string $collationName
     */
    abstract public function setConnectionCollation(string $collationName = ''): void;

    /**
     * Escapes a value
     *
     * @param mixed $value
     * @return string
     */
    abstract public function escape($value = ''): string;

    /**
     * Prepares a query
     *
     * @return Query
     */
    abstract public function prepare(): Query;

    /**
     * Starts a transaction
     */
    abstract public function beginTransaction(): void;

    /**
     * Commits a transaction.
     */
    abstract public function commit(): void;

    /**
     * Rolls back a transaction.
     */
    abstract public function rollBack(): void;

    /**
     * Executes an SQL query. Returns:
     * - sql result in case of SELECT
     * - last insert ID in case of INSERT
     * - number of affected rows in case of UPDATE/DELETE
     *
     * @param mixed $query
     * @return mixed
     */
    abstract public function execute($query = '');
}
