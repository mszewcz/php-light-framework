<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Random\Random;
use PHPUnit\Framework\TestCase;


class RandomTest extends TestCase
{

    public function setUp()
    {
    }

    public function testHash()
    {
        $this->assertNotEquals(Random::hash(), Random::hash());
        $this->assertNotEquals(Random::hash(), Random::hash());
        $this->assertNotEquals(Random::hash(), Random::hash());
        $this->assertNotEquals(Random::hash(), Random::hash());
        $this->assertNotEquals(Random::hash(), Random::hash());
        $this->assertNotEquals(Random::hash(), Random::hash());
    }

    public function testHtmlId()
    {
        $this->assertNotEquals(Random::htmlId(), Random::htmlId());
        $this->assertNotEquals(Random::htmlId(), Random::htmlId());
        $this->assertNotEquals(Random::htmlId(), Random::htmlId());
        $this->assertNotEquals(Random::htmlId(), Random::htmlId());
        $this->assertNotEquals(Random::htmlId(), Random::htmlId());

        $this->assertNotRegExp('/^[0-9]/', Random::htmlId());
        $this->assertNotRegExp('/^[0-9]/', Random::htmlId());
        $this->assertNotRegExp('/^[0-9]/', Random::htmlId());
        $this->assertNotRegExp('/^[0-9]/', Random::htmlId());
        $this->assertNotRegExp('/^[0-9]/', Random::htmlId());
    }
}
