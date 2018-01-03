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


$_SESSION['varX'] = base64_encode(serialize('valX'));

class SessionTest extends TestCase
{

    /* @var \MS\LightFramework\Variables\Specific\AbstractWritableSession */
    private $handler;

    public function setUp()
    {
        $vHandler = Variables::getInstance();
        $this->handler = $vHandler->session;
    }

    public function tearDown()
    {
        unset($this->handler);
    }

    public function testExceptionClone()
    {
        $this->expectExceptionMessage('Clone of MS\LightFramework\\Variables\\Specific\\Session is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->handler;
    }

    public function testExceptionVariableNameGet()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->get();
    }

    public function testExceptionMfregGet()
    {
        $this->expectExceptionMessage('_MFREG_ variable name is not allowed');
        $this->handler->get('_MFREG_');
    }

    public function testExceptionVariableNameSet()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->set();
    }

    public function testExceptionMfregSet()
    {
        $this->expectExceptionMessage('_MFREG_ variable name is not allowed');
        /** @noinspection PhpParamsInspection */
        $this->handler->set('_MFREG_');
    }

    public function testExceptionVariableNameClear()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->clear();
    }

    public function testExceptionMfregClear()
    {
        $this->expectExceptionMessage('_MFREG_ variable name is not allowed');
        $this->handler->clear('_MFREG_');
    }

    public function testGet()
    {
        $this->assertSame('valX', $this->handler->get('varX', Variables::TYPE_STRING));
    }

    public function testGetDefault()
    {
        $this->assertSame('', $this->handler->get('some_var', Variables::TYPE_STRING));
    }

    public function testSetClear()
    {
        $this->assertSame('', $this->handler->get('varY', Variables::TYPE_STRING));
        $this->handler->set('varY', 'valY');
        $this->assertSame('valY', $this->handler->get('varY', Variables::TYPE_STRING));
        $this->handler->clear('varY');
        $this->assertSame('', $this->handler->get('varY', Variables::TYPE_STRING));
    }

}
