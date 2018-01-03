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
 * Class FormControll
 *
 * @package MS\LightFramework\Html\Form
 */
class FormControll extends Element
{
    private $controllData = [];

    /**
     * FormControll constructor.
     *
     * @param array $controllData
     */
    public function __construct(array $controllData = [])
    {
        $this->controllData = $controllData;
    }

    /**
     * Generates FormControll and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $fieldId = $this->controllData['field-id'];
        $label = $this->controllData['label'];
        $label = $label != Tags::NBSP ? $label.':' : $label;
        $icons = '';
        $class = ['controll'];
        $ret = [];

        if ($this->controllData['required'] == 1) {
            $class[] = 'is-required';
        }
        if ($this->controllData['help'] != '') {
            $icons .= Tags::i($this->controllData['help'], ['class' => 'icon-help']);
            $class[] = 'has-help';
        }
        if ($this->controllData['error'] != '') {
            $icons .= Tags::i($this->controllData['error'], ['class' => 'icon-error']);
            $class[] = 'has-error';
        }

        $ctrl = Tags::div($label, ['class' => 'label']);
        $ctrl .= Tags::div($this->controllData['controll'], ['class' => 'html-controll']);
        $ctrl .= Tags::div($icons, ['class' => 'icons']);
        $ret[] = Tags::div($ctrl, ['class' => \implode(' ', $class), 'data-field-id' => $fieldId]);

        if ($this->controllData['script'] != '') {
            $ret[] = Tags::script(\str_replace(Tags::CRLF, ' ', $this->controllData['script']));
        }

        return \implode(Tags::CRLF, $ret);
    }
}
