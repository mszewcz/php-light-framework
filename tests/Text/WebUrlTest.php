<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Text\WebUrl;
use PHPUnit\Framework\TestCase;


class WebUrlTest extends TestCase
{

    public function setUp()
    {
    }

    public function testGenerate()
    {
        $text = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent pretium id sapien eu sagittis.';

        $expected1 = '/lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-praesent-pretium-id-sapien-eu-sagittis';
        $expected2 = '/lorem-ipsum-dolor-sit-amet-con';
        $expected3 = '/lorem-ipsum-dolor-sit-amet';

        $this->assertSame($expected1, WebUrl::generate($text));
        $this->assertSame($expected2, WebUrl::generate($text, 30));
        $this->assertSame($expected3, WebUrl::generate($text, 27));
    }

}
