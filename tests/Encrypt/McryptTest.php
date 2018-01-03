<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Encryption\Mcrypt;
use PHPUnit\Framework\TestCase;


class EncryptTest extends TestCase
{

    public function testEncrypt()
    {
        $text = 'Text to be encrypted';
        $encrypted = Mcrypt::encrypt($text);
        $this->assertNotEmpty($encrypted);
        $this->assertNotEquals($text, $encrypted);
    }

    public function testGetExtensionName()
    {
        $reflectionMethod = new ReflectionMethod('MS\LightFramework\Encryption\Mcrypt', 'getExtensionName');
        $reflectionMethod->setAccessible(true);
        $this->assertNotEmpty($reflectionMethod->invoke(null));
    }

    public function testDecrypt()
    {
        $text = 'Text to be encrypted';
        $encrypted = Mcrypt::encrypt($text);
        $dectypted = Mcrypt::decrypt($encrypted);
        $this->assertEquals($text, $dectypted);
    }

    public function testIsEnabled()
    {
        $this->assertNotNull(Mcrypt::isEnabled());
    }

    public function testGetRandomSource()
    {
        $this->assertNotNull(Mcrypt::getRandomSource());
    }
}
