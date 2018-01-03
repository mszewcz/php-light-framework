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
use MS\LightFramework\Text\CharCount;
use PHPUnit\Framework\TestCase;


class CharCountTest extends TestCase
{

    public function setUp()
    {
    }

    public function testCount()
    {
        $text = 'Click '.Tags::a('here', ['href' => 'http://msworks.pl']).' to see <abbr>MS Works</abbr> homepage.'.Tags::CRLF;
        $text .= Tags::h1('Contact');
        $text .= Tags::p('ul. A. Sołtana 6/42'.Tags::br().'01-494 Warszawa');

        $this->assertSame(67, CharCount::count($text));
        $this->assertSame(80, CharCount::count($text, true));
        $this->assertSame(0, CharCount::count(''));
    }

}
