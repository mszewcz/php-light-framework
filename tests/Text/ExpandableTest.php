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
use MS\LightFramework\Text\Expandable;
use PHPUnit\Framework\TestCase;


class ExpandableTest extends TestCase
{

    public function setUp()
    {
    }

    public function testExpandable()
    {
        $text = Tags::p('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis.');
        $text .= Tags::p('Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam.');

        $expected1 = '<span class="expandable"><span class="short">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi...<span class="expand-link">TXT_EXPANDABLE_EXPAND</span></span><span class="full">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam.<span class="collapse-link">TXT_EXPANDABLE_COLLAPSE</span></span></span>';
        $expected2 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam.';

        $this->assertSame($expected1, Expandable::generate($text, 140));
        $this->assertSame($expected2, Expandable::generate($text, null));
        $this->assertSame($expected2, Expandable::generate($text, 1000));
    }

}
