<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Filesystem\SymbolicLink;
use PHPUnit\Framework\TestCase;


class SymbolicLinkTest extends TestCase
{

    public function testCreateExistsRemove()
    {
        $link = realpath(dirname(__DIR__).'/../../../Samples').'/Filesystem/testLink.xml';
        $target = realpath(dirname(__DIR__).'/../../').'/../phpunit.xml';

        $this->assertFileNotExists($link);
        $this->assertFalse(SymbolicLink::exists($link));

        SymbolicLink::create($link, $target);
        SymbolicLink::read($link);
        SymbolicLink::remove($link);
    }
}
