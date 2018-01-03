<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Filesystem;

use MS\LightFramework\Base;


/**
 * Class AbstractFilesystem
 *
 * @package MS\LightFramework\Filesystem
 */
abstract class AbstractFilesystem
{
    protected static $initialized = false;
    protected static $newDirectoryMode;
    protected static $newFileMode;
    protected static $newSymlinkMode;
    protected static $writeLocks;
    protected static $hashFileDir;
    protected static $hashFileDirDepth;
    protected static $documentRoot;

    /**
     * Sets static class variables
     */
    protected static function init(): void
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$newDirectoryMode = \octdec((string)$baseClass->Filesystem->newDirectoryMode);
            static::$newFileMode = \octdec((string)$baseClass->Filesystem->newFileMode);
            static::$writeLocks = (boolean)$baseClass->Filesystem->writeLocks;
            static::$hashFileDir = $baseClass->parsePath((string)$baseClass->Filesystem->hashFileDirectory);
            static::$hashFileDirDepth = (int)$baseClass->Filesystem->hashFileDirectoryDepth;
            static::$documentRoot = $baseClass->parsePath('%DOCUMENT_ROOT%').DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Replaces slashes (/) and backslashes (\) to DIRECTORY_SEPARATOR
     *
     * @param string $path
     * @return string
     */
    protected static function normalizePath(string $path): string
    {
        static::init();
        return \rtrim(\str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

    /**
     * Verifies, that path to directory is within document root and does not contain current/upper directory (./)
     * path element.
     *
     * @param string $path
     * @return bool
     */
    protected static function verifyPath(string $path = ''): bool
    {
        static::init();
        $currentDir = '.'.DIRECTORY_SEPARATOR;
        return (\stripos($path, static::$documentRoot) !== false && \strpos($path, $currentDir) === false);
    }

    /**
     * Returns default mode for newly created directories
     *
     * @return string
     */
    public static function getNewDirectoryMode(): string
    {
        static::init();
        return (string)\decoct(static::$newDirectoryMode);
    }

    /**
     * Sets default mode for newly created directories
     *
     * @param string $mode
     */
    public static function setNewDirectoryMode(string $mode = '740'): void
    {
        static::init();
        static::$newDirectoryMode = \preg_match('/^[0-7]{3}$/', $mode) ? \intval($mode, 8) : 0740;
    }

    /**
     * Returns default mode for newly created files
     *
     * @return string
     */
    public static function getNewFileMode(): string
    {
        static::init();
        return (string)\decoct(static::$newFileMode);
    }

    /**
     * Sets default mode for newly created files
     *
     * @param string $mode
     */
    public static function setNewFileMode(string $mode = '740'): void
    {
        static::init();
        static::$newFileMode = \preg_match('/^[0-7]{3}$/', $mode) ? \intval($mode, 8) : 0740;
    }

    /**
     * Returns default mode for newly created symbolic links
     *
     * @return string
     */
    public static function getNewSymbolicLinkMode(): string
    {
        static::init();
        return (string)\decoct(static::$newSymlinkMode);
    }

    /**
     * Sets default mode for newly created symbolic links
     *
     * @param string $mode
     */
    public static function setNewSymbolicLinkMode(string $mode = '740'): void
    {
        static::init();
        static::$newSymlinkMode = \preg_match('/^[0-7]{3}$/', $mode) ? \intval($mode, 8) : 0740;
    }

    /**
     * Returns whether locks are acquired during file writing
     *
     * @return bool
     */
    public static function areWriteLocksEnabled(): bool
    {
        static::init();
        return (boolean)static::$writeLocks;
    }

    /**
     * Turns on locks during file writing
     */
    public static function turnOnWriteLocks(): void
    {
        static::init();
        static::$writeLocks = true;
    }

    /**
     * Turns off locks during file writing
     */
    public static function turnOffWriteLocks(): void
    {
        static::init();
        static::$writeLocks = false;
    }
}
