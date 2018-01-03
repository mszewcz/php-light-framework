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
use MS\LightFramework\Html\Form\Form;
use MS\LightFramework\Html\Form\FormConditional;
use MS\LightFramework\Html\Form\FormControll;
use MS\LightFramework\Html\Form\FormSet;
use MS\LightFramework\Html\Form\FormSubtitle;
use MS\LightFramework\Html\Form\FormStatus;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormConditionalTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionForm()
    {
        $this->expectExceptionMessage('FormConditional cannot contain Form');

        $cond = new FormConditional(['data-conditional-id' => 'conditional-1']);
        $cond->addElement(new Form([]));
    }

    public function testExceptionFormStatus()
    {
        $this->expectExceptionMessage('FormConditional cannot contain FormStatus');

        $cond = new FormConditional(['data-conditional-id' => 'conditional-1']);
        $cond->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
    }

    public function testExceptionFormSet()
    {
        $this->expectExceptionMessage('FormConditional cannot contain FormSet');

        $cond = new FormConditional(['data-conditional-id' => 'conditional-1']);
        $cond->addElement(new FormSet('set1', ['data-set-id' => 'set-1']));
    }

    public function testExceptionDataConditionalID()
    {
        $this->expectExceptionMessage('FormConditional must have "data-conditional-id" attribute');

        new FormConditional([]);
    }

    public function testGenerate()
    {
        $expected = '<div data-conditional-id="conditional-1" class="conditional">'.Tags::CRLF;
        $expected .= '<input type="hidden" name="conditional-1_state" value="0"/>'.Tags::CRLF;
        $expected .= '<div class="subtitle">subtitle texts</div>'.Tags::CRLF;
        $expected .= '<div class="controll is-required has-help" data-field-id="0"><div class="label">Field 1:</div><div class="html-controll"><input type="text" name="MFVARS[f1]" value="v1" id="f1" class="form-input"/></div><div class="icons"><i class="icon-help">cc</i></div></div>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;

        $cond = new FormConditional(['data-conditional-id' => 'conditional-1']);
        $cond->addElement(new FormSubtitle('subtitle texts'));
        $cond->addElement(
            new FormControll(['label' => 'Field 1', 'required' => 1, 'field-id' => 0, 'controll' => Text::inputText('f1', 'v1'), 'error' => '', 'help' => 'cc', 'script' => ''])
        );

        $this->assertEquals($expected, $cond->generate());
    }
}
