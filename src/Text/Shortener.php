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
 * Class Shortener
 *
 * @package MS\LightFramework\Text
 */
class Shortener
{
    private static $wordEndingChars = ['.', ',', ';', ' '];

    /**
     * Shortens string to given length,
     *
     * @param string   $text
     * @param int|null $charCount
     * @param bool     $fullWords
     * @param string   $suffix
     * @return string
     */
    public static function shorten(string $text = '', ?int $charCount = null, bool $fullWords = true,
                                   string $suffix = '...'): string
    {
        $text = StripTags::strip($text);
        $text = \htmlspecialchars_decode($text, ENT_COMPAT | ENT_HTML5);
        $text = \trim(\preg_replace('/\s+/', ' ', $text));

        if ($charCount === null || \mb_strlen($text, 'UTF-8') <= $charCount) {
            return $text;
        }

        if ($fullWords === true) {
            $shortened = \mb_substr($text, 0, $charCount, 'UTF-8');
            while (!\in_array(\mb_substr($shortened, -1, 1, 'UTF-8'), static::$wordEndingChars)) {
                $shortened = \mb_substr($shortened, 0, -1, 'UTF-8');
            }
        }
        if ($fullWords !== true) {
            $shortened = \mb_substr($text, 0, $charCount, 'UTF-8');
        }

        while (\in_array(\mb_substr($shortened, -1, 1, 'UTF-8'), static::$wordEndingChars)) {
            $shortened = \mb_substr($shortened, 0, -1, 'UTF-8');
        }

        return $shortened.$suffix;
    }
}
