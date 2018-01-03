<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Pesel;
use PHPUnit\Framework\TestCase;


class PeselTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Pesel();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('78041500632'));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid());
        $this->assertTrue(in_array(Pesel::VALIDATOR_ERROR_PESEL_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('7804150063'));
        $this->assertTrue(in_array(Pesel::VALIDATOR_ERROR_PESEL_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('78041500631'));
        $this->assertTrue(in_array(Pesel::VALIDATOR_ERROR_PESEL_INVALID_NUMBER, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('aaaaaaaaaaa'));
        $this->assertTrue(in_array(Pesel::VALIDATOR_ERROR_PESEL_INCORRECT_FORMAT, $this->handler->getErrors()));
    }
}
