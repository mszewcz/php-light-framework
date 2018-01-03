<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query\ReplaceInto;
use PHPUnit\Framework\TestCase;


class ReplaceIntoTest extends TestCase
{

    public function setUp()
    {
    }

    public function testTableException()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');
        new ReplaceInto();
    }

    public function testSetException()
    {
        $this->expectExceptionMessage('$fields has to be an array or a string');

        $q = new ReplaceInto('table1');
        $q->set();
    }
}
