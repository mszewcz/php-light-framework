<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Log\Backend;

use MS\LightFramework\Filesystem\File;
use MS\LightFramework\Html\Tags;
use MS\LightFramework\Log\Log;
use MS\LightFramework\Log\LogInterface;


/**
 * Class Filesystem
 *
 * @package MS\LightFramework\Log\Backend
 */
final class Filesystem implements LogInterface
{
    private $logFile;
    private $logLevel;

    /**
     * Filesystem constructor.
     *
     * @param string $fileName
     * @param int    $logLevel
     */
    public function __construct(string $fileName = '', int $logLevel = 0)
    {
        $this->logFile = Log::getLogDirectoryPath().DIRECTORY_SEPARATOR.$fileName;
        $this->logLevel = $logLevel;
    }

    /**
     * Logs message to file
     *
     * @param string      $type
     * @param string      $message
     * @param null|string $source
     * @param null|string $excp
     * @return bool
     */
    public function rawLog(string $type = '', string $message = '', ?string $source = null, ?string $excp = null): bool
    {
        $src = $source !== null
            ? \sprintf('[%s] ', \is_object($source) ? '\\'.\get_class($source) : (string)$source)
            : '';
        $exc = $excp !== null && ($excp instanceof \Exception) ? \sprintf('[Ex: %s] ', $excp->getMessage()) : '';
        $exc = \str_replace(["\r", "\n", "\t"], '', $exc);
        $msg = \sprintf('%s %s %s%s%s%s', \date('d-m-Y H:i:s'), $type, $src, $exc, $message, Tags::CRLF);

        return File::append($this->logFile, $msg);
    }

    /**
     * Logs debug information
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logDebug(string $message = '', ?string $source = null, ?string $exception = null): bool
    {
        $ret = false;
        if ($this->logLevel & Log::LOG_DEBUG) {
            $ret = $this->rawLog('[  DEBUG]', $message, $source, $exception);
        }
        return $ret;
    }

    /**
     * Logs information
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logInfo(string $message = '', ?string $source = null, ?string $exception = null): bool
    {
        $ret = false;
        if ($this->logLevel & Log::LOG_INFO) {
            $ret = $this->rawLog('[   INFO]', $message, $source, $exception);
        }
        return $ret;
    }

    /**
     * Logs warning
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logWarning(string $message = '', ?string $source = null, ?string $exception = null): bool
    {
        $ret = false;
        if ($this->logLevel & Log::LOG_WARNING) {
            $ret = $this->rawLog('[WARNING]', $message, $source, $exception);
        }
        return $ret;
    }

    /**
     * Logs error
     *
     * @param string      $message
     * @param null|string $source
     * @param null|string $exception
     * @return bool
     */
    public function logError(string $message = '', ?string $source = null, ?string $exception = null): bool
    {
        $ret = false;
        if ($this->logLevel & Log::LOG_ERROR) {
            $ret = $this->rawLog('[  ERROR]', $message, $source, $exception);
        }
        return $ret;
    }

    /**
     * Returns path to log file
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return $this->logFile;
    }
}
