<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Isbn;
use PHPUnit\Framework\TestCase;


class IsbnTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Isbn();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('0-306-40615-2', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83-7197-504-X', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('9971-5-0210-0', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978-3-16-148410-0', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978-83-7181-510-2', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('0 306 40615 2', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83 7197 504 X', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83 7361 409 5', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978 3 16 148410 0', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978 83 7181 510 2', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('0306406152', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('837197504X', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('8373614095', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('9783161484100', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('9788371815102', ['type' => 'auto']));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid('0-306-40615-2', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83-7197-504-X', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83-7361-409-5', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('0 306 40615 2', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83 7197 504 X', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('83 7361 409 5', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('0306406152', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('837197504X', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('8373614095', ['type' => 'isbn-10']));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertTrue($this->handler->isValid('978-3-16-148410-0', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978-83-7181-510-2', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978 3 16 148410 0', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('978 83 7181 510 2', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('9783161484100', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('9788371815102', ['type' => 'isbn-13']));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('0-306-70615-2', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('83-7137-504-X', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('83-7341-409-5', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('978-1-16-148410-0', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('978-93-7181-510-2', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('', ['type' => 'auto']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('', ['type' => 'isbn-10']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('', ['type' => 'isbn-13']));
        $this->assertTrue(in_array(Isbn::VALIDATOR_ERROR_ISBN_INCORRECT_FORMAT, $this->handler->getErrors()));
    }
}
