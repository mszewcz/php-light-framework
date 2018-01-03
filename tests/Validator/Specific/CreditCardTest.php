<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\CreditCard;
use PHPUnit\Framework\TestCase;


class CreditCardTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new CreditCard();
    }

    public function testExceptionUnsupportedCardType()
    {
        $this->expectExceptionMessage('Unsopported credit card type: unknown');
        $this->handler->isValid(1, ['type' => 'unknown']);
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('3782 8224 6310 005', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('3642 9034 0704 73', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6011 1111 1111 1117', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('3566 1111 1111 1113', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6304 9850 2809 0561 515', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6759 4111 0000 0008', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6759 5600 4500 5727 054', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('5500 7158 2776 0056', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6334 5898 9800 0001', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6767 8200 9988 0077 06', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6334 9711 1111 1114', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4111 1111 1111 1111', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4024 0071 3372 7', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4917 3008 0000 0000', ['type' => 'any']));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid('3782 8224 6310 005', ['type' => 'american-express']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('3642 9034 0704 73', ['type' => 'diners-club-international']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6011 1111 1111 1117', ['type' => 'discover']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('3566 1111 1111 1113', ['type' => 'jcb']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6304 9850 2809 0561 515', ['type' => 'laser']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6759 4111 0000 0008', ['type' => 'maestro']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6759 5600 4500 5727 054', ['type' => 'maestro']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('5500 7158 2776 0056', ['type' => 'mastercard']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6334 5898 9800 0001', ['type' => 'solo']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6767 8200 9988 0077 06', ['type' => 'solo']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('6334 9711 1111 1114', ['type' => 'solo']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4111 1111 1111 1111', ['type' => 'visa']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4024 0071 3372 7', ['type' => 'visa']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('4917 3008 0000 0000', ['type' => 'visa-electron']));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('', ['type' => 'any']));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_PREFIX_NOT_MATCH, $this->handler->getErrors()));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_LENGTH_NOT_MATCH, $this->handler->getErrors()));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('4111 1111 1111 1112', ['type' => 'visa']));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('4111 1111 1111 1111', ['type' => 'mastercard']));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_PREFIX_NOT_MATCH, $this->handler->getErrors()));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_LENGTH_NOT_MATCH, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('4111 1111 1111 111', ['type' => 'any']));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_LENGTH_NOT_MATCH, $this->handler->getErrors()));
        $this->assertTrue(in_array(CreditCard::VALIDATOR_ERROR_CREDIT_CARD_NUMBER_NOT_VALID, $this->handler->getErrors()));
    }
}
