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
 * Class FormConditional
 *
 * @package MS\LightFramework\Html\Form
 */
class FormConditional extends ElementComposite
{
    protected $vHandler = null;
    protected $notAllowedElements = [
        'MS\LightFramework\Html\Form\Form',
        'MS\LightFramework\Html\Form\FormStatus',
        'MS\LightFramework\Html\Form\FormSet'
    ];
    private $attributes = [];

    /**
     * FormConditional constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (!\array_key_exists('data-conditional-id', $attributes)) {
            throw new InvalidArgumentException('FormConditional must have "data-conditional-id" attribute');
        }
        $this->vHandler = Variables::getInstance();
        $attributes['class'] = 'conditional';
        $this->attributes = $attributes;
    }

    /**
     * Generates from conditional and returns it
     *
     * @return string
     */
    public function generate(): string
    {
        $condID = (string)$this->attributes['data-conditional-id'];
        $getState = $this->vHandler->get->get($condID.'_state', Variables::TYPE_INT) == 1;
        $postState = $this->vHandler->post->get($condID.'_state', Variables::TYPE_INT) == 1;
        $visible = $getState || $postState;
        $this->attributes['class'] .= $visible === true ? ' visible' : '';

        $elements = [];
        $elements[] = Controlls\Text::inputHidden($condID.'_state', (int)$visible, ['method-get' => true, 'id' => '']);
        foreach ($this->elements as $element) {
            /** @noinspection PhpUndefinedMethodInspection */
            $elements[] = $element->generate();
        }
        return Tags::div($elements, $this->attributes);
    }
}
