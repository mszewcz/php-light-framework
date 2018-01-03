<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Validator\Chain;
use MS\LightFramework\Validator\Specific;
use PHPUnit\Framework\TestCase;


class ChainTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionNoValidatorsFound()
    {
        $this->expectExceptionMessage('No validators found in chain');
        $chain = new Chain();
        $chain->isValid('test');
    }

    public function testExceptionParseChainNotArray()
    {
        $this->expectExceptionMessage('Validators chain must be an array');
        new Chain(999);
    }

    public function testExceptionParseChainValidatorNotFound()
    {
        $this->expectExceptionMessage('Validator "StringsLength" not found');

        $v = [
            'StringsLength' => ['length' => 11],
        ];
        new Chain($v);
    }

    public function testExceptionParseChainIncorrectChainFormat()
    {
        $this->expectExceptionMessage('Validator chain element must be either instance of AbstractValidator or array("validatorName" => array(options))');

        $v = [
            999,
        ];
        new Chain($v);
    }

    public function testExceptionRemove()
    {
        $this->expectExceptionMessage('Validator name must be a string');
        $chain = new Chain();
        $chain->remove(99);
    }

    public function testIsValid1()
    {
        $chain = new Chain();
        $valid = $chain->add(new Specific\Number(['greater-than' => 4]))->isValid(5);
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertTrue($valid);
        $this->assertTrue(empty($errors['Number']));
        $this->assertArrayHasKey('Number', $result);
        $this->assertTrue($result['Number']);
    }

    public function testIsValid2()
    {
        $chain = new Chain();
        $valid = $chain->add(['StringLength' => ['length' => 11]])->add(new Specific\Pesel())->isValid('78041500632');
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertTrue($valid);
        $this->assertTrue(empty($errors['StringLength']));
        $this->assertTrue(empty($errors['Pesel']));
        $this->assertArrayHasKey('StringLength', $result);
        $this->assertArrayHasKey('Pesel', $result);
        $this->assertTrue($result['StringLength']);
        $this->assertTrue($result['Pesel']);
    }

    public function testIsValid3()
    {
        $v = [
            'StringLength' => ['length' => 11],
            new Specific\Pesel(),
        ];
        $chain = new Chain($v);
        $valid = $chain->isValid('78041500632');
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertTrue($valid);
        $this->assertTrue(empty($errors['StringLength']));
        $this->assertTrue(empty($errors['Pesel']));
        $this->assertArrayHasKey('StringLength', $result);
        $this->assertArrayHasKey('Pesel', $result);
        $this->assertTrue($result['StringLength']);
        $this->assertTrue($result['Pesel']);
    }

    public function testIsValid4()
    {
        $v = [
            'StringLength' => ['length' => 11],
            new Specific\Pesel(),
        ];
        $chain = new Chain();
        $valid = $chain->isValid('78041500632', $v);
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertTrue($valid);
        $this->assertTrue(empty($errors['StringLength']));
        $this->assertTrue(empty($errors['Pesel']));
        $this->assertArrayHasKey('StringLength', $result);
        $this->assertArrayHasKey('Pesel', $result);
        $this->assertTrue($result['StringLength']);
        $this->assertTrue($result['Pesel']);
    }

    public function testReset()
    {
        $v1 = [
            'StringLength' => ['length' => 11],
            new Specific\Pesel(),
        ];
        $v2 = [
            'StringLength' => ['length' => 3],
            'Number'       => ['min' => 765],
        ];
        $chain = new Chain($v1);
        $valid = $chain->isValid('78041500632');
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertTrue($valid);
        $this->assertTrue(empty($errors['StringLength']));
        $this->assertTrue(empty($errors['Pesel']));
        $this->assertArrayHasKey('StringLength', $result);
        $this->assertArrayHasKey('Pesel', $result);
        $this->assertTrue($result['StringLength']);
        $this->assertTrue($result['Pesel']);

        $chain->reset();
        $valid = $chain->isValid(223, $v2);
        $result = $chain->getChainResults();
        $errors = $chain->getChainErrors();

        $this->assertFalse($valid);
        $this->assertTrue(empty($errors['Pesel']));
        $this->assertTrue(in_array(Specific\Number::VALIDATOR_ERROR_NUMBER_MUST_BE_HIGHER_OR_EQUAL_TO_MIN, $errors['Number']));
        $this->assertArrayNotHasKey('Pesel', $result);
        $this->assertArrayHasKey('Number', $result);
        $this->assertTrue($result['StringLength']);
        $this->assertFalse($result['Number']);
    }

    public function testGetChain()
    {
        $vc = [
            'StringLength' => ['length' => 3],
            'Number'       => ['min' => 765],
        ];
        $expected1 = "StringLength ['type'=>'equals', 'length'=>3, 'inclusive'=>false],\n";
        $expected1 .= "Number ['type'=>'integer', 'min'=>765, 'max'=>null, 'inclusive'=>true]";

        $expected2 = "StringLength,\n";
        $expected2 .= "Number";

        $chain = new Chain($vc);
        $result = $chain->getChain(true);
        $this->assertEquals($expected1, $result);
        $result = $chain->getChain(false);
        $this->assertEquals($expected2, $result);
    }

    public function testRemove()
    {
        $vc = [
            'StringLength' => ['length' => 3],
            'Number'       => ['min' => 765],
        ];
        $expected1 = "StringLength ['type'=>'equals', 'length'=>3, 'inclusive'=>false],\n";
        $expected1 .= "Number ['type'=>'integer', 'min'=>765, 'max'=>null, 'inclusive'=>true]";
        $expected2 = "Number ['type'=>'integer', 'min'=>765, 'max'=>null, 'inclusive'=>true]";

        $chain = new Chain($vc);
        $result = $chain->getChain(true);
        $this->assertEquals($expected1, $result);

        $chain->remove('StringLength');
        $result = $chain->getChain(true);
        $this->assertEquals($expected2, $result);
    }
}
