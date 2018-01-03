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


/**
 * Class Element
 *
 * @package MS\LightFramework\Html\Form
 */
abstract class Element
{
    /**
     * Returns PHP constant or default value if not defined
     *
     * @param mixed $name
     * @return mixed
     */
    protected function getConstant($name = '')
    {
        return defined($name) ? constant($name) : $name;
    }

    /**
     * Generates HTML from elements and returns code
     *
     * @return string
     */
    abstract public function generate(): string;
}
