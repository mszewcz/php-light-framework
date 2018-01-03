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
use MS\LightFramework\Text\StripTags;
use PHPUnit\Framework\TestCase;


class StripTagsTest extends TestCase
{

    public function setUp()
    {
    }

    public function testStrip()
    {
        $text = 'Click '.Tags::a('here', ['href' => 'http://msworks.pl']).' to see <abbr>MS Works</abbr> homepage.';
        $text .= Tags::h1('Contact');
        $text .= Tags::p('ul. A. Sołatna 6/42'.Tags::br().'01-494 Warszawa');

        $expected1 = 'Click here to see MS Works homepage. Contact ul. A. Sołatna 6/42 01-494 Warszawa';
        $expected2 = 'Click here to see MS Works homepage. Contact ul. A. Sołatna 6/42<br/>01-494 Warszawa';
        $expected3 = 'Click <a href="http://msworks.pl" title="">here</a> to see MS Works homepage. Contact ul. A. Sołatna 6/42 01-494 Warszawa';
        $expected4 = 'Click <a href="http://msworks.pl" title="">here</a> to see <abbr>MS Works</abbr> homepage. Contact ul. A. Sołatna 6/42 01-494 Warszawa';

        $this->assertEquals($expected1, StripTags::strip($text));
        $this->assertEquals($expected2, StripTags::strip($text, ['br']));
        $this->assertEquals($expected3, StripTags::strip($text, ['a']));
        $this->assertEquals($expected4, StripTags::strip($text, ['a', 'abbr']));
    }

}
