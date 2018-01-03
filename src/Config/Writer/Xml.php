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
 * Class Xml
 *
 * @package MS\LightFramework\Config\Writer
 */
class Xml extends AbstractWriter
{
    /**
     * Transforms configuration array into string
     *
     * @param array $config
     * @return string
     */
    public function transform(array $config): string
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString(\str_repeat(' ', 4));
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('config');

        foreach ($config as $nodeName => $nodeData) {
            $nodeName = \is_numeric($nodeName) ? 'num__'.$nodeName : $nodeName;

            if (\is_array($nodeData)) {
                $this->addChildNode($nodeName, $nodeData, $writer);
            } elseif (\is_bool($nodeData)) {
                $writer->writeElement($nodeName, $nodeData === true ? 'true' : 'false');
            } else {
                $writer->writeElement($nodeName, (string)$nodeData);
            }
        }

        $writer->endElement();
        $writer->endDocument();
        return $writer->outputMemory();
    }

    /**
     * Adds new child node to configuration XML
     *
     * @param string     $name
     * @param array      $config
     * @param \XMLWriter $writer
     */
    private function addChildNode(string $name, array $config, \XMLWriter $writer): void
    {
        $writer->startElement($name);

        foreach ($config as $nodeName => $nodeData) {
            $nodeName = \is_numeric($nodeName) ? 'num__'.$nodeName : $nodeName;

            if (\is_array($nodeData)) {
                $this->addChildNode($nodeName, $nodeData, $writer);
            } elseif (\is_bool($nodeData)) {
                $writer->writeElement($nodeName, $nodeData === true ? 'true' : 'false');
            } else {
                $writer->writeElement($nodeName, (string)$nodeData);
            }
        }

        $writer->endElement();
    }
}
