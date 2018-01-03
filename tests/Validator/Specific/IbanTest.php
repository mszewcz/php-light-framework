<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Iban;
use PHPUnit\Framework\TestCase;


class IbanTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Iban();
    }

    public function testExceptionUnsupportedCountryCode()
    {
        $this->expectExceptionMessage('Unsopported country code: XX');
        $this->handler->isValid(1, ['country-code' => 'XX']);
    }

    public function testExceptionOutsideSepaCountryCode()
    {
        $this->expectExceptionMessage('Countries outside the Single Euro Payments Area (SEPA) are not supported');
        $this->handler->isValid(1, ['allow-non-sepa' => false, 'country-code' => 'VG']);
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('65 1060 0076 0000 3200 0005 7153', ['allow-non-sepa' => true, 'country-code' => 'PL']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('PL 65 1060 0076 0000 3200 0005 7153', ['allow-non-sepa' => true, 'country-code' => 'PL']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('PL65 1060 0076 0000 3200 0005 7153', ['allow-non-sepa' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('', ['country-code' => 'PL']));
        $this->assertTrue(in_array(Iban::VALIDATOR_ERROR_IBAN_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('PL651060007600003200000571'));
        $this->assertTrue(in_array(Iban::VALIDATOR_ERROR_IBAN_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('VG65 1060 0076 0000 3200 0005 7153', ['allow-non-sepa' => true]));
        $this->assertTrue(in_array(Iban::VALIDATOR_ERROR_IBAN_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('PL65 1060 0074 0000 3200 0005 7153', ['allow-non-sepa' => true]));
        $this->assertTrue(in_array(Iban::VALIDATOR_ERROR_IBAN_INVALID_NUMBER, $this->handler->getErrors()));
    }
}
