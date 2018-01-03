<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\StringLength;
use PHPUnit\Framework\TestCase;


class StringLengthTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new StringLength();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'default', 'length' => 4]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'equals', 'length' => 4]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'greater-than', 'length' => 3]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'greater-than', 'length' => 4, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'lower-than', 'length' => 5]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jaźń', ['type' => 'lower-than', 'length' => 4, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('jaźń', ['type' => 'equals', 'length' => 5]));
        $this->assertTrue(in_array(StringLength::VALIDATOR_ERROR_STRING_LENGTH_NOT_MATCH, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('jaźń', ['type' => 'greater-than', 'length' => 4]));
        $this->assertTrue(in_array(StringLength::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_THAN_MIN, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('jaźń', ['type' => 'greater-than', 'length' => 5, 'inclusive' => true]));
        $this->assertTrue(in_array(StringLength::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_HIGHER_OR_EQUAL_TO_MIN, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('jaźń', ['type' => 'lower-than', 'length' => 4]));
        $this->assertTrue(in_array(StringLength::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_THAN_MAX, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('jaźń', ['type' => 'lower-than', 'length' => 3, 'inclusive' => true]));
        $this->assertTrue(in_array(StringLength::VALIDATOR_ERROR_STRING_LENGTH_MUST_BE_LOWER_OR_EQUAL_TO_MAX, $this->handler->getErrors()));
    }
}
