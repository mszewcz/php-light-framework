<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Query;
use PHPUnit\Framework\TestCase;


class QueryTest extends TestCase
{
    private $whereArray = [];

    public function setUp()
    {
        $this->whereArray[]['t1.name'] = ['$ne' => 'xxx'];
        $this->whereArray[]['$or'] = ['t1.id' => ['$nin' => [2, 'aa', 4], '$gte' => 1]];
        $this->whereArray[]['t1.id'] = ['$in' => [3, 8]];
        $this->whereArray[]['t1.name'] = ['$like' => 'ccc%'];
        $this->whereArray[]['t2.name'] = 'xxx';
        $this->whereArray[]['UNIX_TIMESTAMP(t1.ts)'] = 'UNIX_TIMESTAMP("2010-12-12")';
    }

    public function testSelect()
    {
        $q = new Query();
        $q->select()
            ->from(['table1' => 't1'])
            ->join('left', ['table2' => 't2'], ['t2.id' => 't1.id'])
            ->join('left', ['table3' => 't3'], ['t3.id' => 't1.id'])
            ->where($this->whereArray)
            ->groupby(['t1.id'])
            ->having($this->whereArray)
            ->orderby(['t1.name' => 1, 't2.name' => -1])
            ->limit(10)
            ->offset(20);

        $expected = 'SELECT *';
        $expected .= ' FROM table1 t1';
        $expected .= ' LEFT JOIN table2 t2 ON t2.id=t1.id';
        $expected .= ' LEFT JOIN table3 t3 ON t3.id=t1.id';
        $expected .= ' WHERE t1.name!="xxx" AND (t1.id NOT IN (2,"aa",4) OR t1.id>=1) AND t1.id IN (3,8) AND t1.name LIKE "ccc%" AND t2.name="xxx" AND UNIX_TIMESTAMP(t1.ts)=UNIX_TIMESTAMP("2010-12-12")';
        $expected .= ' GROUP BY t1.id';
        $expected .= ' HAVING t1.name!="xxx" AND (t1.id NOT IN (2,"aa",4) OR t1.id>=1) AND t1.id IN (3,8) AND t1.name LIKE "ccc%" AND t2.name="xxx" AND UNIX_TIMESTAMP(t1.ts)=UNIX_TIMESTAMP("2010-12-12")';
        $expected .= ' ORDER BY t1.name ASC, t2.name DESC';
        $expected .= ' LIMIT 10';
        $expected .= ' OFFSET 20';

        $this->assertEquals($expected, $q->___build());
    }

    public function testInsert()
    {
        $q = new Query();
        $q->insert(['f1' => 3, 'f2' => 'aaa', 'f3' => [1, 2, 3], 'f4' => 'table1.f3+1', 'f5' => 'now()'])
            ->into('table1');

        $expected = 'INSERT INTO table1 (f1,f2,f3,f4,f5)';
        $expected .= ' VALUES (3,"aaa","a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}",table1.f3+1,now())';

        $this->assertEquals($expected, $q->___build());
    }

    public function testUpdate()
    {
        $q = new Query();
        $q->update('table1')
            ->set(['f1' => 3, 'f2' => 'aaa', 'f3' => [1, 2, 3], 'f4' => 'table1.f3+1', 'f5' => 'now()'])
            ->where($this->whereArray)
            ->orderby(['t1.name' => 1, 't2.name' => -1])
            ->limit(10);

        $expected = 'UPDATE table1';
        $expected .= ' SET f1=3, f2="aaa", f3="a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}", f4=table1.f3+1, f5=now()';
        $expected .= ' WHERE t1.name!="xxx" AND (t1.id NOT IN (2,"aa",4) OR t1.id>=1) AND t1.id IN (3,8) AND t1.name LIKE "ccc%" AND t2.name="xxx" AND UNIX_TIMESTAMP(t1.ts)=UNIX_TIMESTAMP("2010-12-12")';
        $expected .= ' ORDER BY t1.name ASC, t2.name DESC';
        $expected .= ' LIMIT 10';

        $this->assertEquals($expected, $q->___build());
    }

    public function testReplaceInto()
    {
        $q = new Query();
        $q->replaceInto('table1')
            ->set(['f1' => 3, 'f2' => 'aaa', 'f3' => [1, 2, 3], 'f4' => 'table1.f3+1', 'f5' => 'now()']);

        $expected = 'REPLACE INTO table1';
        $expected .= ' SET f1=3, f2="aaa", f3="a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}", f4=table1.f3+1, f5=now()';

        $this->assertEquals($expected, $q->___build());
    }

    public function testDelete()
    {
        $q = new Query();
        $q->delete()->from('table1')
            ->where($this->whereArray)
            ->limit(10);

        $expected = 'DELETE FROM table1';
        $expected .= ' WHERE t1.name!="xxx" AND (t1.id NOT IN (2,"aa",4) OR t1.id>=1) AND t1.id IN (3,8) AND t1.name LIKE "ccc%" AND t2.name="xxx" AND UNIX_TIMESTAMP(t1.ts)=UNIX_TIMESTAMP("2010-12-12")';
        $expected .= ' LIMIT 10';

        $this->assertEquals($expected, $q->___build());
    }
}
