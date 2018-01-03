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
use MS\LightFramework\Filesystem\Directory;
use MS\LightFramework\Image\Resizer;
use PHPUnit\Framework\TestCase;


class ResizerTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionSourceFileNotFound()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize5.jpg';
        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize5__w300.jpg';

        $this->expectExceptionMessage('Source file does not exist: '.$srcImage);
        Resizer::resize($srcImage, $dstImage, ['w' => 300]);
    }

    public function testExceptionInvalidSourceFile()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Exception1.txt';
        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Exception1__w300.jpg';

        $this->expectExceptionMessage('Invalid source file: '.$srcImage);
        Resizer::resize($srcImage, $dstImage, ['w' => 300]);
    }

    public function testExceptionUnrecognizedExtenionOfDestinationFile()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize1.jpg';
        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize1__w300';

        $this->expectExceptionMessage('Unrecognized extension of destination file: '.$dstImage);
        Resizer::resize($srcImage, $dstImage, ['w' => 300]);
    }

    public function testExceptionUnsupportedTypeOfSourceFile()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Exception2.bmp';
        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Exception2__w300.bmp';

        $this->expectExceptionMessage('Unsupported type of source file: '.$srcImage);
        Resizer::resize($srcImage, $dstImage, ['w' => 300]);
    }

    public function testExceptionUnsupportedTypeOfDestinationFile()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize1.jpg';
        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize8__w300.bmp';

        $this->expectExceptionMessage('Unsupported type of destination file: '.$dstImage);
        Resizer::resize($srcImage, $dstImage, ['w' => 300]);
    }

    public function testResizeJPGHorizontal()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize1.jpg';

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize1__w300.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(187, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize1__h200.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['h' => 200]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(320, $size[0]);
        $this->assertEquals(200, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize1__w300_h300.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300, 'h' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(300, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize1__gs1.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['gs' => true]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(1920, $size[0]);
        $this->assertEquals(1200, $size[1]);
        File::remove($dstImage);
        Directory::remove(dirname($dstImage));
    }

    public function testResizeJPGVertical()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize2.jpg';

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize2__w300.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(400, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize2__h500.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['h' => 500]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(375, $size[0]);
        $this->assertEquals(500, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize2__w300_h500.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300, 'h' => 500]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(500, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize2__w500_h300.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 500, 'h' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(500, $size[0]);
        $this->assertEquals(300, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize2__gs1.jpg';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['gs' => true]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(768, $size[0]);
        $this->assertEquals(1024, $size[1]);
        File::remove($dstImage);
        Directory::remove(dirname($dstImage));
    }

    public function testResizePNG()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize3.png';

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize3__w300.png';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(85, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize3__h100.png';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['h' => 100]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(350, $size[0]);
        $this->assertEquals(100, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize3__w600.png';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 600]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(600, $size[0]);
        $this->assertEquals(171, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize3__gs1.png';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['gs' => true]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(700, $size[0]);
        $this->assertEquals(200, $size[1]);
        File::remove($dstImage);
        Directory::remove(dirname($dstImage));
    }

    public function testResizeGIF()
    {
        $srcImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resize4.gif';

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize4__w300.gif';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 300]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(300, $size[0]);
        $this->assertEquals(64, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize4__h100.gif';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['h' => 100]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(465, $size[0]);
        $this->assertEquals(100, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize4__w1000.gif';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['w' => 1000]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(930, $size[0]);
        $this->assertEquals(199, $size[1]);
        File::remove($dstImage);

        $dstImage = realpath(dirname(__DIR__).'/_samples').'/Image/Resized/Resize4__gs1.gif';
        $this->assertTrue(Resizer::resize($srcImage, $dstImage, ['gs' => true]));
        $size = @getimagesize($dstImage);
        $this->assertTrue(count($size) > 0);
        $this->assertEquals(930, $size[0]);
        $this->assertEquals(200, $size[1]);
        File::remove($dstImage);
        Directory::remove(dirname($dstImage));
    }

}
