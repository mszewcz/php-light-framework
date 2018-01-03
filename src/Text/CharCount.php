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
 * Class CharCount
 *
 * @package MS\LightFramework\Text
 */
class CharCount
{
    /**
     * Counts characters. Strips HTML tags. Treats multiple spaces as one.
     *
     * @param string $text
     * @param bool   $includeSpaces
     * @return int
     */
    public static function count(string $text = '', bool $includeSpaces = false): int
    {
        if (\trim($text) === '') {
            return 0;
        }
        $text = StripTags::strip($text);
        $text = \htmlspecialchars_decode($text, ENT_COMPAT | ENT_HTML5);
        $text = \preg_replace('/\s+/', $includeSpaces ? ' ' : '', $text);
        $text = \trim($text);

        return \mb_strlen($text, 'UTF-8');
    }
}
