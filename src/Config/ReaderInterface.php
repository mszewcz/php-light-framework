<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Config;


/**
 * Interface ReaderInterface
 *
 * @package MS\LightFramework\Config\Reader
 */
interface ReaderInterface
{
    /**
     * Reads configuration from file and converts it to array
     *
     * @param string $filename
     * @param bool   $returnArray
     * @return mixed
     */
    public function fromFile(string $filename = '', bool $returnArray = false);
}
