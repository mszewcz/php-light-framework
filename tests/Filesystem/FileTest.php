<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Filesystem\File;
use PHPUnit\Framework\TestCase;


class FileTest extends TestCase
{

    public function testCreate_Exists_IsLink()
    {
        $file = realpath(dirname(__DIR__).'/_samples').'/Filesystem/testFile.txt';
        $this->assertFileNotExists($file);
        $this->assertFalse(File::exists($file));

        File::create($file);
        $this->assertFileExists($file);
        $this->assertTrue(File::exists($file));
        $this->assertFalse(File::isLink($file));

        unlink($file);
    }

    public function testWrite_Read_Append_Remove()
    {
        $file = realpath(dirname(__DIR__).'/_samples').'/Filesystem/testFile.txt';
        $text = "Dummy text to write.\r\n";

        File::write($file, $text);
        $this->assertFileExists($file);

        $data = File::read($file);
        $this->assertEquals($text, $data);

        File::append($file, $text);
        $data = File::read($file);
        $this->assertEquals($text.$text, $data);

        File::remove($file);
        $this->assertFileNotExists($file);

        File::remove($file);
        $this->assertFileNotExists($file);
    }
}
