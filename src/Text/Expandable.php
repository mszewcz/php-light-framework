<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Text;

use MS\LightFramework\Html\Tags;


/**
 * Class Expandable
 *
 * @package MS\LightFramework\Text
 */
class Expandable
{
    /**
     * Returns PHP constant or default value if not defined
     *
     * @param string $name
     * @return mixed|string
     */
    private static function getConstant(string $name = '')
    {
        return defined($name) ? constant($name) : $name;
    }

    /**
     * Shortens string to given length
     *
     * @param string   $text
     * @param int|null $charCount
     * @return string
     */
    public static function generate(string $text = '', ?int $charCount = null): string
    {
        $text = StripTags::strip($text);
        $text = \htmlspecialchars_decode((string)$text, ENT_COMPAT | ENT_HTML5);
        $text = \trim(\preg_replace('/\s+/', ' ', $text));

        if ($charCount === null || \mb_strlen($text, 'UTF-8') <= $charCount) {
            return $text;
        }

        $expand = Tags::span(static::getConstant('TXT_EXPANDABLE_EXPAND'), ['class' => 'expand-link']);
        $collapse = Tags::span(static::getConstant('TXT_EXPANDABLE_COLLAPSE'), ['class' => 'collapse-link']);
        $short = Shortener::shorten($text, $charCount, true, '');
        $short = Tags::span(\sprintf($short.'...'.$expand), ['class' => 'short']);
        $full = Tags::span(\sprintf($text.$collapse), ['class' => 'full']);

        return Tags::span($short.$full, ['class' => 'expandable']);
    }
}
