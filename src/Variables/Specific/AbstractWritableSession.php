<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Variables\Specific;


/**
 * Class AbstractWritableSession
 *
 * @package MS\LightFramework\Variables\Specific
 */
abstract class AbstractWritableSession extends AbstractReadOnly
{
    /**
     * Sets variable.
     *
     * @param string $variableName
     * @param        $variableValue
     * @return Session
     */
    abstract public function set(string $variableName, $variableValue): Session;

    /**
     * Clears (unsets) variable.
     *
     * @param string $variableName
     * @return Session
     */
    abstract public function clear(string $variableName): Session;
}
