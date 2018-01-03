<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Encryption\Password;
use PHPUnit\Framework\TestCase;


class PasswordTest extends TestCase
{

    public function testCreateSalt()
    {
        $s1 = Password::createSalt(22, true);
        $s2 = Password::createSalt(22, false);

        $this->assertNotEmpty($s1);
        $this->assertNotEmpty($s2);
        $this->assertNotEquals($s1, $s2);
    }

    /**
     * @depends testCreateSalt
     */
    public function testIsBlowfishEnabled()
    {
        if (CRYPT_BLOWFISH == 0) return true;

        Password::disableCipher('Blowfish');
        $this->assertFalse(Password::isCipherEnabled('Blowfish'));

        Password::enableCipher('Blowfish');
        $this->assertTrue(Password::isCipherEnabled('Blowfish'));

        return true;
    }

    /**
     * @depends testIsBlowfishEnabled
     */
    public function testIsSha512fishEnabled()
    {
        if (CRYPT_SHA512 == 0) return true;

        Password::disableCipher('Sha512');
        $this->assertFalse(Password::isCipherEnabled('Sha512'));

        Password::enableCipher('Sha512');
        $this->assertTrue(Password::isCipherEnabled('Sha512'));

        return true;
    }

    /**
     * @depends testIsSha512fishEnabled
     */
    public function testIsSha256fishEnabled()
    {
        if (CRYPT_SHA256 == 0) return true;

        Password::disableCipher('Sha256');
        $this->assertFalse(Password::isCipherEnabled('Sha256'));

        Password::enableCipher('Sha256');
        $this->assertTrue(Password::isCipherEnabled('Sha256'));

        return true;
    }

    /**
     * @depends testIsSha256fishEnabled
     */
    public function testHash()
    {
        $password = 'testpass';
        $hashedB = Password::hash($password);

        $this->assertArrayHasKey('password', $hashedB);
        $this->assertArrayHasKey('salt', $hashedB);
        $this->assertNotEquals($password, $hashedB['password']);
    }

    /**
     * @depends testHash
     */
    public function testCompare()
    {
        $password = 'testpass';
        $storedB = Password::hash($password);
        Password::disableCipher('Blowfish');
        $storedS5 = Password::hash($password);
        Password::disableCipher('Sha512');
        $storedS2 = Password::hash($password);
        Password::disableCipher('Sha256');
        $storedM = Password::hash($password);

        $this->assertFalse($storedB['password'] === $storedS5['password']);
        $this->assertFalse($storedB['password'] === $storedS2['password']);
        $this->assertFalse($storedB['password'] === $storedM['password']);
        $this->assertFalse($storedS5['password'] === $storedS2['password']);
        $this->assertFalse($storedS5['password'] === $storedM['password']);
        $this->assertFalse($storedS2['password'] === $storedM['password']);

        $this->assertTrue(Password::compare($password, $storedM['password'], $storedM['salt']));
        $this->assertFalse(Password::compare('a'.$password, $storedM['password'], $storedM['salt']));

        Password::enableCipher('Sha256');
        $this->assertTrue(Password::compare($password, $storedS2['password'], $storedS2['salt']));
        $this->assertFalse(Password::compare('a'.$password, $storedS2['password'], $storedS2['salt']));

        Password::enableCipher('Sha512');
        $this->assertTrue(Password::compare($password, $storedS5['password'], $storedS5['salt']));
        $this->assertFalse(Password::compare('a'.$password, $storedS5['password'], $storedS5['salt']));

        Password::enableCipher('Blowfish');
        $this->assertTrue(Password::compare($password, $storedB['password'], $storedB['salt']));
        $this->assertFalse(Password::compare('a'.$password, $storedB['password'], $storedB['salt']));
    }

}
