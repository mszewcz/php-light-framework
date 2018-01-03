<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query\Insert;
use PHPUnit\Framework\TestCase;


class InsertTest extends TestCase
{

    public function setUp()
    {
    }

    public function testFieldsException()
    {
        $this->expectExceptionMessage('$fields has to be an array');
        new Insert();
    }

    public function testTableException()
    {
        $this->expectExceptionMessage('$table has to be an array or a string');

        $q = new Insert([]);
        $q->into();
    }
}
