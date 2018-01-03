<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Nip;
use PHPUnit\Framework\TestCase;


class NipTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Nip();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('5272674548'));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('527-267-45-48'));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('527 267 45 48'));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid());
        $this->assertTrue(in_array(Nip::VALIDATOR_ERROR_NIP_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('111'));
        $this->assertTrue(in_array(Nip::VALIDATOR_ERROR_NIP_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('111 111 111 111 111'));
        $this->assertTrue(in_array(Nip::VALIDATOR_ERROR_NIP_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('aaa-aaa-aa-aa'));
        $this->assertTrue(in_array(Nip::VALIDATOR_ERROR_NIP_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('527 267 45 47'));
        $this->assertTrue(in_array(Nip::VALIDATOR_ERROR_NIP_INVALID_NUMBER, $this->handler->getErrors()));
    }
}
