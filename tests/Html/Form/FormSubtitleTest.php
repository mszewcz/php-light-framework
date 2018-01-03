<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Form\FormSubtitle;
use PHPUnit\Framework\TestCase;


class FormSubtitleTest extends TestCase
{

    public function setUp()
    {
    }

    public function testGenerate()
    {
        $expected = '<div class="subtitle">Subtitle text</div>';
        $subtitle = new FormSubtitle('Subtitle text');

        $this->assertEquals($expected, $subtitle->generate());
    }

}
