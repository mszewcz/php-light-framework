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
 * Class Password
 *
 * @package MS\LightFramework\Encryption
 */
final class Password
{
    private static $initialized = false;
    private static $cryptBlowfishSaltPattern;
    private static $cryptSHA512SaltPattern;
    private static $cryptSHA256SaltPattern;
    private static $md5Pattern;
    private static $disableBlowfish = false;
    private static $disableSha512 = false;
    private static $disableSha256 = false;

    /**
     * Sets static class variables
     */
    private static function init()
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$cryptBlowfishSaltPattern = (string)$baseClass->Encryption->Password->cryptBlowfishSaltPattern;
            static::$cryptSHA512SaltPattern = (string)$baseClass->Encryption->Password->cryptSha512SaltPattern;
            static::$cryptSHA256SaltPattern = (string)$baseClass->Encryption->Password->cryptSha256SaltPattern;
            static::$md5Pattern = (string)$baseClass->Encryption->Password->md5Pattern;
        }
    }

    /**
     * Creates salt of given length
     *
     * @param int  $length
     * @param bool $withMcrypt
     * @return string
     */
    public static function createSalt(int $length = 22, bool $withMcrypt = true): string
    {
        if (Mcrypt::isEnabled() && $withMcrypt) {
            $salt = \mcrypt_create_iv($length, Mcrypt::getRandomSource());
        } else {
            $salt = '';
            for ($i = 0; $i < $length; $i++) {
                $salt .= \chr(\rand(32, 255));
            }
        }
        return \substr(\strtr(\base64_encode($salt), '+', '.'), 0, $length);
    }

    /**
     * This method hashes password. Algorithm order: CRYPT_BLOWFISH, CRYPT_SHA512, CRYPT_SHA256, MD5.
     *
     * @param string      $password
     * @param null|string $storedSalt
     * @return array
     */
    public static function hash(string $password = '', ?string $storedSalt = null): array
    {
        static::init();

        if ($storedSalt === null) {
            $storedSalt = static::createSalt();
        }

        if (CRYPT_BLOWFISH == 1 && !static::$disableBlowfish) {
            $salt = \sprintf(static::$cryptBlowfishSaltPattern, $storedSalt);
            $full = \crypt($password, $salt);
            $password = \substr($full, \strlen($salt));
        } elseif (CRYPT_SHA512 == 1 && !static::$disableSha512) {
            $salt = \sprintf(static::$cryptSHA512SaltPattern, $storedSalt);
            $full = \crypt($password, $salt);
            $password = \substr($full, \strrpos($full, '$') + 1);
        } elseif (CRYPT_SHA256 == 1 && !static::$disableSha256) {
            $salt = \sprintf(static::$cryptSHA256SaltPattern, $storedSalt);
            $full = \crypt($password, $salt);
            $password = \substr($full, \strrpos($full, '$') + 1);
        } else {
            $salt = $storedSalt;
            $saltPrepend = \substr($salt, 0, (int)\ceil(\strlen($salt) / 2));
            $saltAppend = \substr($salt, (int)-\floor(\strlen($salt) / 2));
            $full = \md5(\sprintf(static::$md5Pattern, $saltPrepend, $password, $saltAppend));
            $password = $full;
        }
        return ['full' => $full, 'password' => $password, 'salt' => $storedSalt];
    }

    /**
     * This method compares provided password with stored password hash.
     *
     * @param string $password
     * @param string $storedPassword
     * @param string $storedSalt
     * @return bool
     */
    public static function compare(string $password = '', string $storedPassword = '', string $storedSalt = ''): bool
    {
        static::init();

        if (CRYPT_BLOWFISH == 1 && !static::$disableBlowfish) {
            $password = static::hash($password, $storedSalt);
            return $password['password'] === $storedPassword;
        } elseif (CRYPT_SHA512 == 1 && !static::$disableSha512) {
            $password = static::hash($password, $storedSalt);
            return $password['password'] === $storedPassword;
        } elseif (CRYPT_SHA256 == 1 && !static::$disableSha256) {
            $password = static::hash($password, $storedSalt);
            return $password['password'] === $storedPassword;
        } else {
            $saltPrepend = \substr($storedSalt, 0, (int)\ceil(\strlen($storedSalt) / 2));
            $saltAppend = \substr($storedSalt, (int)-\floor(\strlen($storedSalt) / 2));
            return \md5(\sprintf(static::$md5Pattern, $saltPrepend, $password, $saltAppend)) === $storedPassword;
        }
    }

    /**
     * Returns whether cipher is enabled or not
     *
     * @param string $cipher
     * @return bool
     */
    public static function isCipherEnabled(string $cipher = ''): bool
    {
        $enabled = false;
        if (\in_array($cipher, ['Blowfish', 'Sha512', 'Sha256'])) {
            $constant = \constant('CRYPT_'.\strtoupper($cipher));
            $variable = 'disable'.$cipher;
            $enabled = $constant == 1 && static::${$variable} === false;
        }
        return $enabled;
    }

    /**
     * Disables cipher
     *
     * @param string $cipher
     */
    public static function disableCipher(string $cipher = ''): void
    {
        if (\in_array($cipher, ['Blowfish', 'Sha512', 'Sha256'])) {
            $variable = 'disable'.$cipher;
            static::${$variable} = true;
        }
    }

    /**
     * Disables cipher
     *
     * @param string $cipher
     */
    public static function enableCipher(string $cipher = ''): void
    {
        if (\in_array($cipher, ['Blowfish', 'Sha512', 'Sha256'])) {
            $variable = 'disable'.$cipher;
            static::${$variable} = false;
        }
    }
}
