<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Specific\Ip;
use PHPUnit\Framework\TestCase;


class IpTest extends TestCase
{

    /* @var \MS\LightFramework\Validator\Specific\AbstractSpecific */
    private $handler;

    public function setUp()
    {
        $this->handler = new Ip();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->handler->isValid('01111111.00000000.00000000.00000001', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('255.255.255.255', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('127.0.0.1', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('ff.ff.ff.fe', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('::', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('::1', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('::ffff:0:0', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('::ffff:127.0.0.1', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:7f8::', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2002::', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:db8::1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('::ffff:ffff:ffff:ffff:ffff:ffff:ffff', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:0db8:0000:0000:0000:0000:1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:0db8:0000:0000:0000:0000:1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:0db8:0:0:0:0:1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:0db8:0:0::1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:0db8::1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));
        $this->assertTrue($this->handler->isValid('2001:db8::1428:57ab', []));
        $this->assertTrue(empty($this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('300.300.300.300', []));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid(':::ffff:ffff:ffff:ffff:ffff:ffff:ffff', []));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('127.0.0.1', ['allow-ipv4' => false]));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('2001:0db8:0000:0000:0000:0000:1428:57ab', ['allow-ipv6' => false]));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_INVALID_NUMBER, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('192.168.0.0', ['allow-private-range' => false]));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_PRIVATE_RANGE_NOT_ALLOWED, $this->handler->getErrors()));

        $this->assertFalse($this->handler->isValid('224.0.0.0', ['allow-reserved-range' => false]));
        $this->assertTrue(in_array(Ip::VALIDATOR_ERROR_IP_RESERVED_RANGE_NOT_ALLOWED, $this->handler->getErrors()));
    }
}
