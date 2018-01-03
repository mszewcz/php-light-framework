<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Regex;
use PHPUnit\Framework\TestCase;


class RegexTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Regex();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('2010-02-30', ['pattern' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('az09ść', ['pattern' => '/^[[:alnum:]]+$/u']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('22 6630933', ['pattern' => '/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/']));
        $this->assertTrue(in_array(Regex::VALIDATOR_ERROR_REGEX_PATTERN_NOT_MATCH, $this->handler->getErrors()));
    }
}
