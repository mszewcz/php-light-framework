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
 * Class Url
 *
 * @package MS\LightFramework\Variables\Specific
 */
final class Url extends AbstractReadOnly
{
    private static $instance;
    private $variables = [];

    /**
     * This method returns class instance.
     *
     * @return Url
     */
    public static function getInstance(): Url
    {
        if (!isset(static::$instance)) {
            $class = __CLASS__;
            static::$instance = new $class;
        }
        return static::$instance;
    }

    /**
     * Class constructor
     */
    private function __construct()
    {
        $pathinfo = \getenv('PATH_INFO');
        if ($pathinfo != '') {
            $tmp = \explode('/', $pathinfo);
            $tmpCnt = \count($tmp);
            for ($i = 1; $i < $tmpCnt; $i += 2) {
                if (isset($tmp[$i + 1])) {
                    $this->variables[$tmp[$i]] = $tmp[$i + 1];
                }
            }
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
     * Returns URL variable's value. If variable doesn't exist method returns default value for specified type.
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
            return $this->cast(\urldecode($this->variables[$variableName]), $type);
        }
        return $this->cast(null, $type);
    }

    /**
     * Builds URL variables string.
     *
     * @param array $variables
     * @param bool  $useExisting
     * @return string
     */
    public function buildString(array $variables = [], bool $useExisting = true): string
    {
        if ($useExisting === true) {
            $variables = \array_merge($this->variables, $variables);
        }

        $tmp = [];
        foreach ($variables as $name => $val) {
            if ($val !== null) {
                $tmp[] = $name.'/'.\urlencode((string)$val);
            }
        }

        $ret = '';
        if (\count($tmp) > 0) {
            $ret .= \implode('/', $tmp).'/';
        }
        return $ret;
    }
}
