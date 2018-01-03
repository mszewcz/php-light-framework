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


/**
 * Class ElementComposite
 *
 * @package MS\LightFramework\Html\Form
 */
abstract class ElementComposite extends Element
{
    protected $elements = [];
    protected $notAllowedElements = [];

    /**
     * Checks if element is not an instance of members of $notAllowedElements[] and adds it to composite.
     *
     * @param Element $element
     * @return Element
     */
    public function addElement(Element $element): Element
    {
        foreach ($this->notAllowedElements as $notAllowed) {
            if ($element instanceof $notAllowed) {
                $callerName = \str_replace([__NAMESPACE__, '\\'], '', \get_called_class());
                $notAllowedName = \str_replace([__NAMESPACE__, '\\'], '', $notAllowed);
                throw new InvalidArgumentException($callerName.' cannot contain '.$notAllowedName);
            }
        }
        $this->elements[] = $element;
        return $element;
    }
}
