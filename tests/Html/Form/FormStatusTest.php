<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Form\FormStatus;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class FormStatusTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExceptionStatusType()
    {
        $this->expectExceptionMessage('FormStatus requires "status-type" in $statusData');
        new FormStatus([]);
    }

    public function testExceptionStatusText()
    {
        $this->expectExceptionMessage('FormStatus requires "status-text" in $statusData');
        new FormStatus(['status-type' => FormStatus::STATUS_OK]);
    }

    public function testGenerate()
    {
        $expected = '<div class="status">'.Tags::CRLF;
        $expected .= '<div class="status-ok">TXT_FORM_STATUS_OK</div>'.Tags::CRLF;
        $expected .= '</div>'.Tags::CRLF;
        $status = new FormStatus(['status-type' => FormStatus::STATUS_OK, 'status-text' => 'TXT_FORM_STATUS_OK']);

        $this->assertEquals($expected, $status->generate());
    }

    public function testGenerateEmpty()
    {
        $expected = '<div class="status">&nbsp;</div>';
        $status = new FormStatus(['status-type' => '', 'status-text' => '']);

        $this->assertEquals($expected, $status->generate());
    }
}
