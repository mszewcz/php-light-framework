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
 * Class WriterManager
 *
 * @package MS\LightFramework\Config
 */
class WriterManager
{
    private static $invokableClasses = [
        'ini'  => '\\MS\\LightFramework\\Config\\Writer\\Ini',
        'json' => '\\MS\\LightFramework\\Config\\Writer\\Json',
        'xml'  => '\\MS\\LightFramework\\Config\\Writer\\Xml',
        'yml'  => '\\MS\\LightFramework\\Config\\Writer\\Yml',
    ];

    /**
     * Returns config writer class
     *
     * @param string $type
     * @return AbstractWriter
     */
    public static function get(string $type): AbstractWriter
    {
        if (!\array_key_exists($type, static::$invokableClasses)) {
            throw new InvalidArgumentException('Unsupported config file type: '.$type);
        }

        return new static::$invokableClasses[$type];
    }
}
