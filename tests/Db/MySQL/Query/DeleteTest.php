<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query\Delete;
use PHPUnit\Framework\TestCase;


class DeleteTest extends TestCase
{

    public function setUp()
    {
    }

    public function testTableException()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');

        $q = new Delete();
        $q->from();
    }

    public function testWhereException()
    {
        $this->expectExceptionMessage('$condition has to be an array');

        $q = new Delete();
        $q->where();
    }

    public function testLimitException()
    {
        $this->expectExceptionMessage('$limit have to be a positive integer');

        $q = new Delete();
        $q->limit();
    }
}
