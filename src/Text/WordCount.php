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
 * Class WordCount
 *
 * @package MS\LightFramework\Text
 */
class WordCount
{
    /**
     * Counts words with provided min length
     *
     * @param string $text
     * @param int    $minWorldLength
     * @return int
     */
    public static function count(string $text = '', int $minWorldLength = 0): int
    {
        if (\trim($text) === '') {
            return 0;
        }
        $text = StripTags::strip($text);
        $text = \htmlspecialchars_decode($text, ENT_COMPAT | ENT_HTML5);
        $text = \preg_replace('/[^\w[:space:]]/u', '', $text);
        if ($minWorldLength > 1) {
            $text = \preg_replace('/(\b\w{1,'.($minWorldLength - 1).'}\b)/u', ' ', $text);
        }
        $text = \trim(\preg_replace('/\s+/', ' ', $text));
        if ($text === '') {
            return 0;
        }
        $words = \explode(' ', $text);

        return \count($words);
    }
}
