<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Session\Handler;
use MS\LightFramework\Variables\Variables;
use PHPUnit\Framework\TestCase;


class HandlerTest extends TestCase
{

    public function setUp()
    {
        $this->vH = Variables::getInstance();
    }

    public function testRegister()
    {
        $this->assertTrue(Handler::register());
        session_start();
    }

    public function testExceptionUnsupportedSessionBackend()
    {
        $reflection = new ReflectionProperty('\\MS\\LightFramework\\Session\\Handler', 'backend');
        $reflection->setAccessible(true);
        $reflection->setValue('filesystemm');

        $this->expectExceptionMessage('Unsupported session backend: filesystemm');
        Handler::register();
    }

    public function testSetIntegrityToken()
    {
        $this->assertTrue(Handler::setIntegrityToken());
    }

    public function testCheckIntegrity()
    {
        $this->assertTrue(Handler::checkIntegrity());
    }

    public function testSetRevalidationTime()
    {
        $this->assertTrue(Handler::setRevalidationTime());
    }

    public function testDoesNeedRevalidation()
    {
        $this->assertFalse(Handler::doesNeedRevalidation());
    }
}
