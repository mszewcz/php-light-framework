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
 * Class Text
 *
 * @package MS\LightFramework\Html\Controlls
 */
final class Text extends AbstractControlls
{
    /**
     * This method returns <input type="hidden"> controll
     *
     * @param string $name
     * @param mixed $value
     * @param array  $attributes
     * @return string
     */
    public static function inputHidden(string $name = '', $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'hidden';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = $value;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns <input type="password"> controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function inputPassword(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'autocomplete' => 'off', 'class' => 'form-input'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'password';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = $value;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns <input type="text"> controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function inputText(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'form-input'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'text';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = $value;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::input(\array_merge($attributes, $userAttributes));
    }

    /**
     * This method returns textarea controll
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @return string
     */
    public static function textarea(string $name = '', string $value = '', array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'cols' => 20, 'rows' => 4, 'class' => 'form-textarea'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        return Tags::textarea($value, \array_merge($attributes, $userAttributes));
    }
}
