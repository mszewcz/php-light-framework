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
use MS\LightFramework\Filesystem\Directory;
use PHPUnit\Framework\TestCase;


class FactoryTest extends TestCase
{

    private $config;
    private $filePath;

    public function setUp()
    {
        $this->config = ['bool'   => true,
                         'string' => 'val',
                         'space1' => ['str'  => 'value1',
                                      'int'  => 1,
                                      'flt'  => 2.44,
                                      'blnT' => true,
                                      'blnF' => false],
                         'space2' => [0 => 'nk1',
                                      1 => 'nk2'],
                         'space3' => ['subspace'  => ['subkey1' => 'sub;val1',
                                                      'subkey2' => 'subval2'],
                                      'subspace2' => ['subkey1' => 'subval1',
                                                      'subkey2' => 'subval2']],
                         'space4' => ['html'    => '<b>val</b>',
                                      'utf'     => 'łąśćź',
                                      'special' => '&\'"']];

        $this->filePath = realpath(dirname(__DIR__).'/_samples/Config/').DIRECTORY_SEPARATOR;
        Directory::create($this->filePath);
    }

    public function testReaderMissingExtension()
    {
        $filename = $this->filePath.'config';
        $this->expectExceptionMessage('Config file name "config" is missing an extension and cannot be auto-detected');
        Factory::read($filename);
    }

    public function testReaderInvalidExtension()
    {
        $filename = $this->filePath.'config.test';
        $this->expectExceptionMessage('Unsupported config file type. Supported extension types are: ini,json,xml,yml');
        Factory::read($filename);
    }

    public function testReaderFileNotExists()
    {
        $filename = $this->filePath.'sample.json';
        $this->expectExceptionMessage('Config file does not exist');
        Factory::read($filename);
    }

    public function testWriterMissingExtension()
    {
        $filename = $this->filePath.'config';
        $this->expectExceptionMessage('Config file name "config" is missing an extension and cannot be auto-detected');
        Factory::write($filename, $this->config);
    }

    public function testWriterInvalidExtension()
    {
        $filename = $this->filePath.'config.test';
        $this->expectExceptionMessage('Unsupported config file type. Supported extension types are: ini,json,xml,yml');
        Factory::write($filename, $this->config);
    }

    public function testWriterIni()
    {
        if (file_exists($this->filePath.'config.ini')) unlink($this->filePath.'config.ini');
        Factory::write($this->filePath.'config.ini', $this->config);
        $this->assertFileExists($this->filePath.'config.ini');
        $this->assertTrue(Factory::write($this->filePath.'config.ini', $this->config));
    }

    public function testWriterJson()
    {
        if (file_exists($this->filePath.'config.json')) unlink($this->filePath.'config.json');
        $this->assertTrue(Factory::write($this->filePath.'config.json', $this->config));
        $this->assertFileExists($this->filePath.'config.json');
    }

    public function testWriterXml()
    {
        if (file_exists($this->filePath.'config.xml')) unlink($this->filePath.'config.xml');
        $this->assertTrue(Factory::write($this->filePath.'config.xml', $this->config));
        $this->assertFileExists($this->filePath.'config.xml');
    }

    public function testWriterYaml()
    {
        if (extension_loaded('php_yaml')) {
            if (file_exists($this->filePath.'config.yml')) unlink($this->filePath.'config.yml');
            $this->assertTrue(Factory::write($this->filePath.'config.yml', new Config($this->config)));
            $this->assertFileExists($this->filePath.'config.yml');
        }
        $this->assertTrue(true);
    }

    /**
     * @depends testWriterIni
     */
    public function testReaderIni()
    {
        $this->assertNotEmpty(Factory::read($this->filePath.'config.ini'));
        $this->assertSame($this->config, Factory::read($this->filePath.'config.ini', true));
    }

    /**
     * @depends testWriterJson
     */
    public function testReaderJson()
    {
        $this->assertNotEmpty(Factory::read($this->filePath.'config.json'));
        $this->assertSame($this->config, Factory::read($this->filePath.'config.json', true));
    }

    /**
     * @depends testWriterXml
     */
    public function testReaderXml()
    {
        $this->assertSame($this->config, Factory::read($this->filePath.'config.xml', true));
    }

    /**
     * @depends testWriterYaml
     */
    public function testReaderYaml()
    {
        if (extension_loaded('php_yaml')) {
            $this->assertSame($this->config, Factory::read($this->filePath.'config.yaml', true));
        }
        $this->assertTrue(true);
    }
}
