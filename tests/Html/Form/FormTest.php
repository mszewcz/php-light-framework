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
use MS\LightFramework\Html\Form\FormStatus;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionForm()
    {
        $this->expectExceptionMessage('Form cannot contain Form');

        $form = new Form(['hiddens' => []]);
        $form->addElement(new Form());
    }

    public function testExceptionFormStatus()
    {
        $this->expectExceptionMessage('Form can contain only one FormStatus element');

        $form = new Form(['hiddens' => []]);
        $form->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
        $form->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
    }

    public function testGenerate()
    {
        $expected = '<form method="post" enctype="multipart/form-data" id="data-form" class="data-form label-width-200">'.Tags::CRLF;
        $expected .= '<input type="hidden" name="MFVARS[hidden-1]" value="hidden-value-1" id="hidden-1"/>'.Tags::CRLF;
        $expected .= '<div class="status">&nbsp;</div>'.Tags::CRLF;
        $expected .= '<div class="controll is-required has-help" data-field-id="0"><div class="label">Field 1:</div><div class="html-controll"><input type="text" name="MFVARS[f1]" value="v1" id="f1" class="form-input"/></div><div class="icons"><i class="icon-help">cc</i></div></div>'.Tags::CRLF;
        $expected .= '</form>'.Tags::CRLF;

        $hiddens = [];
        $hiddens[] = Text::inputHidden('hidden-1', 'hidden-value-1');

        $form = new Form(['hiddens' => $hiddens, 'label-width' => 200]);
        $form->addElement(new FormStatus(['status-type' => '', 'status-text' => '']));
        $form->addElement(
            new FormControll(['label' => 'Field 1', 'required' => 1, 'field-id' => 0, 'controll' => Text::inputText('f1', 'v1'), 'error' => '', 'help' => 'cc', 'script' => ''])
        );

        $this->assertEquals($expected, $form->generate());
    }
}
