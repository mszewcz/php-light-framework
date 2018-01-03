<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Log\Backend\Filesystem;
use MS\LightFramework\Filesystem\File;
use PHPUnit\Framework\TestCase;


class FilesystemTest extends TestCase
{
    /* @var \MS\LightFramework\Log\LogInterface */
    private $log;
    private $logFile;

    public function setUp()
    {
        $this->log = new Filesystem('_test.log', 15);
        $this->logFile = $this->log->getLogFilePath();
    }

    public function tearDown()
    {
        @unlink($this->logFile);
    }

    public function testGetLogFilePath()
    {
        $this->assertNotEmpty($this->logFile);
    }

    public function testLogDebug()
    {
        $res = $this->log->logDebug('Debug message', __CLASS__, null);
        $this->assertTrue($res);
        $this->assertFileExists($this->logFile);

        $content = File::read($this->logFile);
        $this->assertRegExp('/[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} \[  DEBUG\] \[FilesystemTest\] Debug message/', $content);
    }

    public function testLogInfo()
    {
        $res = $this->log->logInfo('Info message', __CLASS__, null);
        $this->assertTrue($res);

        $content = File::read($this->logFile);
        $this->assertRegExp('/[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} \[   INFO\] \[FilesystemTest\] Info message/', $content);
    }

    public function testLogWarning()
    {
        $res = $this->log->logWarning('Warning message', __CLASS__, null);
        $this->assertTrue($res);

        $content = File::read($this->logFile);
        $this->assertRegExp('/[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} \[WARNING\] \[FilesystemTest\] Warning message/', $content);
    }

    public function testLogError()
    {
        $res = $this->log->logError('Error message', __CLASS__, null);
        $this->assertTrue($res);

        $content = File::read($this->logFile);
        $this->assertRegExp('/[0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}:[0-9]{2}:[0-9]{2} \[  ERROR\] \[FilesystemTest\] Error message/', $content);
    }
}
