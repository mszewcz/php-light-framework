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


/**
 * Interface LogInterface
 *
 * @package MS\LightFramework\Log
 */
interface LogInterface
{
    /**
     * Logs message
     *
     * @param string      $type
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function rawLog(string $type = '', string $message = '', ?string $source = null,
                           ?string $exception = null): bool;

    /**
     * Logs debug
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logDebug(string $message = '', ?string $source = null, ?string $exception = null): bool;

    /**
     * Logs info
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logInfo(string $message = '', ?string $source = null, ?string $exception = null): bool;

    /**
     * Logs warning
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logWarning(string $message = '', ?string $source = null, ?string $exception = null): bool;

    /**
     * Logs error
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logError(string $message = '', ?string $source = null, ?string $exception = null): bool;
}
