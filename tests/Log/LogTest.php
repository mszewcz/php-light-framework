<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Log\Log;
use PHPUnit\Framework\TestCase;


class LogTest extends TestCase
{

    public function setUp()
    {
    }

    public function testFactory()
    {
        $log = Log::factory(__CLASS__);
        $this->assertInstanceOf('\\MS\\LightFramework\\Log\\Backend\\Filesystem', $log);
    }
}
