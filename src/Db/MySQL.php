<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db;

use MS\LightFramework\Base;
use MS\LightFramework\Db\MySQL\AbstractMySQL;
use MS\LightFramework\Exceptions\RuntimeException;


/**
 * Class MySQL
 *
 * @package MS\LightFramework\Db
 */
final class MySQL
{
    protected static $initialized = false;
    protected static $baseClass;

    /**
     * Returns MySQL driver according to configuration.
     *
     * @param string|null $driverName
     * @return AbstractMySQL
     */
    public static function getInstance($driverName = null): AbstractMySQL
    {
        if (!static::$initialized) {
            static::$baseClass = Base::getInstance();
        }
        $driverName = $driverName!==null
            ? '\\MS\\LightFramework\\Db\\MySQL\\Drivers\\'.$driverName
            : '\\MS\\LightFramework\\Db\\MySQL\\Drivers\\'.((string) static::$baseClass->Database->MySQL->driverClass);
        /** @noinspection PhpUndefinedMethodInspection */
        $driver = $driverName::getInstance();

        // @codeCoverageIgnoreStart
        if (!($driver instanceof AbstractMySQL)) {
            throw new RuntimeException('MySQL driver must extend \\MS\\LightFramework\\Db\\MySQL\\AbstractMySQL');
        }
        // @codeCoverageIgnoreEnd

        return $driver;
    }
}
