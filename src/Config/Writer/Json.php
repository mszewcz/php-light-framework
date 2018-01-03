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
 * Class Json
 *
 * @package MS\LightFramework\Config\Writer
 */
class Json extends AbstractWriter
{
    /**
     * Transforms configuration array into string
     *
     * @param array $array
     * @return string
     */
    public function transform(array $array): string
    {
        return json_encode(
            $array,
            JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_FORCE_OBJECT | JSON_PRETTY_PRINT |
            JSON_UNESCAPED_SLASHES
        );
    }
}
