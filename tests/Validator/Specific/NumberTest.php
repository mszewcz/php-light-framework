<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Number;
use PHPUnit\Framework\TestCase;


class NumberTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Number();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid(1, ['type' => 'integer']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(1.2, ['type' => 'integer']));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('', ['type' => 'integer']));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(false, ['type' => 'integer']));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE, $this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid(2, ['type' => 'integer', 'min' => 2, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2, ['type' => 'integer', 'min' => 2, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN, $this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(2, ['type' => 'integer', 'max' => 2, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2, ['type' => 'integer', 'max' => 2, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX, $this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid(-2, ['type' => 'integer', 'min' => -2, 'max' => 2, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(2, ['type' => 'integer', 'min' => -2, 'max' => 2, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(-2, ['type' => 'integer', 'min' => -2, 'max' => 2, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2, ['type' => 'integer', 'min' => -2, 'max' => 2, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX, $this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid(3.14, ['type' => 'float']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(1, ['type' => 'float']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('', ['type' => 'float']));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(true, ['type' => 'float']));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_INCORRECT_TYPE, $this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid(2.01, ['type' => 'float', 'min' => 2.01, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2.01, ['type' => 'float', 'min' => 2.01, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN, $this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(2.01, ['type' => 'float', 'max' => 2.01, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2.01, ['type' => 'float', 'max' => 2.01, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX, $this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid(-2.01, ['type' => 'float', 'min' => -2.01, 'max' => 2.01, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid(2.01, ['type' => 'float', 'min' => -2.01, 'max' => 2.01, 'inclusive' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(-2.01, ['type' => 'float', 'min' => -2.01, 'max' => 2.01, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_THAN_MIN, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2.01, ['type' => 'float', 'min' => -2.01, 'max' => 2.01, 'inclusive' => false]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_THAN_MAX, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid(-2.02, ['type' => 'any', 'min' => -2.01, 'max' => 2.01, 'inclusive' => true]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_OR_EQUAL_TO_MIN, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid(2.02, ['type' => 'any', 'min' => -2.01, 'max' => 2.01, 'inclusive' => true]));
        $this->assertTrue(in_array(Number::VALIDATOR_ERROR_NUMBER_MUST_BE_LOWER_OR_EQUAL_TO_MAX, $this->handler->getErrors()));
    }
}
