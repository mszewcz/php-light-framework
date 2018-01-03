<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Image;

use MS\LightFramework\Exceptions\RuntimeException;
use MS\LightFramework\Filesystem\File;


/**
 * Class Ratio
 *
 * @package MS\LightFramework\Image
 */
final class Ratio
{
    /**
     * Resizes image
     *
     * @param string $srcFile
     * @return string
     */
    public static function get(string $srcFile = ''): string
    {
        if (File::exists($srcFile) === false) {
            throw new RuntimeException('Source file does not exist: '.$srcFile);
        }

        $tmp = @\getimagesize($srcFile);
        if (empty($tmp)) {
            throw new RuntimeException('Invalid source file: '.$srcFile);
        }

        return \sprintf('%s:%s', $tmp[0], $tmp[1]);
    }
}
