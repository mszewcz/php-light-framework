<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query\Select;
use PHPUnit\Framework\TestCase;


class SelectTest extends TestCase
{

    public function setUp()
    {
    }

    public function testFieldsException()
    {
        $this->expectExceptionMessage('$fields has to be an array or a string');
        new Select(9);
    }

    public function testTableException()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');

        $q = new Select();
        $q->from();
    }

    public function testJoinException1()
    {
        $this->expectExceptionMessage('$type has to be left|right|inner|full');

        $q = new Select();
        $q->join();
    }

    public function testJoinException2()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');

        $q = new Select();
        $q->join('left');
    }

    public function testJoinException3()
    {
        $this->expectExceptionMessage('$on has to be an array');

        $q = new Select();
        $q->join('left', 'table1');
    }

    public function testWhereException()
    {
        $this->expectExceptionMessage('$condition has to be an array');

        $q = new Select();
        $q->where();
    }

    public function testGroupByException()
    {
        $this->expectExceptionMessage('$groupBy has to be an array or a string');

        $q = new Select();
        $q->groupby();
    }

    public function testHavingException()
    {
        $this->expectExceptionMessage('$condition has to be an array');

        $q = new Select();
        $q->having();
    }

    public function testOrderByException()
    {
        $this->expectExceptionMessage('$orderBy has to be an array or a string');

        $q = new Select();
        $q->orderby();
    }

    public function testLimitException()
    {
        $this->expectExceptionMessage('$limit have to be a positive integer');

        $q = new Select();
        $q->limit();
    }

    public function testOffsetException()
    {
        $this->expectExceptionMessage('$offset have to be a positive integer or a zero');

        $q = new Select();
        $q->offset(-2);
    }
}
