<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\DateTime;
use PHPUnit\Framework\TestCase;


class DateTimeTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new DateTime();
    }

    public function testExceptionOptionsFomat()
    {
        $this->expectExceptionMessage('"format" option has to be provided');
        $this->handler->isValid('2014-02-24');
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid(new \DateTime('now'), []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(time(), []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2014-02-24', ['format' => 'Y-m-d']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('24.02.2012 20:30', ['format' => 'd.m.Y H:i']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(mktime(0, 0, 0, 1, 1, 1970) - 1000, []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(mktime(0, 0, 0, 1, 1, 2035), []));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('2014-02-99', ['format' => 'Y-m-d']));
        $this->assertTrue(in_array(DateTime::VALIDATOR_ERROR_DATETIME_NOT_VALID, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('aaa', ['format' => 'd.m.Y H:i']));
        $this->assertTrue(in_array(DateTime::VALIDATOR_ERROR_DATETIME_NOT_VALID, $this->handler->getErrors()));
    }
}
