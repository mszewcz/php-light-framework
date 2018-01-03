<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Variables\Specific;


/**
 * Class AbstractWritableCookie
 *
 * @package MS\LightFramework\Variables\Specific
 */
abstract class AbstractWritableCookie extends AbstractReadOnly
{
    private $path = '/';
    private $domain = '';
    private $secure = false;
    private $httpOnly = true;

    /**
     * Returns new cookie path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets new cookie path
     *
     * @param string $path
     */
    public function setPath(string $path = ''): void
    {
        $this->path = '/';
        if ($path != '') {
            $this->path .= \substr($path, 0, 1) === '/' ? \substr($path, 1) : $path;
        }
    }

    /**
     * Returns new cookie domain
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Sets new cookie domain and whether cookie should be available for subdomains or not.
     *
     * @param string $domain
     * @param bool   $forSubdomains
     */
    public function setDomain(string $domain = '', bool $forSubdomains = true): void
    {
        $subdomainIndicator = $forSubdomains === true ? '.' : '';
        $this->domain = \sprintf('%s%s', $subdomainIndicator, \preg_replace('/^(\.)?(preview\.)?/', '', $domain));
    }

    /**
     * Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client.
     * When set to TRUE, the cookie will only be set if a secure connection exists.
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Sets cookie security
     */
    public function secureFlagOn(): void
    {
        $this->secure = true;
    }

    /**
     * Unsets cookie security
     */
    public function secureFlagOff(): void
    {
        $this->secure = false;
    }

    /**
     * When TRUE the cookie will be made accessible only through the HTTP protocol.
     * This means that the cookie won't be accessible by scripting languages, such as JavaScript.
     *
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * Sets cookie accessibility only through the HTTP protocol.
     */
    public function httpOnlyFlagOn(): void
    {
        $this->httpOnly = true;
    }

    /**
     * Sets cookie accessibility for all.
     */
    public function httpOnlyFlagOff(): void
    {
        $this->httpOnly = false;
    }

    /**
     * Sets variable.
     *
     * @param string $variableName
     * @param        $variableValue
     * @param array  $expires
     * @return Cookie
     */
    abstract public function set(string $variableName, $variableValue, array $expires): Cookie;

    /**
     * Clears (unsets) variable.
     *
     * @param string $variableName
     * @return Cookie
     */
    abstract public function clear(string $variableName): Cookie;
}
