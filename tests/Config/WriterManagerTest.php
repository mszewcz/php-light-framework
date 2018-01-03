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


class WriterManagerTest extends TestCase
{

    public function testUnsupportedFileType()
    {
        $this->expectExceptionMessage('Unsupported config file type: test');
        WriterManager::get('test');
    }
}
