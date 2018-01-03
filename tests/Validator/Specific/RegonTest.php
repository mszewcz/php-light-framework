<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Regon;
use PHPUnit\Framework\TestCase;


class RegonTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Regon();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('146046776'));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid());
        $this->assertTrue(in_array(Regon::VALIDATOR_ERROR_REGON_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('111'));
        $this->assertTrue(in_array(Regon::VALIDATOR_ERROR_REGON_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('111111111111'));
        $this->assertTrue(in_array(Regon::VALIDATOR_ERROR_REGON_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('aaaaaaaaa'));
        $this->assertTrue(in_array(Regon::VALIDATOR_ERROR_REGON_INCORRECT_FORMAT, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('146046775'));
        $this->assertTrue(in_array(Regon::VALIDATOR_ERROR_REGON_INVALID_NUMBER, $this->handler->getErrors()));
    }
}
