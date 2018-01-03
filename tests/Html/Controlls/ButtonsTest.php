<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Controlls\Buttons;
use PHPUnit\Framework\TestCase;


class ButtonsTest extends TestCase
{

    public function testButton()
    {
        $expected = '<input type="button" name="MFVARS[test]" value="text" id="test" class="testclass"/>';
        $this->assertEquals($expected, Buttons::button('test', 'text', ['class' => 'testclass']));
    }

    public function testButtonSubmit()
    {
        $expected = '<input type="submit" name="MFVARS[test]" value="text" id="test" class="testclass"/>';
        $this->assertEquals($expected, Buttons::buttonSubmit('test', 'text', ['class' => 'testclass']));
    }

    public function testButtonReset()
    {
        $expected = '<input type="reset" name="MFVARS[test]" value="text" id="test" class="testclass"/>';
        $this->assertEquals($expected, Buttons::buttonReset('test', 'text', ['class' => 'testclass']));
    }

    public function testInputImage()
    {
        $expected = '<input type="image" name="MFVARS[test]" src="/tests/_samples/Image/Resize1.jpg" id="test" class="testclass"/>';
        $this->assertEquals($expected, Buttons::inputImage('test', '/tests/_samples/Image/Resize1.jpg', ['class' => 'testclass']));
    }
}
