<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Session;

use MS\LightFramework\Base;
use MS\LightFramework\Variables\Variables;


/**
 * Class Revalidation
 *
 * @package MS\LightFramework\Session
 */
final class Revalidation
{
    private static $initialized = false;
    private static $revalidationTime = 300;

    /**
     * Sets static class variables
     */
    private static function init(): void
    {
        if (!static::$initialized) {
            $baseClass = Base::getInstance();
            static::$initialized = true;
            static::$revalidationTime = (int)$baseClass->Session->RevalidationTimeout;
        }
    }

    /**
     * Sets revalidation timestamp
     *
     * @param string $variableName
     * @return bool
     */
    public static function setRevalidationTime(string $variableName = '_MF_SESSION_REVALIDATED_AT_'): bool
    {
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $vHandler->session->set($variableName, \time());
        return true;
    }

    /**
     * Checks if session deas need revalidation
     *
     * @param string $variableName
     * @return bool
     */
    public static function doesNeedRevalidation(string $variableName = '_MF_SESSION_REVALIDATED_AT_'): bool
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $validationTS = $vHandler->session->get($variableName, $vHandler::TYPE_INT);
        return $validationTS < \time() - static::$revalidationTime ? true : false;
    }

    /**
     * Sets revalidation counter value
     *
     * @param string $variableName
     * @param int    $value
     */
    public static function setRevalidationCounter(string $variableName = '_MF_SESSION_REVALIDATION_COUNTER_',
                                                  int $value = 0): void
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $vHandler->session->set($variableName, $value);
    }

    /**
     * Increases revalidation counter
     *
     * @param string $variableName
     */
    public static function increaseRevalidationCounter(string $variableName = '_MF_SESSION_REVALIDATION_COUNTER_'): void
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        $vHandler->session->set($variableName, $vHandler->session->get($variableName, Variables::TYPE_INT) + 1);
    }

    /**
     * Resets revalidation counter & regenerates session id
     *
     * @param string $variableName
     */
    public static function resetRevalidationCounter(string $variableName = '_MF_SESSION_REVALIDATION_COUNTER_'): void
    {
        static::init();
        $variableName = (string)$variableName;
        $vHandler = Variables::getInstance();
        if ($vHandler->session->get($variableName, Variables::TYPE_INT) > 0) {
            $vHandler->session->set($variableName, 0);
            \session_regenerate_id(true);
        }
    }
}
