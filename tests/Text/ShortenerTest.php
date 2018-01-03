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
use MS\LightFramework\Text\Shortener;
use PHPUnit\Framework\TestCase;


class ShortenerTest extends TestCase
{

    public function setUp()
    {
    }

    public function testShorten()
    {
        $text = Tags::p('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam. Cras ut accumsan lacus. Nunc eu augue ac eros consectetur eleifend. Mauris at dolor imperdiet, aliquam nulla non, consectetur erat. Morbi sit amet vulputate nisi.');
        $text .= Tags::p('Phasellus mollis sem id metus dapibus, vitae euismod est auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sed blandit enim. Etiam in pharetra lectus. Nam semper, diam eu lobortis consequat, mi orci mattis augue, nec lobortis massa leo non nisi. Suspendisse.');

        $expected1 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi...';
        $expected2 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium...';
        $expected3 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam. Cras ut accumsan lacus. Nunc eu augue ac eros consectetur eleifend. Mauris at dolor...';
        $expected4 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $expected5 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pra';
        $expected6 = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis. Pellentesque iaculis quam vitae nisi suscipit, in aliquet purus lobortis. Morbi sit amet placerat mi, sed ultricies quam. Cras ut accumsan lacus. Nunc eu augue ac eros consectetur eleifend. Mauris at dolor imperdiet, aliquam nulla non, consectetur erat. Morbi sit amet vulputate nisi. Phasellus mollis sem id metus dapibus, vitae euismod est auctor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut sed blandit enim. Etiam in pharetra lectus. Nam semper, diam eu lobortis consequat, mi orci mattis augue, nec lobortis massa leo non nisi. Suspendisse.';

        $this->assertSame($expected1, Shortener::shorten($text, 140, true, '...'));
        $this->assertSame($expected2, Shortener::shorten($text, 75, true, '...'));
        $this->assertSame($expected3, Shortener::shorten($text, 310, true, '...'));
        $this->assertSame($expected4, Shortener::shorten($text, 60, true, '.'));
        $this->assertSame($expected5, Shortener::shorten($text, 60, false, ''));
        $this->assertSame($expected6, Shortener::shorten($text, null));
        $this->assertSame($expected6, Shortener::shorten($text, 1000));
    }

}
