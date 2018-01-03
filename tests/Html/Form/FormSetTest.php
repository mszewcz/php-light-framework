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
use MS\LightFramework\Html\Form\FormSet;
use MS\LightFramework\Html\Form\FormSubtitle;
use MS\LightFramework\Html\Form\FormStatus;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormSetTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionForm()
    {
        $this->expectExceptionMessage('FormSet cannot contain Form');

        $set = new FormSet('set1', ['data-set-id' => 'set-1']);
        $set->addElement(new Form([]));
    }

    public function testExceptionFormStatus()
    {
        $this->expectExceptionMessage('FormSet cannot contain FormStatus');

        $set = new FormSet('set1', ['data-set-id' => 'set-1']);
        $set->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
    }

    public function testExceptionFormSet()
    {
        $this->expectExceptionMessage('FormSet cannot contain FormSet');

        $set = new FormSet('set1', ['data-set-id' => 'set-1']);
        $set->addElement(new FormSet('set2', ['data-set-id' => 'set-2']));
    }

    public function testExceptionDataSetID()
    {
        $this->expectExceptionMessage('FormSet must have "data-set-id" attribute');

        new FormSet('set1', []);
    }

    public function testGenerate()
    {
        $expected = '<div data-set-id="set-1" class="set">'.Tags::CRLF;
        $expected .= '<div class="title cf">'.Tags::CRLF;
        $expected .= '<span class="name">set1</span>'.Tags::CRLF;
        $expected .= '<span class="expand" title="TXT_PANEL_DATA_FORM_SET_EXPAND"></span>'.Tags::CRLF;
        $expected .= '<span class="collapse" title="TXT_PANEL_DATA_FORM_SET_COLLAPSE"></span>'.Tags::CRLF;
        $expected .= '<input type="hidden" name="set-1_state" value="0"/>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;
        $expected .= '<div class="content border-radius-5">'.Tags::CRLF;
        $expected .= '<div class="subtitle">subtitle texts</div>'.Tags::CRLF;
        $expected .= '<div class="controll is-required has-help" data-field-id="0"><div class="label">Field 1:</div><div class="html-controll"><input type="text" name="MFVARS[f1]" value="v1" id="f1" class="form-input"/></div><div class="icons"><i class="icon-help">cc</i></div></div>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;

        $set = new FormSet('set1', ['data-set-id' => 'set-1']);
        $set->addElement(new FormSubtitle('subtitle texts'));
        $set->addElement(
            new FormControll(['label' => 'Field 1', 'required' => 1, 'field-id' => 0, 'controll' => Text::inputText('f1', 'v1'), 'error' => '', 'help' => 'cc', 'script' => ''])
        );

        $this->assertEquals($expected, $set->generate());
    }
}
