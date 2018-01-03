<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework;

use MS\LightFramework\Config\AbstractConfig;
use MS\LightFramework\Config\Factory;
use MS\LightFramework\Random\Random;


/**
 * Class Base
 *
 * @package MS\LightFramework
 */
final class Base
{
    private static $instance;
    private $documentRoot;
    private $serverName;
    private $serverProtocol;
    private $requestUri;
    private $referer;
    private $config;

    /**
     * Base constructor.
     */
    private function __construct()
    {
        $documentRoot = realpath(dirname(__DIR__));
        $serverName = 'unknown.foo';
        $serverProtocol = 'HTTP/1.1';
        $requestUri = '';
        $referer = '';

        if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] != '') {
            $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        }
        if (isset($_SERVER['SERVER_NAME'])) {
            $serverName = $_SERVER['SERVER_NAME'];
        }
        if (isset($_SERVER['SERVER_PROTOCOL'])) {
            $serverProtocol = $_SERVER['SERVER_PROTOCOL'];
        }
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
        }

        $this->documentRoot = \rtrim($documentRoot, DIRECTORY_SEPARATOR);
        $this->serverName = \trim($serverName);
        $this->serverProtocol = \trim($serverProtocol);
        $this->requestUri = \trim($requestUri);
        $this->referer = \trim($referer);
        $this->config = Factory::read($_ENV['CONFIG_FILE_FRAMEWORK']);

        $this->createMcryptHashFile();
    }

    /**
     * Creates Base object if needed and returns it
     *
     * @return Base
     */
    public static function getInstance(): Base
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * __clone overload
     */
    public function __clone()
    {
        throw new \RuntimeException('Clone of Config is not allowed.');
    }

    /**
     * Returns Config instance
     *
     * @param string $name
     * @return AbstractConfig|null
     */
    public function __get(string $name): ?AbstractConfig
    {
        return isset($this->config->$name) ? $this->config->$name : null;
    }

    /**
     * Replaces predefined constants in path string to document and framework root
     *
     * @param null|string $path
     * @return null|string
     */
    public function parsePath(?string $path = null): ?string
    {
        if ($path === null) {
            return null;
        }
        $path = \str_replace('%DOCUMENT_ROOT%', $this->documentRoot, $path);
        $path = \str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        return $path;
    }

    /**
     * Returns server name
     *
     * @return string
     */
    public function getServerName(): string
    {
        return $this->serverName;
    }

    /**
     * Returns server protocol
     *
     * @return string
     */
    public function getServerProtocol(): string
    {
        return $this->serverProtocol;
    }

    /**
     * Returns request URI
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    /**
     * Returns referer
     *
     * @return string
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * Creates file with random hash, that will be used for mcrypt key creation
     */
    private function createMcryptHashFile(): void
    {
        if (($hashFile = $this->parsePath($this->config->Encryption->Mcrypt->hashFile)) !== null
            && !\file_exists($hashFile)) {

            $hash = '';
            for ($i = 0; $i < 8; $i++) {
                $hash .= Random::hash();
            }
            \file_put_contents($hashFile, $hash, LOCK_EX);
        }
    }
}
