<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Config;

use MS\LightFramework\Exceptions\RuntimeException;


/**
 * Class Factory
 *
 * @package MS\LightFramework\Config
 */
class Factory
{
    private static $allowedExtensions = ['ini', 'json', 'xml', 'yml'];
    private static $readers = [];
    private static $writers = [];

    /**
     * Replaces slashes (/) and backslashes (\) to DIRECTORY_SEPARATOR
     *
     * @param string $path
     * @return string
     */
    private static function normalizePath(string $path = ''): string
    {
        return \rtrim(\str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
    }

    /**
     * Reads configuration from file
     *
     * @param string $fileName
     * @param bool   $returnArray
     * @return array|bool|Config|string
     */
    public static function read(string $fileName = '', bool $returnArray = false)
    {
        $pathInfo = \pathinfo(static::normalizePath($fileName));
        if (!isset($pathInfo['extension'])) {
            $exMsg = \sprintf(
                'Config file name "%s" is missing an extension and cannot be auto-detected',
                $pathInfo['filename']
            );

            throw new RuntimeException($exMsg);
        }

        $extension = \strtolower($pathInfo['extension']);
        if (!\in_array($extension, static::$allowedExtensions)) {
            $exMsg = \sprintf(
                'Unsupported config file type. Supported extension types are: %s',
                \implode(',', static::$allowedExtensions)
            );

            throw new RuntimeException($exMsg);
        }

        if (!\file_exists($fileName)) {
            throw new RuntimeException('Config file does not exist');
        }

        if (!isset(static::$readers[$extension]) || !(static::$readers[$extension] instanceof AbstractReader)) {
            static::$readers[$extension] = ReaderManager::get($extension);
        }

        return static::$readers[$extension]->fromFile($fileName, $returnArray);
    }

    /**
     * Writes configuration to file
     *
     * @param string $fileName
     * @param        $config
     * @return bool
     */
    public static function write(string $fileName = '', $config = null): bool
    {
        $pathInfo = \pathinfo(static::normalizePath($fileName));
        if (!isset($pathInfo['extension'])) {
            $message = \sprintf(
                'Config file name "%s" is missing an extension and cannot be auto-detected',
                $pathInfo['filename']
            );

            throw new RuntimeException($message);
        }

        $extension = \strtolower($pathInfo['extension']);
        if (!\in_array($extension, static::$allowedExtensions)) {
            $message = \sprintf(
                'Unsupported config file type. Supported extension types are: %s',
                \implode(',', static::$allowedExtensions)
            );

            throw new RuntimeException($message);
        }

        if (!isset(static::$writers[$extension]) || !(static::$writers[$extension] instanceof AbstractWriter)) {
            static::$writers[$extension] = WriterManager::get($extension);
        }

        return static::$writers[$extension]->toFile($fileName, $config);
    }
}
