<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

use MS\LightFramework\Html\Table;
use MS\LightFramework\Html\Tags;
use PHPUnit\Framework\TestCase;


class TableTest extends TestCase
{

    public function testBuildTable1()
    {
        $expected = '<table cellpadding="0" cellspacing="0" border="0">'.Tags::CRLF;
        $expected .= '<thead>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<th>h.1.1</th>'.Tags::CRLF;
        $expected .= '<th>h.1.2</th>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '</thead>'.Tags::CRLF;
        $expected .= '<tbody>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<td>r.1.1</td>'.Tags::CRLF;
        $expected .= '<td>r.1.2</td>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<td>r.2.1</td>'.Tags::CRLF;
        $expected .= '<td>r.2.2</td>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '</tbody>'.Tags::CRLF;
        $expected .= '<tfoot>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<td>f.1.1</td>'.Tags::CRLF;
        $expected .= '<td>f.1.2</td>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '</tfoot>'.Tags::CRLF;
        $expected .= '</table>'.Tags::CRLF;

        $tbl = new Table();
        $tbl->th('h.1.1', []);
        $tbl->th('h.1.2', []);
        $tbl->tr();
        $tbl->thead();
        $tbl->td('r.1.1');
        $tbl->td('r.1.2');
        $tbl->tr();
        $tbl->td('r.2.1');
        $tbl->td('r.2.2');
        $tbl->tr();
        $tbl->tbody();
        $tbl->td('f.1.1');
        $tbl->td('f.1.2');
        $tbl->tr();
        $tbl->tfoot();
        $result = $tbl->generate();

        $this->assertEquals($expected, $result);
    }

    public function testBuildTable2()
    {
        $expected = '<table cellpadding="0" cellspacing="0" border="0">'.Tags::CRLF;
        $expected .= '<caption>caption</caption>'.Tags::CRLF;
        $expected .= '<colgroup>'.Tags::CRLF;
        $expected .= '<col class="c-1-1"/>'.Tags::CRLF;
        $expected .= '<col class="c-1-2"/>'.Tags::CRLF;
        $expected .= '</colgroup>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<td>r.1.1</td>'.Tags::CRLF;
        $expected .= '<td>r.1.2</td>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '<tr>'.Tags::CRLF;
        $expected .= '<td>r.2.1</td>'.Tags::CRLF;
        $expected .= '<td>r.2.2</td>'.Tags::CRLF;
        $expected .= '</tr>'.Tags::CRLF;
        $expected .= '</table>'.Tags::CRLF;

        $tbl = new Table();
        $tbl->caption('caption');
        $tbl->col(['class' => 'c-1-1']);
        $tbl->col(['class' => 'c-1-2']);
        $tbl->colgroup();
        $tbl->td('r.1.1');
        $tbl->td('r.1.2');
        $tbl->tr();
        $tbl->td('r.2.1');
        $tbl->td('r.2.2');
        $tbl->tr();
        $result = $tbl->generate();

        $this->assertEquals($expected, $result);
    }
}
