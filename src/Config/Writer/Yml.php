<?php
/**
 * Project: PHP Light Framework
 *
 * @author      Michal Szewczyk <ms@msworks.pl>
 * @copyright   Michal Szewczyk
 * @license     MIT
 */
declare(strict_types=1);

namespace MS\LightFramework\Config\Writer;

use MS\LightFramework\Config\AbstractWriter;


/**
 * Class Yml
 *
 * @package MS\LightFramework\Config\Writer
 */
class Yml extends AbstractWriter
{

    /**
     * Transforms configuration array into string
     *
     * @param array $config
     * @return string
     */
    public function transform(array $config): string
    {
        if (!\extension_loaded('php_yaml')) {
            return '';
        }
        return \yaml_emit($config, YAML_UTF8_ENCODING, YAML_LN_BREAK);
    }
}
