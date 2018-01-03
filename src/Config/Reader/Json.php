<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Config\Reader;

use MS\LightFramework\Config\AbstractReader;


/**
 * Class Json
 *
 * @package MS\LightFramework\Config\Reader
 */
class Json extends AbstractReader
{
    /**
     * Transforms configuration string into an array
     *
     * @param   string $config
     * @return  array
     */
    public function transform(string $config = ''): array
    {
        $parsed = [];
        if (!empty($config)) {
            $decoded = @\json_decode($config, true, 512);
            $parsed = $decoded !== null ? $decoded : [];
        }
        return $parsed;
    }
}
