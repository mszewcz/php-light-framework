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
 * Class Ini
 *
 * @package MS\LightFramework\Config\Reader
 */
class Ini extends AbstractReader
{
    private $normalizeFrom = ['\\u003C', '\\u003E', '\\u0022', '\\u0027', '\\u003B', '\\u0026'];
    private $normalizeTo = ['<', '>', '"', '\'', ';', '&'];

    /**
     * Transforms configuration string into an array
     *
     * @param string $config
     * @return array
     */
    public function transform(string $config = ''): array
    {
        $parsed = [];
        if (!empty($config)) {
            $config = \parse_ini_string($config, true, INI_SCANNER_RAW);
            if (\is_array($config)) {
                $parsed = $this->processNode($config);
            }
        }
        return $parsed;
    }

    /**
     * Processes INI config node
     *
     * @param array $node
     * @return array
     */
    private function processNode(array $node): array
    {
        $config = [];
        foreach ($node as $name => $value) {
            if (\preg_match('/^num__([0-9]+)$/', $name, $matches)) {
                $name = $matches[1];
            }
            if (\count($nameEx = \explode('.', $name)) > 1) {
                $name = \array_shift($nameEx);
                $mergeBase = isset($config[$name]) ? $config[$name] : [];
                $mergeWith = $this->processNode([\implode('.', $nameEx) => $value]);
                $config[$name] = \array_merge_recursive($mergeBase, $mergeWith);
            } elseif (\is_array($value)) {
                $config[$name] = $this->processNode($value);
            } else {
                $config[$name] = $this->castValue((string)$value);
            }
        }
        return $config;
    }

    /**
     * Casts value to a specified type
     *
     * @param string $value
     * @return mixed
     */
    private function castValue(string $value)
    {
        $value = $this->normalize($value);
        if (\preg_match('/^[0-9]+$/', $value)) {
            $value = (int)$value;
        } elseif (\preg_match('/^[0-9\.]+$/', $value)) {
            $value = (float)$value;
        } elseif (\preg_match('/^true|false$/', $value)) {
            $value = $value === 'true' ? true : false;
        }
        return $value;
    }

    /**
     * Changes some characters from their utf-8 to normal representation
     *
     * @param   string $string
     * @return  string
     */
    private function normalize(string $string): string
    {
        return \str_replace($this->normalizeFrom, $this->normalizeTo, $string);
    }
}
