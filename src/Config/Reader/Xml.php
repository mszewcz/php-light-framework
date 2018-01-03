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
 * Class Xml
 *
 * @package MS\LightFramework\Config\Reader
 */
class Xml extends AbstractReader
{
    /**
     * Transforms configuration string into an array
     *
     * @param string $config
     * @return array
     */
    public function transform(string $config = ''): array
    {
        $config = \preg_replace('/^(<\?xml\ version="[^"]+"\ encoding="[^"]+"\?>)?(\n)?/i', '', $config);
        $parsed = [];
        if (!empty($config)) {
            $node = new \SimpleXMLElement($config);
            if ($node->count() > 0) {
                $parsed = $this->processNode($node->children());
            }
        }
        return $parsed;
    }

    /**
     * Processes XML node
     *
     * @param \SimpleXMLElement $node
     * @return array
     */
    private function processNode(\SimpleXMLElement $node): array
    {
        $config = [];
        foreach ($node as $name => $value) {
            if (\preg_match('/^num__([0-9]+)$/', $name, $matches)) {
                $name = $matches[1];
            }
            if ($value->count() > 0) {
                $config[$name] = $this->processNode($value->children());
            } else {
                $value = (string)$value;
                $config[$name] = $value;

                if (\preg_match('/^[0-9]+$/', $value)) {
                    $config[$name] = (int)$value;
                } elseif (\preg_match('/^[0-9\.]+$/', $value)) {
                    $config[$name] = (float)$value;
                } elseif (\preg_match('/^true|false$/', $value)) {
                    $config[$name] = $value === 'true' ? true : false;
                }
            }
        }
        return $config;
    }
}
