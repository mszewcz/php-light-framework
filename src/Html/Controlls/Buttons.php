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

use MS\LightFramework\Html\Tags;


/**
 * Class Buttons
 *
 * @package MS\LightFramework\Html\Controlls
 */
final class Buttons extends AbstractControlls
{
    /**
     * This method returns <input type="button"> controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function button(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'formButton'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'button';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = \htmlspecialchars((string)$value);
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns <input type="submit"> controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function buttonSubmit(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'formButton'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'submit';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = \htmlspecialchars((string)$value);
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns <input type="reset"> controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function buttonReset(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'formButton'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'reset';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = \htmlspecialchars((string)$value);
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns <input type="image"> controll
     *
     * @param string $name
     * @param string $src
     * @param array  $attributes
     * @return string
     */
    public static function inputImage(string $name = '', string $src = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'formImage'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'image';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['src'] = $src;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }
}
