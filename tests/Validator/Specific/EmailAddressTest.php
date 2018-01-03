<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\EmailAddress;
use PHPUnit\Framework\TestCase;


class EmailAddressTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new EmailAddress();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('biuro@msworks.pl', ['domain-check' => true, 'mx-check' => true, 'deep-mx-check' => true]));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('biuro@msworks.pl', ['domain-check' => false, 'mx-check' => false, 'deep-mx-check' => false]));
        $this->assertTrue(empty($this->handler->getErrors()));
        //$this->assertTrue($this->handler->isValid('email.address@jira.msworks.pl', array('domain-check'=>true,'mx-check'=>true,'deep-mx-check'=>true)));
        $this->assertTrue($this->handler->isValid('valid.email.address@msworks.pl', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('valid-email-address@msworks.pl', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('valid!#$%&\'*email+-/=?^_`{|}~address99@msworks.pl', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('"validemailaddress"@msworks.pl', []));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid..email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid,email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid<email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid>email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid:email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid;email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid(email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid)email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('invalid@email@msworks.pl', []));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('email@192.168.0.1', ['domain-check' => false, 'mx-check' => true, 'deep-mx-check' => true]));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INCORRECT_FORMAT, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('email@non-email-host.com', ['domain-check' => true]));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INVALID_DOMAIN, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('email@non-email-host.com', ['domain-check' => false, 'mx-check' => true, 'deep-mx-check' => true]));
        $this->assertTrue(in_array(EmailAddress::VALIDATOR_ERROR_EMAIL_INVALID_MX, $this->handler->getErrors()));
    }
}
