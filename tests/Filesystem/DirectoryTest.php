<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Filesystem\Directory;
use PHPUnit\Framework\TestCase;


class DirectoryTest extends TestCase
{

    public function testExists()
    {
        $dir = realpath(dirname(__DIR__).'/_samples/');
        $this->assertFileExists($dir);
        $this->assertTrue(Directory::exists($dir));
        $this->assertFileNotExists($dir.'/test/');
        $this->assertFalse(Directory::exists($dir.'/test/'));
    }

    public function testIsLink()
    {
        $dir = realpath(dirname(__DIR__).'/_samples/');
        $this->assertTrue(Directory::exists($dir));
        $this->assertFalse(Directory::isLink($dir));
    }

    public function testRead()
    {
        $dir = realpath(dirname(__DIR__).'/');
        $res = Directory::read($dir);
        $this->assertGreaterThan(0, count($res['subdirs']));
        $this->assertGreaterThan(0, count($res['files']));

        $res = Directory::read($dir, '/_sampl/');
        $this->assertEquals(1, count($res['subdirs']));

        $res = Directory::read($dir, '/bootstrap.php/');
        $this->assertEquals(1, count($res['files']));
    }

    public function testCreate()
    {
        $dir = realpath(dirname(__DIR__).'/_samples/');
        Directory::create($dir);
        $this->assertFileExists($dir);

        $dir = realpath(dirname(__DIR__).'/_samples').'/Filesystem/TestDir';
        Directory::create($dir);
        $this->assertFileExists($dir);
    }

    /**
     * @depends testCreate
     */
    public function testRemove()
    {
        $dir = realpath(dirname(__DIR__).'/_samples').'/Filesystem';
        $this->assertFileExists($dir);

        Directory::remove($dir);
        $this->assertFileNotExists($dir);

        Directory::remove($dir);
        $this->assertFileNotExists($dir);
    }

    /**
     * @depends testRemove
     */
    public function testEmpty()
    {
        $dir = realpath(dirname(__DIR__).'/_samples').'/Filesystem/TestDir';
        Directory::create($dir);
        $this->assertFileExists($dir);

        $dir2 = realpath(dirname(__DIR__).'/_samples').'/Filesystem';
        Directory::emptyDirectory($dir2);
        $this->assertFileExists($dir2);
        $this->assertFileNotExists($dir);

        Directory::remove($dir2);
        $this->assertFileNotExists($dir2);
    }

}
