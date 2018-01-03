<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Filesystem;


/**
 * Class FileName
 *
 * @package MS\LightFramework\Filesystem
 */
final class FileName
{
    /**
     * Returns filesystem-safe file names.
     *
     * @param string $fileName
     * @param bool   $allowMixedCase
     * @return string
     */
    public static function getSafe(string $fileName = '', bool $allowMixedCase = true): string
    {
        $fileName = \iconv('UTF-8', 'ASCII//TRANSLIT', \trim($fileName));
        if (!$allowMixedCase) {
            $fileName = \strtolower($fileName);
        }
        $fileName = \preg_replace('/[^a-z0-9\-\ \.]/i', '', $fileName);
        $fileName = \preg_replace('/[\s\-]+/', '-', $fileName);

        return $fileName;
    }
}
