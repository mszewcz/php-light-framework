<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Db\MySQL;


/**
 * Interface QueryInterface
 *
 * @package MS\LightFramework\Db\MySQL
 */
interface QueryInterface
{
    /**
     * Builds query
     *
     * @return string
     */
    public function ___build(): string;
}
