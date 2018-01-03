<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Variables\Variables;
use PHPUnit\Framework\TestCase;


$_FILES['file1'] = ['tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/logo_msworks.png'), 'name' => 'logo_msworks.png', 'size' => 4765, 'type' => 'image/png', 'error' => 0, 'encoding' => 'binary'];
$_FILES['MFVARS'] = ['tmp_name' => ['file2' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/script.js'), 'file3' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/text.txt')],
                     'name'     => ['file2' => 'script.js', 'file3' => 'text.txt'],
                     'size'     => ['file2' => 29, 'file3' => 22],
                     'type'     => ['file2' => 'text/plain', 'file3' => 'text/plain'],
                     'error'    => ['file2' => 0, 'file3' => 0],
                     'encoding' => ['file2' => 'utf-8', 'file3' => 'utf-8'],
];

class FilesTest extends TestCase
{

    /* @var \MS\LightFramework\Variables\Specific\Files */
    private $handler;

    public function setUp()
    {
        $vHandler = Variables::getInstance();
        $this->handler = $vHandler->files;
    }

    public function tearDown()
    {
        unset($this->handler);
    }

    public function testExceptionClone()
    {
        $this->expectExceptionMessage('Clone of MS\LightFramework\\Variables\\Specific\\Files is not allowed');
        /** @noinspection PhpExpressionResultUnusedInspection */
        clone $this->handler;
    }

    public function testExceptionVariableName()
    {
        $this->expectExceptionMessage('Variable name must be specified');
        /** @noinspection PhpParamsInspection */
        $this->handler->get();
    }

    public function testGet()
    {
        $expected1 = [
            'tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/logo_msworks.png'),
            'name'     => 'logo_msworks.png',
            'size'     => 4765,
            'type'     => 'image/png',
            'error'    => 0,
            'encoding' => 'binary',
        ];
        $expected2 = [
            'tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/script.js'),
            'name'     => 'script.js',
            'size'     => 29,
            'type'     => 'text/plain',
            'error'    => 0,
            'encoding' => 'utf-8',
        ];
        $this->assertSame($expected1, $this->handler->get('file1'));
        $this->assertSame($expected2, $this->handler->get('file2'));
    }

    public function testGetDefault()
    {
        $this->assertSame([], $this->handler->get('some_var'));
    }

    public function testGetAll()
    {
        $expected = ['file1'  => [
            'tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/logo_msworks.png'),
            'name'     => 'logo_msworks.png',
            'size'     => 4765,
            'type'     => 'image/png',
            'error'    => 0,
            'encoding' => 'binary'],
                     'MFVARS' => [
                         'file2' => [
                             'tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/script.js'),
                             'name'     => 'script.js',
                             'size'     => 29,
                             'type'     => 'text/plain',
                             'error'    => 0,
                             'encoding' => 'utf-8'],
                         'file3' => [
                             'tmp_name' => realpath(dirname(__DIR__).'/../../../../Samples/Variables/Files/text.txt'),
                             'name'     => 'text.txt',
                             'size'     => 22,
                             'type'     => 'text/plain',
                             'error'    => 0,
                             'encoding' => 'utf-8'],
                     ],
        ];
        $this->assertSame($expected, $this->handler->getAll());
    }
}
