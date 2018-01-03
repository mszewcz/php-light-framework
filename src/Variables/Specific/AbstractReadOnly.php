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

use MS\LightFramework\Exceptions\DomainException;
use MS\LightFramework\Variables\Variables;


/**
 * Class AbstractReadOnly
 *
 * @package MS\LightFramework\Variables\Specific
 */
abstract class AbstractReadOnly
{
    /**
     * Returns default value for specified type
     *
     * @param int $type
     * @return array|float|int|string
     */
    protected function getDefault(int $type = Variables::TYPE_STRING)
    {
        switch ($type) {
            case Variables::TYPE_INT:
                $value = (int)0;
                break;
            case Variables::TYPE_FLOAT:
                $value = (float)0;
                break;
            case Variables::TYPE_STRING:
                $value = '';
                break;
            case Variables::TYPE_ARRAY:
                $value = [];
                break;
            case Variables::TYPE_JSON_DECODED:
                $value = '';
                break;
            case Variables::TYPE_AUTO:
                $value = '';
                break;
            default:
                throw new DomainException('Invalid variable type specified');
                break;
        }
        return $value;
    }

    /**
     * Method casting value to specified type. If provided value is null, then default value for specified type will
     * be returned.
     *
     * @param null $value
     * @param int  $type
     * @return array|float|int|mixed|null|string
     */
    protected function cast($value = null, int $type = Variables::TYPE_STRING)
    {
        if ($value === null) {
            return $this->getDefault($type);
        }
        switch ($type) {
            case Variables::TYPE_INT:
                $value = @\intval($value);
                break;
            case Variables::TYPE_FLOAT:
                $value = @\floatval($value);
                break;
            case Variables::TYPE_STRING:
                $value = @\strval($value);
                break;
            case Variables::TYPE_ARRAY:
                $value = (array)$value;
                break;
            case Variables::TYPE_JSON_DECODED:
                $value = @\json_decode(@\strval($value), true, 512) ?: '';
                break;
            case Variables::TYPE_AUTO:
                break;
            default:
                throw new DomainException('Invalid variable type specified');
                break;
        }
        return $value;
    }

    /**
     * Returns variable's value. If variable doesn't exist method returns default value for specified type.
     *
     * @param string $variableName
     * @param int    $type
     * @return mixed
     */
    abstract public function get(string $variableName, int $type = Variables::TYPE_STRING);
}
