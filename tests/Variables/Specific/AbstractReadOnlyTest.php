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


$_SESSION['var1'] = base64_encode(serialize(7));
$_SESSION['var2'] = base64_encode(serialize(3.14));
$_SESSION['var3'] = base64_encode(serialize('string'));
$_SESSION['var4'] = base64_encode(serialize(['test' => 1]));
$_SESSION['var5'] = base64_encode(serialize('{"test":2}'));

class AbstractReadOnlyTest extends TestCase
{

    /* @var \MS\LightFramework\Variables\Specific\AbstractReadOnly */
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

    public function testExceptionCast()
    {
        $this->expectExceptionMessage('Invalid variable type specified');
        $this->handler->get('var1', 999);
    }

    public function testDefaultsException()
    {
        $this->expectExceptionMessage('Invalid variable type specified');
        $this->handler->get('some_var', 999);
    }

    public function testDefaults()
    {
        $this->assertSame(0, $this->handler->get('some_var', Variables::TYPE_INT));
        $this->assertSame((float)0, $this->handler->get('some_var', Variables::TYPE_FLOAT));
        $this->assertSame('', $this->handler->get('some_var', Variables::TYPE_STRING));
        $this->assertSame([], $this->handler->get('some_var', Variables::TYPE_ARRAY));
        $this->assertSame('', $this->handler->get('some_var', Variables::TYPE_JSON_DECODED));
    }

    public function testCast()
    {
        $this->assertSame(7, $this->handler->get('var1', Variables::TYPE_INT));
        $this->assertSame(3.14, $this->handler->get('var2', Variables::TYPE_FLOAT));
        $this->assertSame('string', $this->handler->get('var3', Variables::TYPE_STRING));
        $this->assertSame(['test' => 1], $this->handler->get('var4', Variables::TYPE_ARRAY));
        $this->assertSame(['test' => 2], $this->handler->get('var5', Variables::TYPE_JSON_DECODED));
    }
}
