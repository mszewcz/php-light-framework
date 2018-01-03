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
use MS\LightFramework\Html\Form\FormControll;
use MS\LightFramework\Html\Form\FormFieldset;
use MS\LightFramework\Html\Form\FormSet;
use MS\LightFramework\Html\Form\FormStatus;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormFieldsetTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionForm()
    {
        $this->expectExceptionMessage('FormFieldset cannot contain Form');

        $fset = new FormFieldset('Fieldset label');
        $fset->addElement(new Form([]));
    }

    public function testExceptionFormStatus()
    {
        $this->expectExceptionMessage('FormFieldset cannot contain FormStatus');

        $fset = new FormFieldset('Fieldset label');
        $fset->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
    }

    public function testExceptionFormSet()
    {
        $this->expectExceptionMessage('FormFieldset cannot contain FormSet');

        $fset = new FormFieldset('Fieldset label');
        $fset->addElement(new FormSet('set2', ['data-set-id' => 'set-2']));
    }

    public function testGenerate()
    {
        $expected = '<fieldset class="fieldset">'.Tags::CRLF;
        $expected .= '<legend>Fieldset label</legend>'.Tags::CRLF;
        $expected .= '<div class="controll is-required has-help" data-field-id="0"><div class="label">Field 1:</div><div class="html-controll"><input type="text" name="MFVARS[f1]" value="v1" id="f1" class="form-input"/></div><div class="icons"><i class="icon-help">cc</i></div></div>'.Tags::CRLF;
        $expected .= '</fieldset>'.Tags::CRLF;

        $fset = new FormFieldset('Fieldset label');
        $fset->addElement(
            new FormControll(['label' => 'Field 1', 'required' => 1, 'field-id' => 0, 'controll' => Text::inputText('f1', 'v1'), 'error' => '', 'help' => 'cc', 'script' => ''])
        );

        $this->assertEquals($expected, $fset->generate());
    }
}
