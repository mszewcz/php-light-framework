<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Filesystem\AbstractFilesystem;
use PHPUnit\Framework\TestCase;


class AbstractFilesystemTest extends TestCase
{

    public function testDirectoryMode()
    {
        AbstractFilesystem::setNewDirectoryMode('660');
        $this->assertSame('660', AbstractFilesystem::getNewDirectoryMode());
        AbstractFilesystem::setNewDirectoryMode('740');
        $this->assertSame('740', AbstractFilesystem::getNewDirectoryMode());
    }

    public function testFileMode()
    {
        AbstractFilesystem::setNewFileMode('660');
        $this->assertSame('660', AbstractFilesystem::getNewFileMode());
        AbstractFilesystem::setNewFileMode('740');
        $this->assertSame('740', AbstractFilesystem::getNewFileMode());
    }

    public function testSymbolicLinkMode()
    {
        AbstractFilesystem::setNewSymbolicLinkMode('660');
        $this->assertSame('660', AbstractFilesystem::getNewSymbolicLinkMode());
        AbstractFilesystem::setNewSymbolicLinkMode('740');
        $this->assertSame('740', AbstractFilesystem::getNewSymbolicLinkMode());
    }

    public function testWriteLocks()
    {
        AbstractFilesystem::turnOffWriteLocks();
        $this->assertSame(false, AbstractFilesystem::areWriteLocksEnabled());
        AbstractFilesystem::turnOnWriteLocks();
        $this->assertSame(true, AbstractFilesystem::areWriteLocksEnabled());
    }

}
