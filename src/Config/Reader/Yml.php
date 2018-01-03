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
 * Class Yaml
 *
 * @package MS\LightFramework\Config\Reader
 */
class Yml extends AbstractReader
{
    /**
     * Transforms configuration string into an array
     *
     * @param string $config
     * @return array
     */
    public function transform(string $config = ''): array
    {
        $parsed = [];

        if (!\extension_loaded('php_yaml')) {
            return $parsed;
        }
        if (!empty($config)) {
            $parsed = \yaml_parse($config);
            $parsed = $parsed !== null ? $parsed : [];
        }

        return $parsed;
    }
}
