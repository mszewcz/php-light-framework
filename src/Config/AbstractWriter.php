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
 * Class AbstractWriter
 *
 * @package MS\LightFramework\Config\Writer
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * Writes configuration array/object to file
     *
     * @param string $filename
     * @param mixed $config
     * @return bool
     */
    public function toFile(string $filename = '', $config = null): bool
    {
        if (empty($filename)) {
            throw new InvalidArgumentException('File name must be specified');
        }

        if ($config instanceof \Traversable) {
            $config = \method_exists($config, 'toArray') ? $config->toArray() : \iterator_to_array($config, true);
        }

        if (!\is_array($config)) {
            throw new InvalidArgumentException('Config data must be either array or \Traversable');
        }

        \set_error_handler(
            function ($error, $message = '') use ($filename) {
                $exMsg = \sprintf('Error writing to "%s": %s', $filename, $message);
                throw new RuntimeException($exMsg, $error);
            },
            E_WARNING
        );

        try {
            $ret = \file_put_contents($filename, $this->transform($config), LOCK_EX) !== false ? true : false;
            \restore_error_handler();
            return $ret;
        } catch (RuntimeException $e) {
            \restore_error_handler();
            throw $e;
        }
    }

    /**
     * Transforms configuration array into string
     *
     * @param array $config
     * @return string
     */
    abstract protected function transform(array $config): string;
}
