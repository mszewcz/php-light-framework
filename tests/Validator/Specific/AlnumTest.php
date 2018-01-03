<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Alnum;
use PHPUnit\Framework\TestCase;


class AlnumTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Alnum();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('jaźń98', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jAź ń98', ['allow-spaces' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('jAź ń98', ['allow-whitespaces' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('jAźń98', ['utf-8' => false]));
        $this->assertTrue(in_array(Alnum::VALIDATOR_ERROR_ALNUM_NOT_VALID, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('dfe-x30', ['allow-spaces' => true]));
        $this->assertTrue(in_array(Alnum::VALIDATOR_ERROR_ALNUM_SPACES_NOT_VALID, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('dfe_x30', ['allow-spaces' => true]));
        $this->assertTrue(in_array(Alnum::VALIDATOR_ERROR_ALNUM_SPACES_NOT_VALID, $this->handler->getErrors()));
        $this->assertFalse($this->handler->isValid('@dfe', ['allow-spaces' => true]));
        $this->assertTrue(in_array(Alnum::VALIDATOR_ERROR_ALNUM_SPACES_NOT_VALID, $this->handler->getErrors()));
    }
}
