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
use MS\LightFramework\Html\Controlls;
use MS\LightFramework\Html\Tags;
use MS\LightFramework\Variables\Variables;


/**
 * Class FormSet
 *
 * @package MS\LightFramework\Html\Form
 */
class FormSet extends ElementComposite
{
    protected $vHandler = null;
    protected $notAllowedElements = [
        'MS\LightFramework\Html\Form\Form',
        'MS\LightFramework\Html\Form\FormStatus',
        'MS\LightFramework\Html\Form\FormSet',
    ];
    private $name = '';
    private $attributes = [];

    /**
     * FormSet constructor.
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct(string $name = '', array $attributes = [])
    {
        if (!\array_key_exists('data-set-id', $attributes)) {
            throw new InvalidArgumentException('FormSet must have "data-set-id" attribute');
        }
        $attributes['class'] = 'set';
        $this->vHandler = Variables::getInstance();
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * Generates from set and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $setID = (string)$this->attributes['data-set-id'];
        $getState = $this->vHandler->get->get($setID.'_state', Variables::TYPE_INT) == 1;
        $postState = $this->vHandler->post->get($setID.'_state', Variables::TYPE_INT) == 1;
        $expanded = $getState || $postState;
        $this->attributes['class'] .= $expanded === true ? ' expanded' : '';

        $title = [];
        $title[] = Tags::span($this->name, ['class' => 'name']);
        $title[] = Tags::span('',
            ['class' => 'expand', 'title' => $this->getConstant('TXT_PANEL_DATA_FORM_SET_EXPAND')]);
        $title[] = Tags::span('',
            ['class' => 'collapse', 'title' => $this->getConstant('TXT_PANEL_DATA_FORM_SET_COLLAPSE')]);
        $title[] = Controlls\Text::inputHidden($setID.'_state', (int)$expanded, ['method-get' => true, 'id' => '']);

        $elements = [];
        foreach ($this->elements as $element) {
            /** @noinspection PhpUndefinedMethodInspection */
            $elements[] = $element->generate();
        }

        $set = [];
        $set[] = Tags::div($title, ['class' => 'title cf']);
        $set[] = Tags::div($elements, ['class' => 'content border-radius-5']);

        return Tags::div($set, $this->attributes);
    }
}
