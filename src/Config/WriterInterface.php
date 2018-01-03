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
 * Interface WriterInterface
 *
 * @package MS\LightFramework\Config\Writer
 */
interface WriterInterface
{
    /**
     * Writes configuration array/object to file
     *
     * @param string $filename
     * @param        $config
     * @return bool
     */
    public function toFile(string $filename = '', $config = null): bool;
}
