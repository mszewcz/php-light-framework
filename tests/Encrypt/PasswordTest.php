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

    public function testHash()
    {
        $password = 'testpass';
        $hashed = Password::hash($password);

        $this->assertNotEquals($password, $hashed);
    }

    /**
     * @depends testHash
     */
    public function testCompare()
    {
        $password = 'testpass';
        $stored = Password::hash($password);

        $this->assertTrue(Password::compare($password, $stored));
    }
}
