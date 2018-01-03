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
use MS\LightFramework\Html\Form\FormControll;
use PHPUnit\Framework\TestCase;


class FormControllTest extends TestCase
{

    public function setUp()
    {
    }

    public function testGenerate()
    {
        $expected = '<div class="controll is-required has-help has-error" data-field-id="0"><div class="label">Field 1:</div><div class="html-controll"><input type="text" name="MFVARS[f1]" value="v1" id="f1" class="form-input"/></div><div class="icons"><i class="icon-help">Help text</i><i class="icon-error">Error text</i></div></div>';
        $ctrl = new FormControll(['label' => 'Field 1', 'required' => 1, 'field-id' => 0, 'controll' => Text::inputText('f1', 'v1'), 'error' => 'Error text', 'help' => 'Help text', 'script' => '']);

        $this->assertEquals($expected, $ctrl->generate());
    }

}
