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


/**
 * Class WebUrl
 *
 * @package MS\LightFramework\Text
 */
class WebUrl
{
    /**
     * Generates web url
     *
     * @param string   $text
     * @param int|null $maxLength
     * @return string
     */
    public static function generate(string $text = '', ?int $maxLength = null): string
    {
        $text = StripTags::strip($text);
        $text = \strtolower(\iconv('UTF-8', 'ASCII//TRANSLIT', $text));
        $text = \preg_replace('/[^a-z0-9\-\ ]/', '', $text);
        $text = \preg_replace('/[\s\-]+/', '-', $text);
        if (\is_int($maxLength)) {
            $text = \substr($text, 0, $maxLength);
        }
        $text = \rtrim($text, '-');

        return '/'.$text;
    }
}
