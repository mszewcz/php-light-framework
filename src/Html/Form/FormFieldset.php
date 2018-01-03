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
 * Class FormFieldset
 *
 * @package MS\LightFramework\Html\Form
 */
class FormFieldset extends ElementComposite
{
    protected $notAllowedElements = [
        'MS\LightFramework\Html\Form\Form',
        'MS\LightFramework\Html\Form\FormStatus',
        'MS\LightFramework\Html\Form\FormSet'
    ];
    private $label = '';
    private $attributes = [];

    /**
     * FormFieldset constructor.
     *
     * @param string $label
     * @param array  $attributes
     */
    public function __construct(string $label = '', array $attributes = [])
    {
        $attributes['class'] = 'fieldset';
        $this->label = $label;
        $this->attributes = $attributes;
    }

    /**
     * Generates from fieldset and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $elements = [];
        if ($this->label != '') {
            $elements[] = Tags::legend($this->label, []);
        }
        foreach ($this->elements as $element) {
            /** @noinspection PhpUndefinedMethodInspection */
            $elements[] = $element->generate();
        }
        return Tags::fieldset($elements, $this->attributes);
    }
}
