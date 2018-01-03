<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Config\ReaderManager;
use PHPUnit\Framework\TestCase;


class AbstractReaderTest extends TestCase
{

    /* @var \MS\LightFramework\Config\AbstractReader */
    private $reader;
    private $filePath;

    public function setUp()
    {
        $this->reader = ReaderManager::get('json');
        $this->filePath = realpath(dirname(__DIR__).'/_samples/Config/').DIRECTORY_SEPARATOR;
    }

    public function testNoFileName()
    {
        $this->expectExceptionMessage('File name must be specified');
        $this->reader->fromFile('');
    }


    public function testReadFault()
    {
        $filename = $this->filePath.'config2.json';
        $this->expectExceptionMessage('Error reading from "'.$filename.'": file_get_contents('.$filename.'): failed to open stream: No such file or directory');
        $this->reader->fromFile($filename);
    }
}
