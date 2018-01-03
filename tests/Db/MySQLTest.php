<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL;
use PHPUnit\Framework\TestCase;


class MySQLTest extends TestCase
{

    public function setUp()
    {
    }

    public function testGetInstance()
    {
        $dbClass = MySQL::getInstance('MySQLi');
        $this->assertInstanceOf('\\MS\\LightFramework\\Db\\MySQL\\AbstractMySQL', $dbClass);
        $this->assertInstanceOf('\\MS\\LightFramework\\Db\\MySQL\\Drivers\\MySQLi', $dbClass);
    }

}
