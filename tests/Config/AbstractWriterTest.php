<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Config\WriterManager;
use PHPUnit\Framework\TestCase;


class AbstractWriterTest extends TestCase
{

    /* @var \MS\LightFramework\Config\AbstractWriter */
    private $writer;
    private $filePath;

    public function setUp()
    {
        $this->writer = WriterManager::get('json');
        $this->filePath = realpath(dirname(__DIR__).'/_samples/Config/').DIRECTORY_SEPARATOR;
    }

    public function testNoFileName()
    {
        $this->expectExceptionMessage('File name must be specified');
        $this->writer->toFile('', []);
    }

    public function testInvalidConfigArgument()
    {
        $this->expectExceptionMessage('Config data must be either array or \Traversable');
        $filename = $this->filePath.'config.json';
        $this->writer->toFile($filename, '');
    }

    public function testWriteFault()
    {
        $filename = $this->filePath;
        $this->expectExceptionMessage('Error writing to "'.$this->filePath.'": file_put_contents('.$this->filePath.'): failed to open stream: No such file or directory');
        $this->writer->toFile($filename, []);
    }
}
