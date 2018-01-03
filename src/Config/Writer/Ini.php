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
 * INI config writer
 */
class Ini extends AbstractWriter
{
    private $newline = 0x0A;
    private $normalizeFrom = ['<', '>', '"', '\'', ';', '&'];
    private $normalizeTo = ['\\u003C', '\\u003E', '\\u0022', '\\u0027', '\\u003B', '\\u0026'];

    /**
     * Transforms configuration array into string
     *
     * @param   array $config
     * @return  string
     */
    public function transform(array $config): string
    {
        $cfg = '';
        foreach ($config as $nodeName => $nodeData) {
            $nodeName = \is_numeric($nodeName) ? 'num__'.$nodeName : $nodeName;

            if (\is_array($nodeData)) {
                $cfg .= \sprintf('%s[%s]%s', \chr($this->newline), $nodeName, \chr($this->newline));
                $cfg .= $this->addLine($nodeName, $nodeData, 0);
            } elseif (\is_bool($nodeData)) {
                $nodeData = $nodeData === true ? 'true' : 'false';
                $cfg .= \sprintf('%s = %s%s', $nodeName, $nodeData, \chr($this->newline));
            } else {
                $nodeData = $this->normalize((string)$nodeData);
                $cfg .= \sprintf('%s = %s%s', $nodeName, $nodeData, \chr($this->newline));
            }
        }
        return $cfg;
    }

    /**
     * Adds new line to configuration
     *
     * @param string $name
     * @param array  $config
     * @param int    $depth
     * @return string
     */
    private function addLine(string $name, array $config, int $depth): string
    {
        $cfg = '';
        foreach ($config as $nodeName => $nodeData) {
            $nodeName = \is_numeric($nodeName) ? 'num__'.$nodeName : $nodeName;
            $nodeName = $depth > 0 ? \sprintf('%s.%s', $name, $nodeName) : $nodeName;

            if (\is_array($nodeData)) {
                $cfg .= $this->addLine($nodeName, $nodeData, $depth + 1);
            } elseif (\is_bool($nodeData)) {
                $nodeData = $nodeData === true ? 'true' : 'false';
                $cfg .= \sprintf('%s = %s%s', $nodeName, $nodeData, \chr($this->newline));
            } else {
                $nodeData = $this->normalize((string)$nodeData);
                $cfg .= \sprintf('%s = %s%s', $nodeName, $nodeData, \chr($this->newline));
            }
        }
        return $cfg;
    }

    /**
     * Changes some characters to their utf-8 representation
     *
     * @param string $string
     * @return string
     */
    private function normalize(string $string = ''): string
    {
        return \str_replace($this->normalizeFrom, $this->normalizeTo, $string);
    }
}
