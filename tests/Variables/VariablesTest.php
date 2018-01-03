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


class VariablesTest extends TestCase
{

    private $vHandler;

    public function setUp()
    {
        $this->vHandler = Variables::getInstance();
    }

    public function tearDown()
    {
        $this->vHandler = null;
        unset($this->vHandler);
    }

    public function testExceptionClone()
    {
        $this->expectExceptionMessage('Clone of MS\LightFramework\\Variables\\Variables is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->vHandler;
    }

    public function testException__get()
    {
        $this->expectExceptionMessage('Unknown handler: xxx, allowed handlers are: cookie, env, files, get, post, server, session, url');
        $this->vHandler->xxx;
    }

    public function testException__set()
    {
        $this->expectExceptionMessage('Setting properties of MS\LightFramework\\Variables\\Variables is not allowed');
        $this->vHandler->get = null;
    }

    public function testException__unset()
    {
        $this->expectExceptionMessage('Unsetting properties of MS\LightFramework\\Variables\\Variables is not allowed');
        unset($this->vHandler->get);
    }

    public function test__get()
    {
        $this->assertInstanceOf('\\MS\\LightFramework\\Variables\\Specific\\Get', $this->vHandler->get);
    }


}
