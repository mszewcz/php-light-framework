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


/**
 * Class Password
 *
 * @package MS\LightFramework\Encryption
 */
final class Password
{
    /**
     * This method hashes password. Algorithm order: CRYPT_BLOWFISH, CRYPT_SHA512, CRYPT_SHA256, MD5.
     *
     * @param string      $password
     * @return string
     */
    public static function hash(string $password = ''): string
    {
        return \password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * This method compares provided password with stored password hash.
     *
     * @param string $password
     * @param string $storedPassword
     * @return bool
     */
    public static function compare(string $password = '', string $storedPassword = ''): bool
    {
        return password_verify($password, $storedPassword);
    }
}
