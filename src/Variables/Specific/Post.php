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
use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Variables\Variables;


/**
 * Class Post
 *
 * @package MS\LightFramework\Variables\Specific
 */
final class Post extends AbstractReadOnly
{
    private static $instance;
    private $variables = [];

    /**
     * This method returns class instance.
     *
     * @return Post
     */
    public static function getInstance(): Post
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Post constructor.
     */
    private function __construct()
    {
        $this->variables = $_POST;
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
     * Returns POST variable's value. If variable doesn't exist method returns default value for specified type.
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
        if (isset($this->variables['MFVARS'][$variableName])) {
            return $this->cast($this->variables['MFVARS'][$variableName], $type);
        }
        if (isset($this->variables[$variableName])) {
            return $this->cast($this->variables[$variableName]);
        }
        return $this->cast(null, $type);
    }
}
