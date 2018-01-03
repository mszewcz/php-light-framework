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
 * Class Directory
 *
 * @package MS\LightFramework\Filesystem
 */
final class Directory extends AbstractFilesystem
{
    /**
     * Checks whether directory exists.
     *
     * @param string $dir
     * @return bool
     */
    public static function exists(string $dir = ''): bool
    {
        $dir = static::normalizePath($dir);
        return static::verifyPath($dir) && \file_exists($dir) && \is_dir($dir);
    }

    /**
     * Checks whether directory is a link
     *
     * @param string $dir
     * @return bool
     */
    public static function isLink(string $dir = ''): bool
    {
        $dir = static::normalizePath($dir);
        return static::exists($dir) && \is_link($dir);
    }

    /**
     * Returns an array of all subdirectories/files from given directory (including symbolic links).
     * If pattern specified, only items that contain pattern in their names will be returned.
     *
     * @param string      $dir
     * @param null|string $pattern
     * @return array
     */
    public static function read(string $dir = '', ?string $pattern = null): array
    {
        $ret = ['subdirs' => [], 'files' => []];
        $dir = static::normalizePath($dir);

        if (static::exists($dir) && ($handle = @\opendir($dir))) {
            while (false !== ($item = @\readdir($handle))) {
                if (!\in_array($item, ['.', '..']) && ($pattern === null || \preg_match($pattern, $item))) {
                    $item = $dir.DIRECTORY_SEPARATOR.$item;
                    if (\is_dir($item)) {
                        $ret['subdirs'][] = $item;
                    } elseif (\is_file($item)) {
                        $ret['files'][] = $item;
                    }
                }
            }
            @\closedir($handle);
        }

        return $ret;
    }

    /**
     * Creates a new directory (or recursively).
     *
     * @param string $dir
     * @return bool
     */
    public static function create(string $dir = ''): bool
    {
        $ret = true;
        $dir = static::normalizePath($dir);
        if (static::verifyPath($dir) && !\file_exists($dir)) {
            $components = \explode(DIRECTORY_SEPARATOR, $dir);
            $newDirectory = '';

            foreach ($components as $component) {
                $newDirectory .= $component.DIRECTORY_SEPARATOR;
                if (static::verifyPath($dir) && !\file_exists($newDirectory)) {
                    @\mkdir($newDirectory, static::$newDirectoryMode);
                    @\chmod($newDirectory, static::$newDirectoryMode);
                }
            }
            return \file_exists($dir) ? true : false;
        }
        return $ret;
    }

    /**
     * Removes a directory with all subdirectories, files and symbolic links inside.
     *
     * @param string $dir
     * @param bool   $emptyOnly
     * @return bool
     */
    public static function remove(string $dir = '', bool $emptyOnly = false): bool
    {
        $ret = true;
        $dir = static::normalizePath($dir);

        if (static::exists($dir)) {
            if ($handle = @\opendir($dir)) {
                while (false !== ($item = @\readdir($handle))) {
                    if ($item !== '.' && $item !== '..') {
                        $item = $dir.DIRECTORY_SEPARATOR.$item;
                        \is_dir($item) ? static::remove($item) : @\unlink($item);
                    }
                }
                @\closedir($handle);
            }
            if ($emptyOnly === false) {
                @\rmdir($dir);
            }
            return true;
        }
        return $ret;
    }

    /**
     * Removes all subdirectories, files and symbolic links inside give directory.
     *
     * @param string $dir
     * @return bool
     */
    public static function emptyDirectory(string $dir = ''): bool
    {
        return static::remove($dir, true);
    }
}
