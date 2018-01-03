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


$_ENV['var'] = 'val';

class EnvTest extends TestCase
{
    /* @var \MS\LightFramework\Variables\Specific\AbstractReadOnly */
    private $handler;

    public function setUp()
    {
        $vHandler = Variables::getInstance();
        $this->handler = $vHandler->env;
    }

    public function tearDown()
    {
        unset($this->handler);
    }

    public function testExceptionClone()
    {
        $this->expectExceptionMessage('Clone of MS\LightFramework\\Variables\\Specific\\Env is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->handler;
    }

    public function testExceptionVariableName()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->get();
    }

    public function testGet()
    {
        $this->assertSame('val', $this->handler->get('var', Variables::TYPE_STRING));
    }

    public function testGetDefault()
    {
        $this->assertSame('', $this->handler->get('some_var', Variables::TYPE_STRING));
    }
}
