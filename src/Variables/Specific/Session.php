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

use MS\LightFramework\Exceptions\BadMethodCallException;
use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Variables\Variables;


/**
 * Class Session
 *
 * @package MS\LightFramework\Variables\Specific
 */
final class Session extends AbstractWritableSession
{
    private static $instance;
    private $variables = [];

    /**
     * This method returns class instance.
     *
     * @return Session
     */
    public static function getInstance(): Session
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Session constructor.
     */
    private function __construct()
    {
        $this->variables = isset($_SESSION) ? $_SESSION : [];
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
     * Returns SESSION variable's value. If variable doesn't exist method returns default value for specified type.
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
        if ($variableName === '_MFREG_') {
            throw new InvalidArgumentException('_MFREG_ variable name is not allowed');
        }
        if (isset($this->variables[$variableName])) {
            return $this->cast(@\unserialize(\base64_decode($this->variables[$variableName])), $type);
        }
        return $this->cast(null, $type);
    }

    /**
     * Sets SESSION variable.
     *
     * @param string|null $variableName
     * @param null        $variableValue
     * @return Session
     */
    public function set(string $variableName = null, $variableValue = null): Session
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }
        if ($variableName === '_MFREG_') {
            throw new InvalidArgumentException('_MFREG_ variable name is not allowed');
        }
        $_SESSION[$variableName] = \base64_encode(\serialize($variableValue));
        $this->variables[$variableName] = \base64_encode(\serialize($variableValue));

        return static::$instance;
    }

    /**
     * Clears (unsets) SESSION variable.
     *
     * @param string|null $variableName
     * @return Session
     */
    public function clear(string $variableName = null): Session
    {
        if ($variableName === null) {
            throw new BadMethodCallException('Variable name must be specified');
        }
        if ($variableName === '_MFREG_') {
            throw new InvalidArgumentException('_MFREG_ variable name is not allowed');
        }
        if (isset($this->variables[$variableName])) {
            unset($_SESSION[$variableName]);
            unset($this->variables[$variableName]);
        }

        return static::$instance;
    }
}
