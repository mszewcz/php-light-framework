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


$_COOKIE['var'] = 'val';

class CookieTest extends TestCase
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

    public function testExceptionClone()
    {
        $this->expectExceptionMessage('Clone of MS\LightFramework\\Variables\\Specific\\Cookie is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->handler;
    }

    public function testExceptionVariableNameGet()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->get();
    }

    public function testExceptionVariableNameSet()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->set();
    }

    public function testExceptionVariableNameClear()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->clear();
    }

    public function testGet()
    {
        $this->assertSame('val', $this->handler->get('var', Variables::TYPE_STRING));
    }

    public function testSetClear()
    {
        $this->assertSame('', $this->handler->get('varY', Variables::TYPE_STRING));
        $this->handler->set('varY', 'valY', ['y' => 0, 'm' => 0, 'd' => 0, 'h' => 0, 'i' => 0, 's' => 10]);
        $this->assertSame('valY', $this->handler->get('varY', Variables::TYPE_STRING));
        $this->handler->clear('varY');
        $this->assertSame('', $this->handler->get('varY', Variables::TYPE_STRING));
    }

    public function testGetDefault()
    {
        $this->assertSame('', $this->handler->get('some_var', Variables::TYPE_STRING));
    }
}
