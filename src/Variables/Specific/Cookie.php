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

use MS\LightFramework\Base;
use MS\LightFramework\Exceptions\BadMethodCallException;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Variables\Variables;


/**
 * Class Cookie
 *
 * @package MS\LightFramework\Variables\Specific
 */
final class Cookie extends AbstractWritableCookie
{
    private static $instance;
    private $variables = [];

    /**
     * This method returns class instance.
     *
     * @return Cookie
     */
    public static function getInstance(): Cookie
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Cookie constructor.
     */
    private function __construct()
    {
        $baseClass = Base::getInstance();
        $this->setDomain($baseClass->getServerName(), true);

        foreach ($_COOKIE as $k => $v) {
            $this->variables[$k] = $v;
        }
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->variables = [];
    }

    /**
     * __clone overload
     */
    public function __clone()
    {
        throw new RuntimeException('Clone of '.__CLASS__.' is not allowed');
    }

    /**
     * Calculates cookie expire time
     *
     * @param array $expires
     * @return int
     */
    private function getExpireTime(array $expires = ['m' => 1]): int
    {
        $expireTime = \time();
        if (\is_array($expires)) {
            if (isset($expires['y'])) {
                $expireTime += \intval(0 + $expires['y']) * 60 * 60 * 24 * 365;
            }
            if (isset($expires['m'])) {
                $expireTime += \intval(0 + $expires['m']) * 60 * 60 * 24 * 30;
            }
            if (isset($expires['d'])) {
                $expireTime += \intval(0 + $expires['d']) * 60 * 60 * 24;
            }
            if (isset($expires['h'])) {
                $expireTime += \intval(0 + $expires['h']) * 60 * 60;
            }
            if (isset($expires['i'])) {
                $expireTime += \intval(0 + $expires['i']) * 60;
            }
            if (isset($expires['s'])) {
                $expireTime += \intval(0 + $expires['s']);
            }
        }
        return $expireTime;
    }

    /**
     * Returns COOKIE variable's value. If variable doesn't exist method returns default value for specified type.
     *
     * @param string|null $variableName
     * @param int         $type
     * @return array|float|int|mixed|null|string
     */
    public function get(string $variableName = null, int $type = Variables::TYPE_STRING)
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }
        if (isset($this->variables[$variableName])) {
            return $this->cast($this->variables[$variableName], $type);
        }
        return $this->cast(null, $type);
    }

    /**
     * Sets COOKIE variable.
     *
     * @param string|null $variableName
     * @param null        $variableValue
     * @param array       $expires
     * @param bool        $encrypted
     * @return Cookie
     */
    public function set(string $variableName = null, $variableValue = null, array $expires = ['m' => 1],
                        bool $encrypted = true): Cookie
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }

        \setcookie(
            $variableName,
            $encrypted === true ? $variableValue : $variableValue,
            $this->getExpireTime($expires),
            $this->getPath(),
            $this->getDomain(),
            $this->isSecure(),
            $this->isHttpOnly()
        );
        $this->variables[$variableName] = $variableValue;

        return static::$instance;
    }

    /**
     * Clears (unsets) COOKIE variable.
     *
     * @param string|null $variableName
     * @return Cookie
     */
    public function clear(string $variableName = null): Cookie
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }

        \setcookie(
            $variableName,
            '',
            \time() - 1,
            $this->getPath(),
            $this->getDomain(),
            $this->isSecure(),
            $this->isHttpOnly()
        );
        if (isset($this->variables[$variableName])) {
            unset($this->variables[$variableName]);
            unset($_COOKIE[$variableName]);
        }

        return static::$instance;
    }
}
