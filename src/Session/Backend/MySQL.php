<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Session\Backend;

use MS\LightFramework\Db\MySQL as MySQLCLass;


/**
 * Class MySQL
 *
 * @package MS\LightFramework\Session\Backend
 */
final class MySQL implements \SessionHandlerInterface
{
    /* @var \MS\LightFramework\Db\MySQL\AbstractMySQL */
    private $dbClass;
    private $dbTable;

    /**
     * Opens session
     *
     * @param string $savePath
     * @param string $sessionName
     * @return bool
     */
    public function open($savePath, $sessionName): bool
    {
        $this->dbClass = MySQLCLass::getInstance();
        $this->dbTable = 'session_table';
        return true;
    }

    /**
     * Closes session
     *
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * Reads session data
     *
     * @param string $sessionID
     * @return string
     */
    public function read($sessionID): string
    {
        $where = ['session_id' => $sessionID];
        $query = $this->dbClass->prepare()->select(['session_data'])->from($this->dbTable)->where($where);
        $result = $this->dbClass->execute($query);
        $data = $this->dbClass->rowCount() > 0 ? $result[0]['session_data'] : '';

        return $data;
    }

    /**
     * Writes session data
     *
     * @param string $sessionID
     * @param string $sessionData
     * @return bool
     */
    public function write($sessionID, $sessionData): bool
    {
        $dbData = ['session_id' => $sessionID, 'session_access_ts' => time(), 'session_data' => $sessionData];
        $query = $this->dbClass->prepare()->replaceInto($this->dbTable)->set($dbData);
        $result = $this->dbClass->execute($query) == 1 ? true : false;

        return $result;
    }

    /**
     * Destroys session
     *
     * @param string $sessionID
     * @return bool
     */
    public function destroy($sessionID): bool
    {
        $where = ['session_id' => $sessionID];
        $query = $this->dbClass->prepare()->delete()->from($this->dbTable)->where($where);
        $result = $this->dbClass->execute($query) == 1 ? true : false;

        return $result;
    }

    /**
     * Garbages expired sessions
     *
     * @param int $maxLifeTime
     * @return bool
     */
    public function gc($maxLifeTime = 1440): bool
    {
        $where = ['session_access_ts' => ['$lt' => time() - $maxLifeTime]];
        $query = $this->dbClass->prepare()->delete()->from($this->dbTable)->where($where);
        $this->dbClass->execute($query);

        return true;
    }
}
