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

use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Html\Tags;


/**
 * Class Form
 *
 * @package MS\LightFramework\Html\Form
 */
class Form extends ElementComposite
{
    protected $notAllowedElements = ['MS\LightFramework\Html\Form\Form'];
    private $hasStatus = false;
    private $attributes = [];
    private $hiddens = [];

    /**
     * Form constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (isset($attributes['hiddens'])) {
            $this->hiddens = $attributes['hiddens'];
            unset($attributes['hiddens']);
        }
        if (!isset($attributes['method'])) {
            $attributes['method'] = 'post';
        }
        if (!isset($attributes['enctype'])) {
            $attributes['enctype'] = 'multipart/form-data';
        }
        if (!isset($attributes['id'])) {
            $attributes['id'] = 'data-form';
        }
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'data-form';
        }
        if (isset($attributes['label-width'])) {
            $attributes['class'] .= ' label-width-'.\intval($attributes['label-width']);
            unset($attributes['label-width']);
        }
        $this->attributes = $attributes;
    }

    /**
     * Adds element to composite. Throws InvalidArgumentException if more than one FormStatus element is added.
     *
     * @param Element $element
     * @return Element
     */
    public function addElement(Element $element): Element
    {
        if ($element instanceof FormStatus) {
            if ($this->hasStatus === true) {
                throw new InvalidArgumentException('Form can contain only one FormStatus element');
            }
            $this->hasStatus = true;
        }
        return parent::addElement($element);
    }

    /**
     * Generates from and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $elements = [];
        foreach ($this->hiddens as $hidden) {
            $elements[] = $hidden;
        }
        foreach ($this->elements as $element) {
            /** @noinspection PhpUndefinedMethodInspection */
            $elements[] = $element->generate();
        }
        return Tags::form($elements, $this->attributes);
    }
}
