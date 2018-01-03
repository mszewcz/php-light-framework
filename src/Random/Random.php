<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Random;


/**
 * Class Random
 *
 * @package MS\LightFramework\Random
 */
final class Random
{
    /**
     * This method generates random 32 characters long hash.
     *
     * @return string
     */
    public static function hash(): string
    {
        return \md5(\uniqid((string)\mt_rand(), true));
    }

    /**
     * This method returns unique ID for HTML/JS. Returned ID won't start with a number,
     * could start with a prefix and will be truncated to predefined length (8 chars).
     *
     * @param int    $length
     * @param string $prefix
     * @return string
     */
    public static function htmlId(int $length = 8, string $prefix = ''): string
    {
        $tmp = static::hash();
        while (\preg_match('/^[0-9]/', $tmp)) {
            $tmp = \strlen($tmp) <= $length + 1 ? static::hash() : $tmp;
            $tmp = \substr($tmp, 1, \strlen($tmp));
        }
        return $prefix.\substr($tmp, 0, $length);
    }
}
