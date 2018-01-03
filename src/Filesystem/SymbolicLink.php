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
 * Class SymbolicLink
 *
 * @package MS\LightFramework\Filesystem
 */
final class SymbolicLink extends AbstractFilesystem
{
    /**
     * Checks whether link exists.
     *
     * @param string $link
     * @return bool
     */
    public static function exists(string $link = ''): bool
    {
        $link = static::normalizePath($link);
        return static::verifyPath($link) && \file_exists($link) && \is_link($link);
    }

    /**
     * Creates a symbolic link.
     *
     * @param string $link
     * @param string $target
     * @return bool
     */
    public static function create(string $link = '', string $target = ''): bool
    {
        $ret = true;
        $link = static::normalizePath($link);
        $target = static::normalizePath($target);

        if (static::verifyPath($link) && static::verifyPath($target) && Directory::create(\dirname($link)) &&
            !\file_exists($link) && \file_exists($target)) {

            $ret = @\symlink($target, $link);
            @\chmod($link, static::$newSymlinkMode);
        }

        return $ret;
    }

    /**
     * Returns a target of specified symbolic link
     *
     * @param string $link
     * @return string
     */
    public static function read(string $link = ''): string
    {
        $link = static::normalizePath($link);
        if (static::exists($link)) {
            return @\readlink($link);
        }
        return '';
    }

    /**
     * Removes a symbolic link
     *
     * @param string $link
     * @return bool
     */
    public static function remove(string $link = ''): bool
    {
        $link = static::normalizePath($link);
        if (static::exists($link)) {
            return @\unlink($link);
        }
        return false;
    }
}
