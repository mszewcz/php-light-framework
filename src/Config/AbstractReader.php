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

use MS\LightFramework\Exceptions\InvalidArgumentException;
use MS\LightFramework\Exceptions\RuntimeException;


/**
 * Class AbstractReader
 *
 * @package MS\LightFramework\Config\Reader
 */
abstract class AbstractReader implements ReaderInterface
{
    /**
     * Reads configuration from file and converts it to array
     *
     * @param string $filename
     * @param bool   $returnArray
     * @return array|bool|Config|string
     */
    public function fromFile(string $filename = '', bool $returnArray = false)
    {
        if (empty($filename)) {
            throw new InvalidArgumentException('File name must be specified');
        }

        \set_error_handler(
            function ($error, $message = '') use ($filename) {
                $exMsg = \sprintf('Error reading from "%s": %s', $filename, $message);
                throw new RuntimeException($exMsg, $error);
            },
            E_WARNING
        );

        try {
            $config = $this->transform(\file_get_contents($filename));
            \restore_error_handler();
            return $returnArray ? $config : new Config($config);
        } catch (RuntimeException $e) {
            \restore_error_handler();
            throw $e;
        }
    }

    /**
     * Transforms configuration string into an array
     *
     * @param   string $config
     * @return  array
     */
    abstract protected function transform(string $config = '');
}
