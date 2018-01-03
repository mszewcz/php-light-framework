<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Config\Config;
use MS\LightFramework\Config\Factory;
use PHPUnit\Framework\TestCase;


class ConfigTest extends TestCase
{

    /**
     * @var MS\LightFramework\Config\Config
     */
    private $config;

    public function setUp()
    {
        $filename = realpath(dirname(__DIR__).'/_samples/Config/').DIRECTORY_SEPARATOR.'config.json';
        $this->config = Factory::read($filename);
    }

    public function testClone()
    {
        $cloned = clone $this->config;
        $this->assertEquals($this->config, $cloned);
    }

    public function testGet()
    {
        $this->assertSame(2.44, $this->config->space1->flt);
    }

    public function testIsReadOnly()
    {
        $this->assertTrue($this->config->isReadOnly());
    }

    /**
     * @depends testIsReadOnly
     */
    public function testUnmarkReadOnly()
    {
        $this->config->unmarkReadOnly();
        $this->assertFalse($this->config->isReadOnly());
    }

    /**
     * @depends testUnmarkReadOnly
     */
    public function testMarkReadOnly()
    {
        $this->config->markReadOnly();
        $this->assertTrue($this->config->isReadOnly());
    }

    public function testSetException()
    {
        $this->expectExceptionMessage('Config is read only');
        $this->config->testKey = 0;
    }

    public function testSet()
    {
        $this->config->unmarkReadOnly();
        $this->config->testKey = 12;
        $this->assertTrue(isset($this->config->testKey));
        $this->assertSame(12, $this->config->testKey);

        $arr = ['xxx' => 'xxx', 'yyy' => 'yyy'];
        $this->config->testKey = $arr;
        $this->assertSame('xxx', $this->config->testKey->xxx);

        $this->config->markReadOnly();
    }

    public function testIsset()
    {
        $this->assertFalse(isset($this->config->dummyKey));
        $this->assertTrue(isset($this->config->space3->subspace->subkey2));
    }

    public function testUnsetException()
    {
        $this->expectExceptionMessage('Config is read only');
        unset($this->config->space3);
    }

    public function testUnset()
    {
        $this->config->unmarkReadOnly();
        $this->config->dummyKey = 'sss';
        $this->assertTrue(isset($this->config->dummyKey));
        unset($this->config->dummyKey);
        $this->assertFalse(isset($this->config->dummyKey));
    }

    public function testOffsetExists()
    {
        $this->assertFalse(isset($this->config['dummyKey']));
        $this->assertTrue(isset($this->config['space3']['subspace']['subkey2']));
    }

    public function testOffsetGet()
    {
        $this->assertSame(2.44, $this->config['space1']['flt']);
    }

    public function testOffsetSet()
    {
        $this->config->unmarkReadOnly();
        $this->config['dummyKey'] = 12;
        $this->assertSame(12, $this->config['dummyKey']);
        $this->config['arr'] = [];
        $this->config['arr'][] = 'one';
        $this->config['arr'][] = 'two';
        $this->assertSame('two', $this->config['arr'][1]);
    }

    public function testOffsetUnset()
    {
        $this->config->unmarkReadOnly();
        $this->config['dummyKey'] = 12;
        $this->assertTrue(isset($this->config['dummyKey']));
        unset($this->config['dummyKey']);
        $this->assertFalse(isset($this->config['dummyKey']));
    }

    public function testIterator()
    {
        $this->config->unmarkReadOnly();
        $this->config->rewind();
        $this->assertTrue($this->config->valid());
        $this->assertSame('bool', $this->config->key());
        $this->assertSame(true, $this->config->current());
        $this->config->next();
        $this->assertSame('string', $this->config->key());
        $this->assertSame('val', $this->config->current());
        $this->config->rewind();
        $this->assertSame('bool', $this->config->key());
        $this->assertSame(true, $this->config->current());
        unset($this->config->bool);
        $this->config->next();
        $this->assertSame('string', $this->config->key());
    }

    public function testToArray()
    {
        $array = ['a' => ['b' => ['c' => 'd']]];
        $config = new Config($array);
        $this->assertSame($array, $config->toArray());
    }
}
