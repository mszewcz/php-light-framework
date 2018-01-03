<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Variables\Variables;
use PHPUnit\Framework\TestCase;


class AbstractWritableCookieTest extends TestCase
{

    /* @var \MS\LightFramework\Variables\Specific\AbstractWritableCookie */
    private $handler;

    public function setUp()
    {
        $vHandler = Variables::getInstance();
        $this->handler = $vHandler->cookie;
    }

    public function tearDown()
    {
        unset($this->handler);
    }

    public function testGetCookiePath()
    {
        $this->assertEquals('/', $this->handler->getPath());
    }

    public function testSetCookiePath()
    {
        $path = $this->handler->getPath();
        $this->assertEquals($path, $this->handler->getPath());
        $this->handler->setPath('/admin/');
        $this->assertEquals('/admin/', $this->handler->getPath());
        $this->handler->setPath($path);
        $this->assertEquals($path, $this->handler->getPath());
    }

    public function testGetCookieDomain()
    {
        $this->assertNotEmpty($this->handler->getDomain());
    }

    public function testSetCookieDomain()
    {
        $domain = $this->handler->getDomain();
        $this->assertEquals($domain, $this->handler->getDomain());
        $this->handler->setDomain('test.foo', false);
        $this->assertEquals('test.foo', $this->handler->getDomain());
        $this->handler->setDomain('test.foo', true);
        $this->assertEquals('.test.foo', $this->handler->getDomain());
        $this->handler->setDomain($domain);
        $this->assertEquals($domain, $this->handler->getDomain());
    }

    public function testGetCookieSecure()
    {
        $this->assertEquals(false, $this->handler->isSecure());
    }

    public function testSetCookieSecure()
    {
        $this->handler->secureFlagOn();
        $this->assertEquals(true, $this->handler->isSecure());
        $this->handler->secureFlagOff();
        $this->assertEquals(false, $this->handler->isSecure());
    }

    public function testGetCookieHttpOnly()
    {
        $this->assertEquals(true, $this->handler->isHttpOnly());
    }

    public function testSetCookieHttpOnly()
    {
        $this->handler->httpOnlyFlagOn();
        $this->assertEquals(true, $this->handler->isHttpOnly());
        $this->handler->httpOnlyFlagOff();
        $this->assertEquals(false, $this->handler->isHttpOnly());
    }
}
