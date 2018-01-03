<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query\Update;
use PHPUnit\Framework\TestCase;


class UpdateTest extends TestCase
{

    public function setUp()
    {
    }

    public function testTableException()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');
        new Update();
    }

    public function testSetException()
    {
        $this->expectExceptionMessage('$fields has to be an array or a string');

        $q = new Update('table1');
        $q->set();
    }

    public function testWhereException()
    {
        $this->expectExceptionMessage('$condition has to be an array');

        $q = new Update('table1');
        $q->where();
    }

    public function testOrderByException()
    {
        $this->expectExceptionMessage('$orderBy has to be an array or a string');

        $q = new Update('table1');
        $q->orderby();
    }

    public function testLimitException()
    {
        $this->expectExceptionMessage('$limit have to be a positive integer');

        $q = new Update('table1');
        $q->limit();
    }
}
