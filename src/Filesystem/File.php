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


/**
 * Class File
 *
 * @package MS\LightFramework\Filesystem
 */
final class File extends AbstractFilesystem
{
    /**
     * Checks whether file exists.
     *
     * @param string $file
     * @return bool
     */
    public static function exists(string $file = ''): bool
    {
        $file = static::normalizePath($file);
        return static::verifyPath($file) && \file_exists($file) && \is_file($file);
    }

    /**
     * Checks whether file is a link
     *
     * @param string $file
     * @return bool
     */
    public static function isLink(string $file = ''): bool
    {
        $file = static::normalizePath($file);
        return static::exists($file) && \is_link($file);
    }

    /**
     * Creates a file.
     *
     * @param string $file
     * @return bool
     */
    public static function create(string $file = ''): bool
    {
        return static::write($file);
    }

    /**
     * Reads data from file.
     *
     * @param string $file
     * @return string
     */
    public static function read(string $file = ''): string
    {
        $result = false;
        $file = static::normalizePath($file);

        if (static::exists($file)) {
            $result = @\file_get_contents($file);
        }

        return $result;
    }

    /**
     * Writes data to file. Creates directories if required.
     *
     * @param string $file
     * @param string $data
     * @param bool   $doAppend
     * @return bool
     */
    public static function write(string $file = '', string $data = '', bool $doAppend = false): bool
    {
        $result = false;
        $file = static::normalizePath($file);
        if (static::verifyPath($file) && Directory::create(\dirname($file))) {
            $flags = $doAppend ? FILE_APPEND : 0;
            $flags |= static::$writeLocks ? LOCK_EX : 0;

            $result = @\file_put_contents($file, $data, $flags) !== false;
            @\chmod($file, static::$newFileMode);
        }
        return $result;
    }

    /**
     * Appends data to file.
     *
     * @param string $file
     * @param string $data
     * @return bool
     */
    public static function append(string $file = '', string $data = ''): bool
    {
        return static::write($file, $data, true);
    }

    /**
     * Removes a file.
     *
     * @param string $srcFile
     * @param string $dstFile
     * @return bool
     */
    public static function move(string $srcFile = '', string $dstFile = ''): bool
    {
        $srcFile = static::normalizePath($srcFile);
        $dstFile = static::normalizePath($dstFile);
        if (static::exists($srcFile) && !static::exists($dstFile) && static::verifyPath($dstFile)) {
            if (Directory::create(\dirname($dstFile))) {
                return @\rename($srcFile, $dstFile);
            }
        }
        return false;
    }


    /**
     * Removes a file.
     *
     * @param string $file
     * @return bool
     */
    public static function remove(string $file = ''): bool
    {
        $file = static::normalizePath($file);
        if (static::exists($file)) {
            return @\unlink($file);
        }
        return false;
    }
}
