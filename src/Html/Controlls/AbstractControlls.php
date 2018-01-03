<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html\Controlls;


class AbstractControlls
{
    /**
     * Clears unwanted attributes
     *
     * @param array $attributes
     * @param array $clear
     * @return array
     */
    protected static function clearAttributes(array $attributes, array $clear): array
    {
        foreach ($clear as $attributeName) {
            if (array_key_exists($attributeName, $attributes)) {
                unset($attributes[$attributeName]);
            }
        }
        return $attributes;
    }

    /**
     * Returns PHP constant or default value if not defined
     *
     * @param string $name
     * @param string $default
     * @return mixed|string
     */
    protected static function getConstant($name = '', $default = '')
    {
        return defined($name) ? constant($name) : $default;
    }
}
