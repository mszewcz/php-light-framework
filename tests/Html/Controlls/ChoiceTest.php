<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Tags;
use MS\LightFramework\Html\Controlls\Choice;
use PHPUnit\Framework\TestCase;


class ChoiceTest extends TestCase
{

    public function testInputRadio()
    {
        $expected = '<input type="radio" name="MFVARS[test]" value="text" id="rb_test_text" checked="checked" class="testclass"/>';
        $this->assertEquals($expected, Choice::inputRadio('test', 'text', true, ['class' => 'testclass']));
    }

    public function testInputCheckbox()
    {
        $expected = '<input type="checkbox" name="MFVARS[test]" value="text" id="cb_test" checked="checked" class="testclass"/>';
        $this->assertEquals($expected, Choice::inputCheckbox('test', 'text', true, ['class' => 'testclass']));
    }

    public function testSelect()
    {
        $options = [1          => 'opt1',
                    'OPTGROUP' => ['name' => 'opg1', 'options' => [2 => 'opt2', 3 => 'opt3']],
                    4          => 'opt4',
        ];
        $expected = '<div class="select-wrapper multiple">';
        $expected .= '<select name="MFVARS[test][]" id="test" data-selected="2" multiple="multiple" class="testclass">'.Tags::CRLF;
        $expected .= '<option value="1" class="opt-1">opt1</option>'.Tags::CRLF;
        $expected .= '<optgroup label="opg1">'.Tags::CRLF;
        $expected .= '<option value="2" class="opt-2" style="padding-left: 10px" selected="selected">opt2</option>'.Tags::CRLF;
        $expected .= '<option value="3" class="opt-3" style="padding-left: 10px">opt3</option>'.Tags::CRLF;
        $expected .= '</optgroup>'.Tags::CRLF;
        $expected .= '<option value="4" class="opt-4">opt4</option>'.Tags::CRLF;
        $expected .= '</select></div>';

        $this->assertEquals($expected, Choice::select('test', $options, [2], ['class' => 'testclass', 'multiple' => true]));
    }
}
