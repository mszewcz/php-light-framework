<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL\Drivers;

use MS\LightFramework\Base;
use MS\LightFramework\Config\AbstractConfig;
use MS\LightFramework\Db\MySQL\AbstractMySQL;
use MS\LightFramework\Db\MySQL\Query;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Log\Log;


/**
 * Class MySQLi
 *
 * @package MS\LightFramework\Db\MySQL\Drivers
 */
final class MySQLi extends AbstractMySQL
{
    private static $instance;
    private $databaseHost = '';
    private $databaseUser = '';
    private $databasePass = '';
    private $databaseName = '';
    private $connectionCharset = '';
    private $connectionCollation = '';
    private $preparedQuery = null;

    /**
     * MySQLi constructor.
     */
    private function __construct()
    {
        $this->logHandler = Log::factory(__CLASS__);
        $this->loadConfig();
    }

    /**
     * Creates MySQLi instance if needed and returns it
     *
     * @return AbstractMySQL
     */
    public static function getInstance(): AbstractMySQL
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * __clone overload
     */
    public function __clone()
    {
        $this->closeConnection();
        throw new RuntimeException('Clone of MySQLi is not allowed');
    }

    /**
     * __destruct overload
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Loads configuration, opens new connection, selects database and sets connection charset & collation.
     *
     * @param mixed $config
     */
    public function loadConfig($config = null): void
    {
        $baseClass = Base::getInstance();
        $this->errorLevel = (string)$baseClass->Database->MySQL->errorLevel;

        if (\is_null($config)) {
            $config = $baseClass->Database->MySQL->defaultConnection;
        }
        if (!($config instanceof AbstractConfig)) {
            throw new RuntimeException('$config must be an instance of MS\LightFramework\Config\AbstractConfig');
        }

        $this->databaseHost = (string)$config->databaseHost;
        $this->databaseUser = (string)$config->databaseUser;
        $this->databasePass = (string)$config->databasePass;
        $this->databaseName = (string)$config->databaseName;
        $this->connectionCharset = (string)$config->connectionCharset;
        $this->connectionCollation = (string)$config->connectionCollation;

        $this->openConnection();
        $this->selectDatabase($this->databaseName);
        $this->setConnectionCharset($this->connectionCharset);
        $this->setConnectionCollation($this->connectionCollation);
    }

    /**
     * Opens new database connection.
     *
     * @return  void
     */
    public function openConnection(): void
    {
        $this->closeConnection();
        $this->currentLink = @new \mysqli($this->databaseHost, $this->databaseUser, $this->databasePass);

        if (!$this->currentLink->connect_error) {
            $this->isConnected = true;
            $this->connectionCount += 1;
        } else {
            $this->setError(AbstractMySQL::E_NO_CONNECTION, __CLASS__);
        }
    }

    /**
     * Closes existing database connection.
     *
     * @return  void
     */
    public function closeConnection(): void
    {
        if ($this->isConnected) {
            @$this->currentLink->close();
            $this->isConnected = false;
            $this->currentLink = null;
            $this->currentDB = null;
            $this->selectedDB = '';
        }
    }

    /**
     * Selects a database.
     *
     * @param   string $databaseName
     * @return  void
     */
    public function selectDatabase(string $databaseName = ''): void
    {
        $this->selectedDB = $databaseName;
        $this->currentDB = $this->currentLink->select_db($databaseName);

        if (!$this->currentDB) {
            $this->setError(AbstractMySQL::E_NO_DB_SELECTED, __CLASS__);
        }
    }

    /**
     * Sets connection's charset.
     *
     * @param   string $characterSet
     * @return  void
     */
    public function setConnectionCharset(string $characterSet = ''): void
    {
        if ($characterSet !== '') {
            $this->currentLink->set_charset($characterSet);
        }
    }

    /**
     * Returns connection's collation.
     *
     * @return String
     */
    public function getConnectionCollation(): string
    {
        return $this->connectionCollation;
    }

    /**
     * Sets connection's collation.
     *
     * @param   string $collationName
     * @return  void
     */
    public function setConnectionCollation(string $collationName = ''): void
    {
        if ($collationName !== '') {
            $this->connectionCollation = $collationName;
            $this->execute('SET SESSION collation_connection = '.$collationName);
        }
    }

    /**
     * Escapes a value
     *
     * @param mixed $value
     * @return string
     */
    public function escape($value = ''): string
    {
        return \sprintf('"%s"', $this->currentLink->real_escape_string($value));
    }

    /**
     * Prepares a query
     *
     * @return Query
     */
    public function prepare(): Query
    {
        $this->preparedQuery = new Query();
        return $this->preparedQuery;
    }

    /**
     * Starts a transaction
     *
     * @codeCoverageIgnore
     */
    public function beginTransaction(): void
    {
        $this->execute('START TRANSACTION');
    }

    /**
     * Commits a transaction.
     *
     * @codeCoverageIgnore
     */
    public function commit(): void
    {
        $this->execute('COMMIT');
    }

    /**
     * Rolls back a transaction.
     *
     * @codeCoverageIgnore
     */
    public function rollBack(): void
    {
        $this->execute('ROLLBACK');
    }

    /**
     * Executes an SQL query. Returns:
     * - sql result in case of SELECT
     * - last insert ID in case of INSERT
     * - number of affected rows in case of UPDATE/DELETE
     *
     * @param mixed $query
     * @return mixed
     */
    public function execute($query = '')
    {
        if (\is_string($query) && $query !== '') {
            $this->currentQuery = $query;
        } elseif ($query instanceof Query) {
            $this->currentQuery = $query->___build();
        } elseif ($this->preparedQuery instanceof Query) {
            $this->currentQuery = $this->preparedQuery->___build();
        }

        if ($this->currentQuery == '') {
            $this->setError(AbstractMySQL::E_NO_SQL, __CLASS__);
            return null;
        }

        $startTime = $this->getMicrotime();
        if (\preg_match('/^CALL/mi', $this->currentQuery)) {
            $this->currentLink->multi_query($this->currentQuery);
            $result = $this->currentLink->store_result();
        } else {
            $result = $this->currentLink->query($this->currentQuery);
        }
        $queryTime = \round($this->getMicrotime() - $startTime, 4);
        $this->sqlQueries[] = ['query' => $this->currentQuery, 'queryTime' => $queryTime];

        if ($this->logHandler !== null) {
            $this->logHandler->logInfo($this->currentQuery, __CLASS__);
        }
        if (!$result && !\preg_match('/^CALL/mi', $this->currentQuery)) {
            $this->setError(AbstractMySQL::E_ENGINE_ERROR, __CLASS__);
        }

        if ($result instanceof \mysqli_result) {
            $ret = [];
            $this->rowCount = $result->num_rows;
            for ($i = 0; $i < $this->rowCount; $i++) {
                $ret[$i] = $result->fetch_assoc();
            }
            $result->close();
            if ($this->currentLink->more_results()) {
                while ($this->currentLink->next_result()) {
                    $result = $this->currentLink->store_result();
                    if ($result instanceof \mysqli_result) {
                        $result->close();
                    }
                }
            }
        } else {
            $this->rowCount = $this->currentLink->affected_rows;
            $ret = \preg_match('/^insert/mi', $this->currentQuery)
                ? $this->currentLink->insert_id
                : $this->rowCount;
        }
        if ($this->showQuery === true) {
            $fetchTime = \round($this->getMicrotime() - $startTime, 4);
            $queryText = \sprintf(
                '<b>Query %s:</b> %s; <b>[Q: %ss, F: %ss]</b><hr />',
                \count($this->sqlQueries),
                $this->currentQuery,
                $queryTime,
                $fetchTime
            );
            echo filter_var($queryText, FILTER_DEFAULT);
        }
        $this->currentQuery = '';

        return $ret;
    }
}
