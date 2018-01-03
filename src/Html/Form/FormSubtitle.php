<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Html\Form;

use MS\LightFramework\Html\Tags;


/**
 * Class FormSubtitle
 *
 * @package MS\LightFramework\Html\Form
 */
class FormSubtitle extends Element
{
    private $text = '';

    /**
     * FormSubtitle constructor.
     *
     * @param string $text
     */
    public function __construct(string $text = '')
    {
        $this->text = $text;
    }

    /**
     * Generates FormSubtitle and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        return Tags::div($this->text, ['class' => 'subtitle']);
    }
}
