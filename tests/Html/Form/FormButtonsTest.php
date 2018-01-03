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
use MS\LightFramework\Html\Form\FormButtons;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormButtonsTest extends TestCase
{

    public function setUp()
    {
    }

    public function testGenerate()
    {
        $expected = '<div class="buttons">'.Tags::CRLF;
        $expected .= '<input type="submit" name="MFVARS[btn-submit]" value="SUBMIT" id="btn-submit" class="formButton"/>'.Tags::CRLF;
        $expected .= '<input type="reset" name="MFVARS[btn-reset]" value="RESET" id="btn-reset" class="formButton"/>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;

        $btns = [];
        $btns[] = Buttons::buttonSubmit('btn-submit', 'SUBMIT');
        $btns[] = Buttons::buttonReset('btn-reset', 'RESET');
        $buttons = new FormButtons($btns);

        $this->assertEquals($expected, $buttons->generate());
    }

}
