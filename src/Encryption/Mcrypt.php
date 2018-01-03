<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Encryption;

use MS\LightFramework\Base;


/**
 * Class Mcrypt
 *
 * @package MS\LightFramework\Encryption
 */
final class Mcrypt
{
    private static $enabled = null;
    private static $cipher;
    private static $mode;
    private static $randomSource;
    private static $key;

    /**
     * Sets static class variables
     */
    private static function init(): void
    {
        if (static::$enabled === null) {
            $baseClass = Base::getInstance();
            $hashFilePath = $baseClass->parsePath((string)$baseClass->Encryption->Mcrypt->hashFile);

            static::$enabled = static::checkExtension();
            static::$cipher = static::getConstant($baseClass->Encryption->Mcrypt->cipher, 'blowfish');
            static::$mode = static::getConstant($baseClass->Encryption->Mcrypt->mode, 'cbc');
            static::$randomSource = static::getConstant($baseClass->Encryption->Mcrypt->randomSource, 1);

            $keySize = \mcrypt_get_key_size(static::$cipher, static::$mode);
            static::$key = \substr(\pack('H*', \file_get_contents($hashFilePath)), 0, $keySize);
        }
    }

    /**
     * Returns mcrypt extension name
     *
     * @return string
     */
    private static function getExtensionName(): string
    {
        return \strtoupper(\substr(PHP_OS, 0, 3)) === 'WIN' ? 'php_mcrypt.dll' : 'mcrypt.so';
    }

    /**
     * Checks if mcrypt extension is loaded
     *
     * @return bool
     */
    private static function checkExtension(): bool
    {
        return \extension_loaded('mcrypt') || \dl(static::getExtensionName()) ? true : false;
    }

    /**
     * Returns PHP constant or default value if not defined
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    private static function getConstant($name = '', $default = '')
    {
        return \defined($name) ? \constant($name) : $default;
    }

    /**
     * Encrypts data and returns base64 encoded string containing encryption initialization vector and encrypted text.
     *
     * @param string $plainText
     * @return string
     */
    public static function encrypt($plainText = ''): string
    {
        static::init();

        $encrypted = $plainText;
        if (static::$enabled === true) {
            $encryptionIV = \mcrypt_create_iv(
                \mcrypt_get_iv_size(static::$cipher, static::$mode),
                static::$randomSource
            );
            $encrypted = \base64_encode(
                $encryptionIV.
                \mcrypt_encrypt(static::$cipher, static::$key, \serialize($plainText), static::$mode, $encryptionIV)
            );
        }
        return $encrypted;
    }

    /**
     * Extracts encryption initialization vector from base64 encoded string and returns decrypted text.
     *
     * @param string $encryptedText
     * @return string
     */
    public static function decrypt($encryptedText = ''): string
    {
        static::init();

        $decrypted = $encryptedText;
        if (static::$enabled === true && $decrypted === \base64_encode(\base64_decode($decrypted))) {
            $ivSize = \mcrypt_get_iv_size(static::$cipher, static::$mode);
            $encryptedText = \base64_decode($encryptedText, true);
            $encryptionIV = \substr($encryptedText, 0, $ivSize);
            $encryptedText = \substr($encryptedText, $ivSize);

            if (\strlen($encryptionIV) == $ivSize) {
                $dec = @\mcrypt_decrypt(static::$cipher, static::$key, $encryptedText, static::$mode, $encryptionIV);
                $uns = @\unserialize($dec);
                if ($uns !== false || $dec === \serialize(false)) {
                    $decrypted = $uns;
                }
            }
        }
        return $decrypted;
    }

    /**
     * Returns whether mcrypt encryption is enabled or not
     *
     * @return bool|null
     */
    public static function isEnabled(): ?bool
    {
        static::init();
        return static::$enabled;
    }

    /**
     * Returns random source for initialization vector
     *
     * @return int
     */
    public static function getRandomSource(): int
    {
        static::init();
        return static::$randomSource;
    }
}
