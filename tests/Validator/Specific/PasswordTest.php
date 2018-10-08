<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Encryption\Password as PasswordEnctyption;
use MS\LightFramework\Validator\Specific\Password;
use PHPUnit\Framework\TestCase;


class PasswordValidatorTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Password();
    }

    public function testIsValid()
    {
        $p1 = PasswordEnctyption::hash('@Fu93l2=-@dr1o');
        $p2 = PasswordEnctyption::hash('@Fu93l2=-@dr2o');
        $storedP = [$p1, $p2];

        $this->assertTrue($this->handler->isValid('@Fu93l2=-@dr3o', ['stored-passwords' => $storedP]));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid());
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_INCORRECT_LENGTH, $this->handler->getErrors()));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LETTER, $this->handler->getErrors()));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_LOWERCASE_LETTER, $this->handler->getErrors()));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_UPPERCASE_LETTER, $this->handler->getErrors()));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_NUMBER, $this->handler->getErrors()));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_CONTAIN_SPECIAL_CHARACTER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('@Fu93l2=-@dr2o', ['stored-passwords' => $storedP]));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ONE, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('@Fu93l2=-@dr1o', ['stored-passwords' => $storedP, 'require-different-than-previous-all' => true]));
        $this->assertTrue(in_array(Password::VALIDATOR_ERROR_PASSWORD_MUST_BE_DIFFERENT_THAN_PREVIOUS_ALL, $this->handler->getErrors()));
    }
}
