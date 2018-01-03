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

use MS\LightFramework\Random\Random;


/**
 * Class HashFile
 *
 * @package MS\LightFramework\Filesystem
 */
final class HashFile extends AbstractFilesystem
{
    /**
     * Returns unique hash file name.
     *
     * @param string $extension
     * @return array
     */
    public static function create(string $extension = ''): array
    {
        static::init();
        $filePath = $fileName = '';
        if (static::verifyPath(static::$hashFileDir)) {
            $exists = true;
            while ($exists === true) {
                $hash = Random::hash();
                $hashDir = \implode('/', \str_split(\substr($hash, 0, static::$hashFileDirDepth), 1));
                $filePath = static::normalizePath(\sprintf('%s%s', static::$hashFileDir, $hashDir));
                $filePath .= DIRECTORY_SEPARATOR;
                $fileName = \sprintf('%s.%s', $hash, $extension);
                $exists = File::exists($filePath.$fileName);
            }
        }
        return ['path' => $filePath, 'name' => $fileName];
    }

    /**
     * Checks whether hash file exists
     *
     * @param string $hash
     * @return bool
     */
    public static function exists(string $hash = ''): bool
    {
        return File::exists(static::getPath($hash).$hash);
    }

    /**
     * Returns hash file path
     *
     * @param string $hash
     * @return string
     */
    public static function getPath(string $hash = ''): string
    {
        static::init();
        $hashDir = \implode('/', \str_split(\substr($hash, 0, static::$hashFileDirDepth), 1));
        $filePath = static::normalizePath(\sprintf('%s%s', static::$hashFileDir, $hashDir));
        $filePath .= DIRECTORY_SEPARATOR;

        return $filePath;
    }

    /**
     * Returns hash file url
     *
     * @param string $hash
     * @return string
     */
    public static function getDir(string $hash = ''): string
    {
        static::init();
        $fileDir = \str_replace(
            [static::normalizePath(static::$documentRoot), DIRECTORY_SEPARATOR],
            ['', '/'],
            static::getPath($hash)
        );

        return $fileDir;
    }

    /**
     * Returns hash file url
     *
     * @param string $hash
     * @param array  $hashParams
     * @return string
     */
    public static function getImageUrl(string $hash = '', array $hashParams = ['w' => 0, 'h' => 0, 'gs' => 0]): string
    {
        static::init();
        $fileDir = static::getDir($hash);
        $fileName = $hash;
        $paramsString = '';
        if (isset($hashParams['w']) && $hashParams['w'] > 0) {
            $paramsString .= '_w'.$hashParams['w'];
        }
        if (isset($hashParams['h']) && $hashParams['h'] > 0) {
            $paramsString .= '_h'.$hashParams['h'];
        }
        if (isset($hashParams['gs']) && $hashParams['gs'] == 1) {
            $paramsString .= '_gs1';
        }
        if ($paramsString != '') {
            $fileName = \preg_replace('/(\.[a-z]{3,5})$/', '_'.$paramsString.'$1', $fileName);
        }

        return $fileDir.$fileName;
    }
}
