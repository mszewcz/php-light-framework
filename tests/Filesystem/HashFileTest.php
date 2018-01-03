<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Base;
use MS\LightFramework\Filesystem\HashFile;
use PHPUnit\Framework\TestCase;


class HashFileTest extends TestCase
{

    private $hashFileDirectory;
    private $hashFileDirectoryDepth;

    public function setUp()
    {
        $baseClass = Base::getInstance();
        $this->hashFileDirectory = $baseClass->parsePath((string)$baseClass->Filesystem->hashFileDirectory);
        $this->hashFileDirectoryDepth = (int)$baseClass->Filesystem->hashFileDirectoryDepth;
    }

    public function testCreate()
    {
        $hash = HashFile::create();
        $hashDir = $this->hashFileDirectory.implode('/', str_split(substr($hash['name'], 0, $this->hashFileDirectoryDepth), 1));
        $hashDir = rtrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $hashDir), DIRECTORY_SEPARATOR);
        $hashDir .= DIRECTORY_SEPARATOR;

        $this->assertNotEmpty($hash['path']);
        $this->assertNotEmpty($hash['name']);
        $this->assertEquals($hashDir, $hash['path']);
        $this->assertFileNotExists($hash['path'].$hash['name']);
    }

}
