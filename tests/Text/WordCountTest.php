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
use MS\LightFramework\Text\WordCount;
use PHPUnit\Framework\TestCase;


class WordCountTest extends TestCase
{

    public function setUp()
    {
    }

    public function testCount()
    {
        $text = 'Click '.Tags::a('here', ['href' => 'http://msworks.pl']).' to see <abbr>MS WORKS</abbr> homepage.'.Tags::CRLF;
        $text .= Tags::h1('Contact');
        $text .= Tags::p('ul. A. Sołtana 6/42'.Tags::br().'01-494 Warszawa');

        $this->assertSame(14, WordCount::count($text));
        $this->assertSame(13, WordCount::count($text, 2));
        $this->assertSame(10, WordCount::count($text, 3));
        $this->assertSame(8, WordCount::count($text, 4));
        $this->assertSame(7, WordCount::count($text, 5));
        $this->assertSame(4, WordCount::count($text, 6));
        $this->assertSame(4, WordCount::count($text, 7));
        $this->assertSame(2, WordCount::count($text, 8));
        $this->assertSame(0, WordCount::count($text, 9));
        $this->assertSame(0, WordCount::count(''));
    }

}
