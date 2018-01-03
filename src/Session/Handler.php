<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Session;

use MS\LightFramework\Base;
use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Filesystem\Directory;


/**
 * Class Handler
 *
 * @package MS\LightFramework\Session
 */
final class Handler
{
    private static $invokableClasses = [
        'filesystem' => '\\MS\\LightFramework\\Session\\Backend\\Filesystem',
        'mysql'      => '\\MS\\LightFramework\\Session\\Backend\\MySQL',
    ];
    private static $initialized = false;
    private static $backend;
    private static $savePath;

    /**
     * Sets static class variables
     */
    private static function init(): void
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$backend = (string)$baseClass->Session->Backend;
            static::$savePath = $baseClass->parsePath($baseClass->Session->SavePath);
            static::$savePath = \trim(\session_save_path()) != '' ? \session_save_path() : static::$savePath;

            Directory::create(static::$savePath);
        }
    }

    /**
     * Registers session handler
     *
     * @return bool
     */
    public static function register(): bool
    {
        static::init();
        if (!\array_key_exists(static::$backend, static::$invokableClasses)) {
            throw new InvalidArgumentException('Unsupported session backend: '.static::$backend);
        }
        $handler = new static::$invokableClasses[static::$backend];
        \session_save_path(static::$savePath);

        return \session_set_save_handler($handler, true);
    }

    /**
     * Sets session integrity token
     *
     * @return bool
     */
    public static function setIntegrityToken(): bool
    {
        return Integrity::setToken();
    }

    /**
     * Checks session integrity according to session handler configuration
     *
     * @return bool
     */
    public static function checkIntegrity(): bool
    {
        return Integrity::check();
    }

    /**
     * Sets revalidation timestamp
     *
     * @return bool
     */
    public static function setRevalidationTime(): bool
    {
        return Revalidation::setRevalidationTime();
    }

    /**
     * Checks if session deas need revalidation
     *
     * @return bool
     */
    public static function doesNeedRevalidation(): bool
    {
        return Revalidation::doesNeedRevalidation();
    }
}
