<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Filesystem\FileName;
use PHPUnit\Framework\TestCase;


class FileNameTest extends TestCase
{

    public function testGetSafe()
    {
        $fileName = 'Some File Name.doc';
        $expected = 'Some-File-Name.doc';
        $this->assertSame($expected, FileName::getSafe($fileName));

        $fileName = 'Some File - Name.doc';
        $expected = 'some-file-name.doc';
        $this->assertSame($expected, FileName::getSafe($fileName, false));

        $fileName = 'Some utf-8 File name - ŁĄĆ.doc';
        $expected = 'Some-utf-8-File-name-LAC.doc';
        $this->assertSame($expected, FileName::getSafe($fileName));

        $fileName = 'Some utf-8 File name - ŁĄĆ.doc';
        $expected = 'some-utf-8-file-name-lac.doc';
        $this->assertSame($expected, FileName::getSafe($fileName, false));
    }
}
