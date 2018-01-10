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
 * Class StripTags
 *
 * @package MS\LightFramework\Text
 */
class StripTags
{
    /**
     * Strips tags from text. Preserves allowed tags.
     *
     * @param mixed  $text
     * @param array  $allowedTags
     * @return string
     */
    public static function strip($text = '', array $allowedTags = []): string
    {
        $text = \htmlspecialchars_decode((string)$text, ENT_COMPAT | ENT_HTML5);
        foreach ($allowedTags as $tag) {
            $text = \preg_replace('|<(/?'.$tag.'\ ?/?)>|i', '{{{{\\1}}}}', $text);
            $text = \preg_replace('|<('.$tag.' [^>]+)>|i', '{{{{\\1}}}}', $text);
        }
        $text = \preg_replace('/<[^>]+>/', ' ', $text);
        foreach ($allowedTags as $tag) {
            $text = \preg_replace('|{{{{(/?'.$tag.'\ ?/?)}}}}|i', '<\\1>', $text);
            $text = \preg_replace('|{{{{('.$tag.' [^}]+)}}}}|i', '<\\1>', $text);
        }
        $text = \preg_replace('/\s+/', ' ', $text);

        return \trim($text);
    }
}
