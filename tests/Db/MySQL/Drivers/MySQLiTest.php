<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Db\MySQL\Drivers\MySQLi;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class MySQLiTest extends TestCase
{
    /* @var \MS\LightFramework\Db\MySQL\AbstractMySQL */
    private $dbClass;
    private $tmpConfig;
    private $tmpConfigC;
    private $tmpConfigD;

    public function setUp()
    {
        $this->dbClass = MySQLi::getInstance();

        $this->tmpConfig = new MS\LightFramework\Config\Config(['databaseHost'        => 'localhost',
                                                                'databaseUser'        => 'root',
                                                                'databasePass'        => 'correct_pass',
                                                                'databaseName'        => 'test',
                                                                'connectionCharset'   => 'utf-8',
                                                                'connectionCollation' => 'utf8_general_ci']);

        $this->tmpConfigC = new MS\LightFramework\Config\Config(['databaseHost'        => 'localhost',
                                                                 'databaseUser'        => 'root',
                                                                 'databasePass'        => 'incorrect_pass',
                                                                 'databaseName'        => 'test',
                                                                 'connectionCharset'   => 'utf-8',
                                                                 'connectionCollation' => 'utf8_general_ci']);

        $this->tmpConfigD = new MS\LightFramework\Config\Config(['databaseHost'        => 'localhost',
                                                                 'databaseUser'        => 'root',
                                                                 'databasePass'        => 'correct_pass',
                                                                 'databaseName'        => 'xxx',
                                                                 'connectionCharset'   => 'utf-8',
                                                                 'connectionCollation' => 'utf8_general_ci']);
    }

    public function tearDown()
    {
        $this->dbClass->loadConfig($this->tmpConfig);
    }

    public function testClone()
    {
        $this->expectExceptionMessage('Clone of MySQLi is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->dbClass;
    }

    public function testConnectionError()
    {
        $this->expectExceptionMessage('FATAL ERROR (1045): Cannot establish connection to database server.');
        $this->dbClass->loadConfig($this->tmpConfigC);
    }

    public function testDatabaseError()
    {
        $this->expectExceptionMessage('FATAL ERROR (1049): Cannot select database \'xxx\'.');
        $this->dbClass->loadConfig($this->tmpConfigD);
    }

    public function testLoadConfigError()
    {
        $this->expectExceptionMessage('$config must be an instance of MS\LightFramework\Config\AbstractConfig');
        $this->dbClass->loadConfig([]);
    }

    public function testGetConnectionCollation()
    {
        $this->dbClass->loadConfig($this->tmpConfig);
        $this->assertEquals('utf8_general_ci', $this->dbClass->getConnectionCollation());
    }

    public function testNoSqlError()
    {
        $this->dbClass->__set('errorLevel', 'print');
        $this->expectOutputString('FATAL ERROR (0): Query was empty.'.Tags::CRLF.Tags::br().Tags::CRLF);
        $this->dbClass->execute();
        $this->dbClass->__set('errorLevel', 'exception');
    }

    public function testEngineError()
    {
        $this->expectExceptionMessage('FATAL ERROR (1146): Table \'test.nonexistingtable\' doesn\'t exist: SELECT * FROM nonexistingtable');
        $this->dbClass->execute('SELECT * FROM nonexistingtable');
    }

    public function testRowCount()
    {
        $this->dbClass->__set('showQuery', true);
        $this->dbClass->execute('SELECT 2+3 AS res');
        $this->dbClass->__set('showQuery', false);
        $this->assertSame(1, $this->dbClass->rowCount());
    }

    public function testGetQueries()
    {
        $qArray = $this->dbClass->getQueries();
        $this->assertSame(12, count($qArray));
    }

    public function testPrepare()
    {
        $this->assertInstanceOf('MS\LightFramework\Db\MySQL\Query', $this->dbClass->prepare());
    }
}
