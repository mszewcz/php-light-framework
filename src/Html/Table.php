<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html;


/**
 * Class Table
 *
 * @package MS\LightFramework\Html
 */
final class Table
{
    private $tableAttributes = [];
    private $tableArray = [];
    private $colArray = [];
    private $trArray = [];
    private $tdArray = [];

    /**
     * Table constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->tableAttributes = $attributes;
    }

    /**
     * This method inserts <caption> tag into table
     *
     * @param string $text
     * @param array  $attributes
     */
    public function caption(string $text = '', array $attributes = []): void
    {
        if (\count($this->tableArray) == 0) {
            $this->tableArray[] = Tags::caption($text, $attributes);
        }
    }

    /**
     * This method inserts <col> tag into colgroup
     *
     * @param array $attributes
     */
    public function col(array $attributes = []): void
    {
        $this->colArray[] = Tags::col($attributes);
    }

    /**
     * This method inserts <colgroup> tag into table
     *
     * @param array $attributes
     */
    public function colgroup(array $attributes = []): void
    {
        $this->tableArray[] = Tags::colgroup($this->colArray, $attributes);
        $this->colArray = [];
    }

    /**
     * This method inserts <th> tag into tr
     *
     * @param string $text
     * @param array  $attributes
     */
    public function th(string $text = '', array $attributes = []): void
    {
        $this->tdArray[] = Tags::th($text, $attributes);
    }

    /**
     * This method inserts <td> tag into tr
     *
     * @param string $text
     * @param array  $attributes
     */
    public function td(string $text = '', array $attributes = []): void
    {
        $this->tdArray[] = Tags::td($text, $attributes);
    }

    /**
     * This method inserts <tr> tag into table
     *
     * @param array $attributes
     */
    public function tr(array $attributes = []): void
    {
        $this->trArray[] = Tags::tr($this->tdArray, $attributes);
        $this->tdArray = [];
    }

    /**
     * This method inserts <thead> tag into table
     *
     * @param array $attributes
     */
    public function thead(array $attributes = []): void
    {
        $this->tableArray[] = Tags::thead($this->trArray, $attributes);
        $this->trArray = [];
    }

    /**
     * This method inserts <tbody> tag into table
     *
     * @param array $attributes
     */
    public function tbody(array $attributes = []): void
    {
        $this->tableArray[] = Tags::tbody($this->trArray, $attributes);
        $this->trArray = [];
    }

    /**
     * This method inserts <tfoot> tag into table
     *
     * @param array $attributes
     */
    public function tfoot(array $attributes = []): void
    {
        $this->tableArray[] = Tags::tfoot($this->trArray, $attributes);
        $this->trArray = [];
    }

    /**
     * This method generates and returns <table>
     *
     * @return string
     */
    public function generate(): string
    {
        foreach ($this->trArray as $tr) {
            $this->tableArray[] = $tr;
        }
        $table = Tags::table($this->tableArray, $this->tableAttributes);

        $this->tableAattributes = [];
        $this->tableArray = [];
        $this->trArray = [];

        return $table;
    }
}
