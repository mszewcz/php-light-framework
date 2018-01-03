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


class ReaderManagerTest extends TestCase
{

    public function testUnsupportedFileType()
    {
        $this->expectExceptionMessage('Unsupported config file type: test');
        ReaderManager::get('test');
    }
}
