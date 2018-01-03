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
 * Class FormButtons
 *
 * @package MS\LightFramework\Html\Form
 */
class FormButtons extends Element
{
    private $buttons = [];

    /**
     * FormButtons constructor.
     *
     * @param array $buttons
     */
    public function __construct(array $buttons = [])
    {
        $this->buttons = $buttons;
    }

    /**
     * Generates FormSubtitle and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        return Tags::div($this->buttons, ['class' => 'buttons']);
    }
}
