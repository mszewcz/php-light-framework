<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Log;

use MS\LightFramework\Base;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Filesystem\Directory;


/**
 * Class Log
 *
 * @package MS\LightFramework\Log
 */
final class Log
{
    const LOG_DEBUG = 1;
    const LOG_INFO = 2;
    const LOG_WARNING = 4;
    const LOG_ERROR = 8;

    private static $initialized = false;
    private static $logDir = '';
    private static $classConfig = [];

    /**
     * Sets static class variables
     */
    private static function init(): void
    {
        if (static::$initialized === false) {
            $baseClass = Base::getInstance();

            static::$initialized = true;
            static::$logDir = $baseClass->parsePath((string)$baseClass->Log->logDirectory);
            static::$classConfig = $baseClass->Log->classConfig->toArray();

            if (static::$logDir != '') {
                Directory::create(static::$logDir);
            }
        }
    }

    /**
     * Returns logger for given class
     *
     * @param string $className
     * @return LogInterface
     */
    public static function factory(string $className = ''): LogInterface
    {
        static::init();

        $className = '\\'.$className;
        $loggerConfig = \array_key_exists($className, static::$classConfig)
            ? static::$classConfig[$className]
            : static::$classConfig['Default'];
        $loggerClass = '\\MS\\LightFramework\\Log\\Backend\\'.\ucfirst($loggerConfig['backend']);
        $logDestination = $loggerConfig['destination'];
        $logLevel = 0;

        if ($loggerConfig['logDebug'] === true) {
            $logLevel |= static::LOG_DEBUG;
        }
        if ($loggerConfig['logInfo'] === true) {
            $logLevel |= static::LOG_INFO;
        }
        if ($loggerConfig['logWarning'] === true) {
            $logLevel |= static::LOG_WARNING;
        }
        if ($loggerConfig['logError'] === true) {
            $logLevel |= static::LOG_ERROR;
        }

        $logger = new $loggerClass($logDestination, $logLevel);

        // @codeCoverageIgnoreStart
        if (!($logger instanceof LogInterface)) {
            throw new RuntimeException('Log backend class must implement \\MS\\LightFramework\\Log\\LogInterface');
        }
        // @codeCoverageIgnoreEnd

        return $logger;
    }

    /**
     * Returns path to log directory
     *
     * @return string
     */
    public static function getLogDirectoryPath(): string
    {
        static::init();
        return static::$logDir;
    }
}
