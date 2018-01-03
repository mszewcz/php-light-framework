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
use MS\LightFramework\Filesystem\Directory;
use MS\LightFramework\Filesystem\File;


/**
 * Class Resizer
 *
 * @package MS\LightFramework\Image
 */
final class Resizer
{
    private static $qualityPNG = 1;
    private static $quality = 90;
    private static $defaultOptions = ['w' => 0, 'h' => 0, 'gs' => false, 'quality' => 90];

    /**
     * Returns source image function
     *
     * @param int|null $imgType
     * @return null|string
     */
    private static function getSourceFunction(?int $imgType = null): ?string
    {
        switch ($imgType) {
            case IMAGETYPE_JPEG:
                $src['function'] = 'imagecreatefromjpeg';
                break;
            case IMAGETYPE_PNG:
                $src['function'] = 'imagecreatefrompng';
                break;
            case IMAGETYPE_GIF:
                $src['function'] = 'imagecreatefromgif';
                break;
            default:
                $src['function'] = null;
                break;
        }
        return $src['function'];
    }

    /**
     * Returns destination image function
     *
     * @param string|null $imgExtension
     * @return null|string
     */
    private static function getDestinationFunction(string $imgExtension = null): ?string
    {
        switch ($imgExtension) {
            case 'jpg':
            case 'jpeg':
                $dst['function'] = 'imagejpeg';
                break;
            case 'png':
                $dst['function'] = 'imagepng';
                break;
            case 'gif':
                $dst['function'] = 'imagegif';
                break;
            default:
                $dst['function'] = null;
                break;
        }
        return $dst['function'];
    }

    /**
     * Sets output quality
     *
     * @param int $quality
     */
    private static function setQuality(int $quality = 90): void
    {
        $quality = $quality < 10 || $quality > 100 ? 90 : $quality;
        static::$quality = $quality;
        static::$qualityPNG = (int)\round((100 - $quality) / 10, 0);
    }

    /**
     * Return destination image quality
     *
     * @param null|string $imgExtension
     * @return int
     */
    private static function getDestinationQuality(?string $imgExtension = null): int
    {
        switch ($imgExtension) {
            case 'png':
                $quality = static::$qualityPNG;
                break;
            default:
                $quality = static::$quality;
                break;
        }
        return $quality;
    }

    /**
     * Calculates resize data
     *
     * @param array $srcSize
     * @param array $dstOptions
     * @return array
     */
    private static function calculateResizeData(array $srcSize = [], array $dstOptions = []): array
    {
        $srcRatio = $srcSize['w'] / $srcSize['h'];
        $dstSize = ['w' => 0, 'h' => 0];

        if ($dstOptions['w'] > $srcSize['w']) {
            $dstOptions['w'] = $srcSize['w'];
        }
        if ($dstOptions['h'] > $srcSize['h']) {
            $dstOptions['h'] = $srcSize['h'];
        }

        if ($dstOptions['w'] <= 0 && $dstOptions['h'] <= 0) {
            $dstOptions['w'] = $srcSize['w'];
            $dstOptions['h'] = $srcSize['h'];
        } elseif ($dstOptions['w'] <= 0) {
            $dstOptions['w'] = $dstOptions['h'] * $srcRatio;
        } elseif ($dstOptions['h'] <= 0) {
            $dstOptions['h'] = $dstOptions['w'] / $srcRatio;
        }

        $dstRatio = $dstOptions['w'] / $dstOptions['h'];
        $dstSize['w'] = (int)\floor($dstOptions['w']);
        $dstSize['h'] = (int)\floor($dstOptions['h']);
        $dstSize['src-box'] = static::calculateSourceBox($srcSize, $srcRatio, $dstSize, $dstRatio);

        return $dstSize;
    }

    /**
     * Calculates source box
     *
     * @param array $srcSize
     * @param float $srcRatio
     * @param array $dstSize
     * @param float $dstRatio
     * @return array
     */
    private static function calculateSourceBox(array $srcSize = [], float $srcRatio = 1, array $dstSize = [],
                                               float $dstRatio = 1): array
    {
        $srcBox = ['x' => 0, 'y' => 0, 'w' => $srcSize['w'], 'h' => $srcSize['h']];
        if ($srcRatio != $dstRatio) {
            $srcBox['w'] = $dstSize['w'];
            $srcBox['h'] = $dstSize['h'];

            while ($srcBox['w'] < $srcSize['w'] && $srcBox['h'] < $srcSize['h']) {
                $srcBox['w'] *= 10;
                $srcBox['h'] *= 10;
            }

            if ($srcBox['w'] > $srcSize['w']) {
                $srcBox['w'] = $srcSize['w'];
                $srcBox['h'] = (int)\floor($srcSize['w'] / $dstRatio);
            }
            if ($srcBox['h'] > $srcSize['h']) {
                $srcBox['h'] = $srcSize['h'];
                $srcBox['w'] = (int)\floor($srcSize['h'] * $dstRatio);
            }

            $srcBox['x'] = 0;
            $srcBox['y'] = (int)\floor(($srcSize['h'] - $srcBox['h']) / 2);

            if ($srcBox['w'] < $srcSize['w']) {
                $srcBox['x'] = (int)\floor(($srcSize['w'] - $srcBox['w']) / 2);
                $srcBox['y'] = 0;
            }
        }
        return $srcBox;
    }

    /**
     * Resizes image
     *
     * @param array $src
     * @param array $dst
     * @param array $options
     */
    private static function resizeImage(array $src = [], array $dst = [], array $options = []): void
    {
        $srcImg = $src['function']($src['file']);
        $dstImg = \imagecreatetruecolor($dst['size']['w'], $dst['size']['h']);

        if (\in_array($src['type'], [IMAGETYPE_GIF, IMAGETYPE_PNG])) {
            $trIdx = \imagecolortransparent($srcImg);

            if ($trIdx > 0) {
                $trData = \imagecolorsforindex($srcImg, $trIdx);
                $background = \imagecolorallocate($dstImg, $trData['red'], $trData['green'], $trData['blue']);
                \imagefill($dstImg, 0, 0, $background);
                \imagecolortransparent($dstImg, $background);
            } elseif ($src['type'] == IMAGETYPE_PNG) {
                \imagealphablending($dstImg, false);
                $background = \imagecolorallocatealpha($dstImg, 0, 0, 0, 127);
                \imagefill($dstImg, 0, 0, $background);
                \imagesavealpha($dstImg, true);
            }
        }

        \imagecopyresampled(
            $dstImg,
            $srcImg,
            0,
            0,
            $dst['size']['src-box']['x'],
            $dst['size']['src-box']['y'],
            $dst['size']['w'],
            $dst['size']['h'],
            $dst['size']['src-box']['w'],
            $dst['size']['src-box']['h']
        );

        if ($options['gs'] === true) {
            \imagefilter($dstImg, IMG_FILTER_GRAYSCALE);
        }

        if (Directory::exists(\dirname($dst['file'])) === false) {
            Directory::create(\dirname($dst['file']));
        }

        $dst['function']($dstImg, $dst['file'], $dst['quality']);
    }

    /**
     * Resizes image
     *
     * @param string $srcFile
     * @param string $dstFile
     * @param array  $options
     * @return bool
     */
    public static function resize(string $srcFile = '', string $dstFile = '', array $options = []): bool
    {
        $options = \array_merge(static::$defaultOptions, $options);
        $src['file'] = $srcFile;
        $dst['file'] = $dstFile;

        if (File::exists($src['file']) === false) {
            throw new RuntimeException('Source file does not exist: '.$src['file']);
        }

        $tmp = @\getimagesize($src['file']);
        if (empty($tmp)) {
            throw new RuntimeException('Invalid source file: '.$src['file']);
        }
        $src['size'] = ['w' => $tmp[0], 'h' => $tmp[1]];
        $src['type'] = $tmp[2];

        \preg_match('/\.([a-z]+)$/i', $dst['file'], $regs);
        $dst['type'] = isset($regs[1]) ? $regs[1] : null;
        if ($dst['type'] === null) {
            throw new RuntimeException('Unrecognized extension of destination file: '.$dst['file']);
        }

        $src['function'] = static::getSourceFunction($src['type']);
        if ($src['function'] === null) {
            throw new RuntimeException('Unsupported type of source file: '.$src['file']);
        }

        $dst['function'] = static::getDestinationFunction($dst['type']);
        if ($dst['function'] === null) {
            throw new RuntimeException('Unsupported type of destination file: '.$dst['file']);
        }

        static::setQuality($options['quality']);
        $dst['quality'] = static::getDestinationQuality($dst['type']);
        $dst['size'] = static::calculateResizeData($src['size'], $options);
        static::resizeImage($src, $dst, $options);

        return File::exists($dst['file']);
    }
}
