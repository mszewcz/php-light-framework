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
 * Class Choice
 *
 * @package MS\LightFramework\Html\Controlls
 */
final class Choice extends AbstractControlls
{
    /**
     * Returns <input type="radio"> controll
     *
     * @param string $name
     * @param string $value
     * @param bool   $checked
     * @param array  $attributes
     * @return string
     */
    public static function inputRadio(string $name = '', string $value = '', bool $checked = false,
                                      array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'form-radio'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'radio';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = \htmlspecialchars($value);
        $attributes['id'] = isset($userAttributes['id'])
            ? $userAttributes['id']
            : \sprintf('rb_%s_%s', $name, $value);
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id']);

        if ($checked === true) {
            $attributes['checked'] = 'checked';
        }

        return Tags::input(array_merge($attributes, $userAttributes));
    }

    /**
     * Returns <input type="checkbox"> controll
     *
     * @param string $name
     * @param mixed  $value
     * @param bool   $checked
     * @param array  $attributes
     * @return string
     */
    public static function inputCheckbox(string $name = '', $value = '', bool $checked = false,
                                         array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'form-checkbox'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['type'] = 'checkbox';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = \htmlspecialchars($value);
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : \sprintf('cb_%s', $name);

        if (\array_key_exists('multiple', $userAttributes) && $userAttributes['multiple'] === true) {
            $attributes['name'] = \sprintf('%s[]', $attributes['name']);
            $attributes['id'] = \sprintf('%s_%s', $attributes['id'], $value);
        }

        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id', 'multiple']);

        if ($checked === true) {
            $attributes['checked'] = 'checked';
        }

        return Tags::input(array_merge($attributes, $userAttributes));
    }

    /**
     * Returns <optgroup> and <options> tags for select
     *
     * @param array $values
     * @param array $selected
     * @param int   $level
     * @return array
     */
    private static function getOptions(array $values, array $selected, int $level = 0): array
    {
        $options = [];
        foreach ($values as $k => $v) {
            if (\strpos((string)$k, 'OPTGROUP') !== false) {
                $label = \str_replace(['&amp;nbsp;', '&amp;'], [Tags::NBSP, '&'], \htmlspecialchars($v['name']));
                $optAttributes = ['label' => $label];
                if ($level > 0) {
                    $optAttributes['style'] = 'padding-left: '.(10 * $level).'px';
                }
                $options[] = Tags::optgroup(self::getOptions($v['options'], $selected, $level + 1), $optAttributes);
            } else {
                $optAttributes = [
                    'value' => $k,
                    'class' => 'opt-'.\str_replace('/', '', $k),
                ];
                if ($level > 0) {
                    $optAttributes['style'] = 'padding-left: '.(10 * $level).'px';
                }
                if (\in_array($k, $selected)) {
                    $optAttributes['selected'] = 'selected';
                }
                $options[] = Tags::option(
                    \str_replace(['&amp;nbsp;', '&amp;'], [Tags::NBSP, '&'], \htmlspecialchars((string)$v)),
                    $optAttributes
                );
            }
        }
        return $options;
    }

    /**
     * Returns <select> controll
     *
     * @param string $name
     * @param array  $values
     * @param array  $selected
     * @param array  $attributes
     * @return string
     */
    public static function select(string $name = '', array $values = [], array $selected = [],
                                  array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false, 'class' => 'form-select'];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $attributes = [];
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $attributes['data-selected'] = \implode(',', $selected);
        $wrapperClass = ['select-wrapper'];

        if (\array_key_exists('multiple', $userAttributes) && $userAttributes['multiple'] === true) {
            $attributes['multiple'] = 'multiple';
            $attributes['name'] = \sprintf('%s[]', $attributes['name']);
            $wrapperClass[] = 'multiple';
        }
        if (\array_key_exists('wrapper-class', $userAttributes)) {
            $wrapperClass[] = $userAttributes['wrapper-class'];
        }
        if (\array_key_exists('disabled', $userAttributes)) {
            $wrapperClass[] = 'disabled';
        }
        $clearAttributes = ['method-get', 'id', 'multiple', 'wrapper-class'];
        $userAttributes = static::clearAttributes($userAttributes, $clearAttributes);
        $select = Tags::select(self::getOptions($values, $selected, 0), \array_merge($attributes, $userAttributes));

        return Tags::div($select, ['class' => \implode(' ', $wrapperClass)]);
    }

    /**
     * Returns boolean switch controll
     *
     * @param string $name
     * @param array  $values
     * @param bool   $active
     * @param array  $attributes
     * @return string
     */
    public static function booleanSwitch(string $name = '', array $values = [], bool $active = false,
                                         array $attributes = []): string
    {
        $defaultAttributes = ['method-get' => false];
        $userAttributes = \array_merge($defaultAttributes, $attributes);
        $isEnabled = !\array_key_exists('disabled', $userAttributes);
        $attributes = [];
        $attributes['type'] = 'hidden';
        $attributes['name'] = $userAttributes['method-get'] === false ? \sprintf('MFVARS[%s]', $name) : $name;
        $attributes['value'] = $active === true ? 1 : 0;
        $attributes['id'] = isset($userAttributes['id']) ? $userAttributes['id'] : $name;
        $userAttributes = static::clearAttributes($userAttributes, ['method-get', 'id', 'disabled']);

        $switch = [];
        $switch[] = Tags::div(Tags::i(), ['class' => 'switch cf']);
        $switch[] = Tags::label($values[0], ['class' => 'label-0']);
        $switch[] = Tags::label($values[1], ['class' => 'label-1']);
        $switch[] = Tags::input(\array_merge($attributes, $userAttributes));

        $class = ['form-switch', 'cf'];
        if ($active === true) {
            $class[] = 'active';
        }
        if (!$isEnabled) {
            $class[] = 'disabled';
        }
        return Tags::div($switch, ['class' => \implode(' ', $class)]);
    }
}
