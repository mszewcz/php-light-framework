<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html;

use MS\LightFramework\Base;


/**
 * Class Tags
 *
 * @package MS\LightFramework\Html
 *
 * @method static a($name = '', $arguments = [])
 * @method static abbr($name = '', $arguments = [])
 * @method static address($name = '', $arguments = [])
 * @method static area($arguments = [])
 * @method static article($name = '', $arguments = [])
 * @method static aside($name = '', $arguments = [])
 * @method static audio($name = '', $arguments = [])
 * @method static b($name = '', $arguments = [])
 * @method static base($arguments = [])
 * @method static bdi($name = '', $arguments = [])
 * @method static bdo($name = '', $arguments = [])
 * @method static blockquote($name = '', $arguments = [])
 * @method static body($name = '', $arguments = [])
 * @method static br($arguments = [])
 * @method static button($name = '', $arguments = [])
 * @method static canvas($name = '', $arguments = [])
 * @method static caption($name = '', $arguments = [])
 * @method static cite($name = '', $arguments = [])
 * @method static col($name = '', $arguments = [])
 * @method static code($name = '', $arguments = [])
 * @method static colgroup($name = '', $arguments = [])
 * @method static command($name = '', $arguments = [])
 * @method static datalist($name = '', $arguments = [])
 * @method static dd($name = '', $arguments = [])
 * @method static del($name = '', $arguments = [])
 * @method static details($name = '', $arguments = [])
 * @method static dfn($name = '', $arguments = [])
 * @method static div($name = '', $arguments = [])
 * @method static dl($name = '', $arguments = [])
 * @method static dt($name = '', $arguments = [])
 * @method static em($name = '', $arguments = [])
 * @method static embed($name = '', $arguments = [])
 * @method static fieldset($name = '', $arguments = [])
 * @method static figcaption($name = '', $arguments = [])
 * @method static figure($name = '', $arguments = [])
 * @method static footer($name = '', $arguments = [])
 * @method static form($name = '', $arguments = [])
 * @method static h1($name = '', $arguments = [])
 * @method static h2($name = '', $arguments = [])
 * @method static h3($name = '', $arguments = [])
 * @method static h4($name = '', $arguments = [])
 * @method static h5($name = '', $arguments = [])
 * @method static h6($name = '', $arguments = [])
 * @method static head($name = '', $arguments = [])
 * @method static header($name = '', $arguments = [])
 * @method static hr($arguments = [])
 * @method static html($name = '', $arguments = [])
 * @method static i($name = '', $arguments = [])
 * @method static iframe($name = '', $arguments = [])
 * @method static img($name = '', $arguments = [])
 * @method static input($name = '', $arguments = [])
 * @method static ins($name = '', $arguments = [])
 * @method static kbd($name = '', $arguments = [])
 * @method static keygen($name = '', $arguments = [])
 * @method static label($name = '', $arguments = [])
 * @method static legend($name = '', $arguments = [])
 * @method static li($name = '', $arguments = [])
 * @method static link($name = '', $arguments = [])
 * @method static map($name = '', $arguments = [])
 * @method static mark($name = '', $arguments = [])
 * @method static menu($name = '', $arguments = [])
 * @method static meta($name = '', $arguments = [])
 * @method static meter($name = '', $arguments = [])
 * @method static nav($name = '', $arguments = [])
 * @method static noscript($name = '', $arguments = [])
 * @method static object($name = '', $arguments = [])
 * @method static ol($name = '', $arguments = [])
 * @method static option($name = '', $arguments = [])
 * @method static optgroup($name = '', $arguments = [])
 * @method static output($name = '', $arguments = [])
 * @method static p($name = '', $arguments = [])
 * @method static param($name = '', $arguments = [])
 * @method static pre($name = '', $arguments = [])
 * @method static progress($name = '', $arguments = [])
 * @method static q($name = '', $arguments = [])
 * @method static rp($name = '', $arguments = [])
 * @method static rt($name = '', $arguments = [])
 * @method static ruby($name = '', $arguments = [])
 * @method static s($name = '', $arguments = [])
 * @method static samp($name = '', $arguments = [])
 * @method static script($name = '', $arguments = [])
 * @method static section($name = '', $arguments = [])
 * @method static select($name = '', $arguments = [])
 * @method static small($name = '', $arguments = [])
 * @method static source($name = '', $arguments = [])
 * @method static span($name = '', $arguments = [])
 * @method static strong($name = '', $arguments = [])
 * @method static style($name = '', $arguments = [])
 * @method static sub($name = '', $arguments = [])
 * @method static summary($name = '', $arguments = [])
 * @method static sup($name = '', $arguments = [])
 * @method static table($name = '', $arguments = [])
 * @method static tbody($name = '', $arguments = [])
 * @method static td($name = '', $arguments = [])
 * @method static textarea($name = '', $arguments = [])
 * @method static tfoot($name = '', $arguments = [])
 * @method static th($name = '', $arguments = [])
 * @method static thead($name = '', $arguments = [])
 * @method static time($name = '', $arguments = [])
 * @method static title($name = '', $arguments = [])
 * @method static tr($name = '', $arguments = [])
 * @method static track($name = '', $arguments = [])
 * @method static u($name = '', $arguments = [])
 * @method static ul($name = '', $arguments = [])
 * @method static vartag($name = '', $arguments = [])
 * @method static video($name = '', $arguments = [])
 * @method static wbr($name = '', $arguments = [])
 */
class Tags
{
    const CRLF = "\r\n";
    const NBSP = '&nbsp;';
    private static $initialized = false;
    private static $tagsData = [];

    /**
     * Sets static class variables
     */
    private static function init()
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$tagsData = $baseClass->Html->Tags;
        }
    }

    /**
     * Extract tag content from __callStatic $arguments
     *
     * @param      $arguments
     * @param bool $hasContent
     * @return mixed|string
     */
    private static function getTagContent($arguments, bool $hasContent)
    {
        $content = '';
        if (\is_array($arguments) && isset($arguments[0]) && $hasContent === true) {
            $content = $arguments[0];
        }
        return $content;
    }

    /**
     * Extract tag attributes from __callStatic $arguments
     *
     * @param      $arguments
     * @param bool $hasContent
     * @return array
     */
    private static function getTagAttributes($arguments, bool $hasContent): array
    {
        if (\is_array($arguments)) {
            if ($hasContent === true && \count($arguments) == 2) {
                return \is_array($arguments[1]) ? $arguments[1] : [];
            }
            if ($hasContent === false) {
                if (\count($arguments) == 2) {
                    return \is_array($arguments[1]) ? $arguments[1] : [];
                }
                if (\count($arguments) == 1) {
                    return \is_array($arguments[0]) ? $arguments[0] : [];
                }
            }
        }
        return [];
    }

    /**
     * Clears unwanted attributes
     *
     * @param array $attributes
     * @param array $clear
     * @return array
     */
    private static function clearAttributes(array $attributes, array $clear): array
    {
        foreach ($clear as $name) {
            if ($name === 'ALL') {
                return [];
            }
            if (\array_key_exists($name, $attributes)) {
                unset($attributes[$name]);
            }
        }
        return $attributes;
    }

    /**
     * Sets default attributes
     *
     * @param array $attributes
     * @param array $default
     * @return array
     */
    private static function setDefaultAttributes(array $attributes, array $default): array
    {
        foreach ($default as $name => $value) {
            if (!\array_key_exists($name, $attributes)) {
                $attributes[$name] = $value;
            }
        }
        return $attributes;
    }

    /**
     * This method transforms attributes array to string
     *
     * @param array $attributes
     * @return string
     */
    private static function extractAttributes(array $attributes = []): string
    {
        $extracted = [];
        if (\count($attributes) > 0) {
            foreach ($attributes as $name => $value) {
                if ($name != 'id' || ($name == 'id' && $value != '')) {
                    $extracted[] = sprintf(
                        '%s="%s"',
                        \strtolower($name),
                        \htmlspecialchars((string)$value, ENT_COMPAT | ENT_HTML5)
                    );
                }
            }
            return \sprintf(' %s', \implode(' ', $extracted));
        }
        return '';
    }

    /**
     * Builds html tag
     *
     * @param string $name
     * @param        $arguments
     * @return string
     */
    private static function buildTag(string $name, $arguments): string
    {
        $tagConf = static::$tagsData[$name];
        $hasValue = (boolean)$tagConf['hasValue'];
        $clear = \iterator_to_array($tagConf['clearAttributes'], true);
        $default = \iterator_to_array($tagConf['defaultAttributes'], true);

        $tagName = $name == 'vartag' ? 'var' : $name;
        $tagValue = static::getTagContent($arguments, $hasValue);
        $attributes = static::getTagAttributes($arguments, $hasValue);
        $attributes = static::clearAttributes($attributes, $clear);
        $attributes = static::setDefaultAttributes($attributes, $default);
        $newLine = '';

        if (\is_array($tagValue)) {
            $tagValue = \implode(static::CRLF, $tagValue);
            $newLine = static::CRLF;
        } elseif (\in_array($tagName, ['body', 'head', 'html'])) {
            $newLine = static::CRLF;
        }

        if ($hasValue === true) {
            return \sprintf(
                '<%s%s>%s%s%s</%s>%s',
                $tagName,
                static::extractAttributes($attributes),
                $newLine,
                \rtrim(\str_replace(static::CRLF.static::CRLF, static::CRLF, $tagValue), static::CRLF),
                $newLine,
                $tagName,
                $newLine
            );
        }

        if (\in_array($tagName, ['iframe'])) {
            return \sprintf('<%s%s></%s>', $tagName, static::extractAttributes($attributes), $tagName);
        }

        return \sprintf('<%s%s/>', $tagName, static::extractAttributes($attributes));
    }

    /**
     * This method returns html comment
     *
     * @param string $text
     * @param bool   $addSpaces
     * @return string
     */
    public static function comment(string $text = '', bool $addSpaces = false): string
    {
        $space = $addSpaces === true ? ' ' : '';
        return \sprintf('<!--%s%s%s-->', $space, $text, $space);
    }

    /**
     * This method returns <!DOCTYPE> tag
     *
     * @param string $definition
     * @return string
     */
    public static function doctype(string $definition = 'html'): string
    {
        return \sprintf('<!DOCTYPE %s>', $definition);
    }

    /**
     * __callStatic overload
     *
     * @param string $name
     * @param        $arguments
     * @return string
     */
    public static function __callStatic(string $name, $arguments): string
    {
        static::init();
        $tag = '';
        if (isset(static::$tagsData[$name])) {
            $tag = static::buildTag($name, $arguments);
        }
        return $tag;
    }
}
