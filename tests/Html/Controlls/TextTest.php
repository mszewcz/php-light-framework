<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Controlls\Text;
use PHPUnit\Framework\TestCase;


class TextTest extends TestCase
{

    public function testInputHidden()
    {
        $expected = '<input type="hidden" name="MFVARS[test]" value="text" id="test" class="testclass"/>';
        $this->assertEquals($expected, Text::inputHidden('test', 'text', ['class' => 'testclass']));
    }

    public function testInputText()
    {
        $expected = '<input type="text" name="MFVARS[test]" value="text" id="test" class="testclass"/>';
        $this->assertEquals($expected, Text::inputText('test', 'text', ['class' => 'testclass']));
    }

    public function testInputPassword()
    {
        $expected = '<input type="password" name="MFVARS[test]" value="text" id="test" autocomplete="off" class="testclass"/>';
        $this->assertEquals($expected, Text::inputPassword('test', 'text', ['class' => 'testclass']));
    }

    public function testTextarea()
    {
        $expected = '<textarea name="MFVARS[test]" id="test" cols="20" rows="40" class="testclass">text</textarea>';
        $this->assertEquals($expected, Text::textarea('test', 'text', ['class' => 'testclass']));
    }
}
