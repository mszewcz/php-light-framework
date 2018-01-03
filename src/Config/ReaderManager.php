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

use MS\LightFramework\Exceptions\InvalidArgumentException;

/**
 * Class ReaderManager
 *
 * @package MS\LightFramework\Config
 */
class ReaderManager
{
    private static $invokableClasses = [
        'ini'  => '\\MS\\LightFramework\\Config\\Reader\\Ini',
        'json' => '\\MS\\LightFramework\\Config\\Reader\\Json',
        'xml'  => '\\MS\\LightFramework\\Config\\Reader\\Xml',
        'yml'  => '\\MS\\LightFramework\\Config\\Reader\\Yml',
    ];

    /**
     * Returns config reader class
     *
     * @param string $type
     * @return AbstractReader
     */
    public static function get(string $type): AbstractReader
    {
        if (!\array_key_exists($type, static::$invokableClasses)) {
            throw new InvalidArgumentException('Unsupported config file type: '.$type);
        }

        return new static::$invokableClasses[$type];
    }
}
